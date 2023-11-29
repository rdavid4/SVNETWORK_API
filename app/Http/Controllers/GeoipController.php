<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GeoIp2\Database\Reader;
class GeoipController extends Controller
{
    public function show(Request $request)
    {
        $ip_user = '';
        $ip_user = $request->input('ip') ?? $request->ip();
        $local = str_starts_with("192.168.1.189", $ip_user);

        if ($ip_user == '::' || $ip_user == '::1' || $ip_user == '127.0.0.1' || $local) {
            $ip_user = '73.129.183.9';
        }

        $path = storage_path('app/geoip/GeoLite2-City.mmdb');
        $reader = new Reader($path);

        $record = $reader->city($ip_user);


        return $record;


        // print($record->country->name . "\n"); // 'United States'
        // print($record->country->names['zh-CN'] . "\n"); // '美国'

        // print($record->mostSpecificSubdivision->name . "\n"); // 'Minnesota'
        // print($record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

        // print($record->city->name . "\n"); // 'Minneapolis'

        // print($record->postal->code . "\n"); // '55455'

        // print($record->location->latitude . "\n"); // 44.9733
        // print($record->location->longitude . "\n"); // -93.2323

        // print($record->traits->network . "\n"); // '128.101.101.101/32'
    }
}
