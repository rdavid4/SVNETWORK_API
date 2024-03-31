<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatchResource;
use App\Models\CompanyService;
use App\Models\Matches;
use App\Models\Service;
use App\Models\Zipcode;
use App\Models\User;
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
        //Send information to admin




        $matches = $matches->map(function($match) use($service_id,$user, $project_id, $service){
            //Inserto Matches
            // Matches::create([
            //     'email' => $user->email,
            //     'user_id' => $user->id,
            //     'company_id' => $match->company->id,
            //     'service_id' => $service_id
            // ]);

            //Envio cobro a compania en caso de que sea verificada y creada por usuario
            $match->company->projects()->attach($project_id);

            if($match->company->users){
                foreach ($match->company->users as $key => $user) {
                    $user->transactions()->create([
                        'project_id' => $project_id,
                        'service_id' => $service_id,
                        'price'=> $service->price
                    ]);
                }
            }


            //Envio correos a cliente y compañia verificada y creada por usuario en otro caso no enviar correo a la compania
            return $match->company;
        });

        return MatchResource::collection($matches);
    }
}
