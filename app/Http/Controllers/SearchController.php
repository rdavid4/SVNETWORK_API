<?php

namespace App\Http\Controllers;

use App\Events\MatchProcessed;
use App\Http\Resources\MatchesResource;
use App\Http\Resources\MatchResource;
use App\Http\Resources\NoMatchesResource;
use App\Models\CompanyService;
use App\Models\Matches;
use App\Models\Company;
use App\Models\NoMatches;
use App\Models\Project;
use App\Models\Service;
use App\Models\Transactions;
use App\Models\Zipcode;
use App\Models\User;
use App\Notifications\MatchesCompanyNotification;
use App\Notifications\MatchesUserNotification;
use App\Notifications\NoMatchesAdminNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;
class SearchController extends Controller
{
    public function search(Request $request){
        $request->validate(([
            'zipcode' => 'required',
            'service_id' => 'required',
            'project_id' => 'required',
            'email' => 'required',
        ]));

        $zipcode = $request->zipcode;
        $service_id = $request->service_id;
        $project_id = $request->project_id;
        $project = Project::find($project_id);
        $service = Service::find($service_id);
        $price = $service->price > 0 ? round($service->price * 100, 0) : 0;

        $user = User::where('email', $request->email)->first();
        $admins = User::where('is_admin',1)->get();
        if(auth()->user()){
            $user = auth()->user();
        }
        $zipcode = Zipcode::where('zipcode', $zipcode)->first();
        //Companies conditions
        //1.- Non users repeated matches
        $today = Carbon::today();
        $companiesMatchIds = Matches::where('service_id', $service_id)->where('email', $user->email)->whereDate('created_at', $today)->pluck('company_id')
        ->toArray();
        //2.-Non companies where service is paused
        $companiesServicePause = CompanyService::where('service_id', $service_id)->where('pause',1)->pluck('company_id');
        $companies = Company::all();

        //3.-Companies without payment method
        $companiesWithoutPaymentMethod = $companies->map(function($company){
             if($company->users->count()){
                return $company->users->first()->stripe_client_id != null ? $company->id : null;
             }
        })->whereNotNull()->toArray();
        //4.-Companies has more than defaults payments
        $companiesDefaults = Transactions::selectRaw('company_id, COUNT(*) as count')
        ->where('paid', 0)
        ->groupBy('company_id')
        ->get();

        $companiesDefaults = $companiesDefaults->map(function($row){
            return $row->count >= 5 ? $row->company_id : null;
        })->whereNotNull()->toArray();

        $matches = $service->companyServiceZip
        ->where('zipcode_id',$zipcode->id)
        ->whereNotIn('company_id', $companiesMatchIds)
        ->whereNotIn('company_id', $companiesServicePause)
        ->whereNotIn('company_id', $companiesDefaults)
        ->whereIn('company_id', $companiesWithoutPaymentMethod)
        ->take(3);

        //No matches actions
        if(count($matches)==0){
            NoMatches::create([
                'email' => $user->email,
                'user_id' => $user->id,
                'project_id' => $project_id,
                'service_id' => $service_id
            ]);
            $data = [
                'user' => $user,
                'service' => $service,
                'zipcode' => $zipcode
            ];
            foreach ($admins as $key => $admin) {
                $admin->notify(new NoMatchesAdminNotification($data));
            }

            return [];
        }else{

            $matches = $matches->map(function($match) use($service_id,$user, $project_id, $service){
                $payment_message = null;
                $status = false;
                $payment_code = null;
                //Inserto Matches
                Matches::create([
                    'email' => $user->email,
                    'user_id' => $user->id,
                    'company_id' => $match->company->id,
                    'project_id' => $project_id,
                    'service_id' => $service_id
                ]);
                //Envio cobro a compania en caso de que sea verificada y creada por usuario
                $company = Company::find($match->company->id);
                $company->projects()->attach($project_id);

                if($match->company->users){

                    $payment_method_id = null;
                    if($match->company->users[0]->stripe_client_id){

                        try{
                            $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
                            $payment_methods = $stripe->paymentMethods->all([
                            'type' => 'card',
                            'limit' => 3,
                            'customer' => $match->company->users[0]->stripe_client_id,
                            ]);


                            if($payment_methods->data){
                                $payment_method_id = $payment_methods->data[0]->id;

                                if($service->price > 0){

                                    $payment = $stripe->paymentIntents->create([
                                        'amount' => $service->price  * 100,
                                        'currency' => 'usd',
                                        'customer' => $match->company->users[0]->stripe_client_id,
                                        'payment_method' => $payment_method_id,
                                        'confirm' => true,
                                        'description' => 'Match '.$service->name,
                                        'confirmation_method' => 'automatic', // Utiliza 'automatic' para pagos automáticos
                                        'metadata' => [
                                            'customer_name' => $match->company->users[0]->name.' '.$match->company->users[0]->surname,
                                            // Agrega más metadatos según sea necesario
                                        ],
                                        'return_url'=> config('app.app_url').'/user/companies/profile'
                                    ]);
                                }
                            }

                            $status = true;

                            Transactions::create([
                                'user_id'=> $match->company->users[0]->id,
                                'project_id'=> $project_id,
                                'service_id'=> $service->id,
                                'stripe_payment_method'=> $payment_method_id,
                                'price'=> $service->price,
                                'company_id'=> $match->company->id,
                                'paid' => $status,
                                'message' => null,
                                'payment_code' => $payment_code,
                            ]);

                        }catch (\Stripe\Exception\ApiErrorException $e) {
                            $status = false;
                            $payment_message = $e->getError()->message;
                            $payment_code = $e->getError()->decline_code;

                            // Maneja el error de Stripe aquí
                            // Puedes registrar el error, mostrar un mensaje al usuario, etc.
                            // Pero el código continuará ejecutándose después de este bloque catch
                            Transactions::create([
                                'user_id'=> $match->company->users[0]->id,
                                'project_id'=> $project_id,
                                'service_id'=> $service->id,
                                'company_id'=> $match->company->id,
                                'stripe_payment_method'=> $payment_method_id,
                                'price'=> $service->price,
                                'paid' => $status,
                                'message' => $payment_message,
                                'payment_code' => $payment_code,
                            ]);
                        }

                    }

                    $user->link = config('app.app_url').'/user/companies/profile/projects/'. $project_id;
                    $user->service = $service;
                    $match->company->users[0]->notify(new MatchesCompanyNotification($user));
                }

                return $match->company;
            });
        }


        if(count($matches)){
            $data = ['matches' => $matches, 'service'=>$service];
            $user->notify(new MatchesUserNotification($data));
        }

        return MatchResource::collection($matches);
    }

    function noMatchesList(){
        $noMatches = NoMatches::all();

        return NoMatchesResource::collection($noMatches);
    }
    function matchesList(){
        $matches = Matches::all();

        return MatchesResource::collection($matches);
    }
}
