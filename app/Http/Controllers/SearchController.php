<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatchResource;
use App\Models\CompanyService;
use App\Models\Matches;
use App\Models\Service;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        $request->validate(([
            'zipcode' => 'required',
            'service_id' => 'required',
        ]));

        $zipcode = $request->zipcode;
        $service_id = $request->service_id;
        $service = Service::find($service_id);
        $user = auth()->user();

        //Companies conditions
        //1.- Non users repeated matches
        $companiesMatchIds = Matches::where('service_id', $service_id)->where('email', 'rogerdavid444@gmail.com')->pluck('company_id')
        ->toArray();
        //2.-Non companies where service is paused
        $companiesServicePause = CompanyService::where('service_id', $service_id)->where('pause',1)->pluck('company_id');

        $matches = $service->companyServiceZip
        ->where('zipcode_id',$zipcode['id'])
        ->whereNotIn('company_id', $companiesMatchIds)
        ->whereNotIn('company_id', $companiesServicePause)
        ->take(3);

        $matches = $matches->map(function($match) use($service_id){
            //Inserto Matches
            Matches::create([
                'email' => 'rogerdavid444@gmail.com',
                'company_id' => $match->company->id,
                'service_id' => $service_id
            ]);

            //Envio correos a cliente y compañia
            return $match->company;
        });

        return MatchResource::collection($matches);
    }
}
