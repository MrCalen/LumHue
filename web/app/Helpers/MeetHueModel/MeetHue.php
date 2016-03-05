<?php

namespace App\Helpers\MeetHueModel;

use App\Models\Light;

class MeetHue
{
    private $base_uri = "https://www.meethue.com/api";
    private $access_token = "WGF4TXNzVUtJWXRrVGFSQXhlcWNrenhobk16UkIvRGgwNDJ6RmJydVhsWT0%3D";

    private function curlGetHelper($action)
    {
        $base_uri = $this->base_uri . '/' . $action . '?token=' . $this->access_token;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $base_uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private function curlPostHelper()
    {
    }

    public function getBridge()
    {
        return $this->curlGetHelper('getbridge');
    }

    public function applyLightStatus(Light $light)
    {

    }

}