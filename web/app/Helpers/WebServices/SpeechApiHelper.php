<?php

namespace App\Helpers\WebServices;

class SpeechApiHelper
{
    public static function IssueToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://oxford-speech.cloudapp.net/token/issueToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=client%20credentials&client_id=lumhue&client_secret=" . env('BINGTOKEN') . "&scope=https%3A%2F%2Fspeech.platform.bing.com",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $json = json_decode($response);
        return $json->access_token;
    }

    public static function SendBinary($binaryData)
    {
        $token = self::IssueToken();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://speech.platform.bing.com/recognize?".
                    "appid=D4D52672-91D7-4C74-8AD8-42B1D98141A5".
                    "&locale=fr-FR".
                    "&version=3.0".
                    "&format=json".
                    "&requestid=b2c95ede-97eb-4c88-81e4-80f32d6aef74".
                    "&instanceid=106a4690-b664-ca61-addb-cdc705560791".
                    "&device.os=osx".
                    "&scenarios=ulm".
                    "&result.profanitymarkup=0",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $token,
                "cache-control: no-cache",
                "content-type: audio/wav; samplerate=16000",
            ),
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $binaryData);
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return json_decode($response);
    }
}
