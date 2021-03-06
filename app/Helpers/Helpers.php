<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Helpers {
    public static function callNhanhApi($dataArray, $uri, $isFixed = false) {
        $dataString = !$isFixed ? json_encode($dataArray) : $dataArray;
        $checksum = md5(md5(config('app.nhanh_api_secret_key') . $dataString) . $dataString);
        $postArray = [
            "version" => "1.0",
            "apiUsername" => config('app.nhanh_api_user_name'),
            "data" => $dataString,
            "checksum" => $checksum
        ];
        $curl = curl_init(config('app.nhanh_api_host') . $uri);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $curlResult = curl_exec($curl);
        curl_close($curl);
        
        // return json_decode($curlResult)->code == 0 ? json_decode($curlResult) : json_decode($curlResult)->data;
        if(isset(json_decode($curlResult)->data)) return json_decode($curlResult)->data;
        
        return json_decode($curlResult);
        
    }
}