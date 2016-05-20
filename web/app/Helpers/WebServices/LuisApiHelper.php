<?php

namespace App\Helpers\WebServices;

use App\Helpers\WebServices\Luis\LuisIntent;

class LuisApiHelper
{
    private static function IssueCall($query)
    {
        $curl = curl_init();
        $uri = "https://api.projectoxford.ai/luis/v1/application?"
            . "id=" . env('LUIS_APPID')
            . "&subscription-key=" . env("LUIS_SUBSCRIPTION")
            . "&q=" . urlencode($query);
        curl_setopt_array($curl, array (
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array (
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public static function GetIntent($message, $meethue_token)
    {
        $response = self::IssueCall($message);
        $intent = $response->intents[0];
        $confidence = $intent->score * 100;
        if ($confidence < 50)
            return [
                'success' => false,
                'message' => "Je ne suis pas certain de l'action à appliquer",
                'confidence' => $confidence,
            ];
        $intent = LuisIntent::ApplyIntent($intent, $meethue_token);
        if ($intent === null)
            return [
                'success' => false,
                'message' => "Je n'ai pas compris votre message",
                'confidence' => $confidence,
            ];

        return [
            'success' => true,
            'message' => "C'est fait :)",
            'confidence' => $confidence,
        ];
    }
}
