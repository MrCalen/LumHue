<?php

namespace App\QueryBuilder\NodeHue;

use app\Models\HueLight;

class NodeHue
{
    private $base_uri = "http://localhost:3000";

    private function curlGetHelper()
    {
        $base_uri = $this->base_uri . '/getBridge';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $base_uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function getBridgeStatus()
    {
        return $this->curlGetHelper();
    }

    public function applyLightStatus(HueLight $light)
    {
        $fields = "";
        $arr = $light->toArray();
        foreach ($arr as $key => $value) {
            $fields .= $key . '=' . $value . '&';
        }
        $fields = rtrim($fields, '&');

        $base_uri = $this->base_uri . '/setLight';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $base_uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, count($arr));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        curl_exec($ch);
        curl_close($ch);
    }
}
