<?php

namespace App\Http\Controllers;

use App\Events\MatchProcessed;
use App\Http\Resources\MatchResource;
use App\Models\CompanyService;
use App\Models\Matches;
use App\Models\Company;
use App\Models\Service;
use App\Models\Zipcode;
use App\Models\User;
use App\Notifications\MatchesCompanyNotification;
use App\Notifications\MatchesUserNotification;
use Illuminate\Http\Request;

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
        $service = Service::find($service_id);
        $price = $service->price > 0 ? round($service->price * 100, 0) : 0;

        $user = User::where('email', $request->email)->first();
        if(auth()->user()){
            $user = auth()->user();
        }
        $zipcode = Zipcode::where('zipcode', $zipcode)->first();
        //Companies conditions
        //1.- Non users repeated matches
        $companiesMatchIds = Matches::where('service_id', $service_id)->where('email', $user->email)->pluck('company_id')
        ->toArray();
        //2.-Non companies where service is paused
        $companiesServicePause = CompanyService::where('service_id', $service_id)->where('pause',1)->pluck('company_id');
        $matches = $service->companyServiceZip
        ->where('zipcode_id',$zipcode->id)
        ->whereNotIn('company_id', $companiesMatchIds)
        ->whereNotIn('company_id', $companiesServicePause)
        ->take(3);

        //No matches actions
        if(!$matches){

        }
        //Send information to admin

        $matches = $matches->map(function($match) use($service_id,$user, $project_id, $service){
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
                        'customer' => $user->stripe_client_id,
                        ]);

                        if($payment_methods->data){
                            $payment_method_id = $payment_methods->data[0]->id;
                        }

                    }catch (\Stripe\Exception\ApiErrorException $e) {
                        // Maneja el error de Stripe aquí
                        // Puedes registrar el error, mostrar un mensaje al usuario, etc.
                        // Pero el código continuará ejecutándose después de este bloque catch
                        echo 'Error: ' . $e->getMessage();
                    }

                    if($payment_method_id){
                        if($service->price > 0){
                            $payment = $stripe->paymentIntents->create([
                                'amount' => $service->price  * 100,
                                'currency' => 'usd',
                                'customer' => $user->stripe_client_id,
                                'payment_method' => $payment_method_id,
                                'confirm' => true,
                                'description' => 'Match '.$service->name,
                                'confirmation_method' => 'automatic', // Utiliza 'automatic' para pagos automáticos
                                'metadata' => [
                                    'customer_name' => 'Juan Pérez',
                                    // Agrega más metadatos según sea necesario
                                ],
                                'return_url'=>'https://example.com/success'
                            ]);
                        }
                    }

                }


                foreach ($match->company->users as $key => $userCompany) {
                    $userCompany->transactions()->create([
                        'project_id' => $project_id,
                        'service_id' => $service_id,
                        'price'=> $service->price
                    ]);
                }
                $user->link = config('app.app_url').'/user/companies/profile/projects/'. $project_id;
                $user->service = $service;
                $userCompany->notify(new MatchesCompanyNotification($user));
            }

            return $match->company;
        });

        if(count($matches)){
            $data = ['matches' => $matches, 'service'=>$service];
            $user->notify(new MatchesUserNotification($data));
        }

        return MatchResource::collection($matches);
    }
}
