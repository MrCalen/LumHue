<?php

namespace App\Helpers\MeetHueModel;

use App\Models\Light;

class MeetHue
{
  private $base_uri = "https://www.meethue.com/api";
  private $user_token = "328f4f1a291e01cd550e24950d02d4e";

  private function curlGetHelper($action, $token)
  {
    $base_uri = $this->base_uri . '/' . $action . '?token=' . $token;
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

  private function createClipMessage(Light $light)
  {
    $clipmessage = [
      'clipCommand' => [
        'url'    => '/api/' . $this->user_token . '/lights/' . $light->getId() . '/state',
        'method' => "PUT",
        'body'   => $light->toArray(),
      ],
    ];

    return [
      'clipmessage' => urlencode(json_encode($clipmessage)),
    ];
  }

  private function curlPostHelper($action, Light $light, $token)
  {
    $url = 'https://www.meethue.com/api/' . $action . '?token=' . $token;
    $fields = $this->createClipMessage($light);

    $fields_string = "";
    foreach ($fields as $key => $value)
      $fields_string .= $key . '=' . $value . '&';
    rtrim($fields_string, '&');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
  }

  public function getBridge($token)
  {
    return $this->curlGetHelper('getbridge', $token);
  }

  public function applyLightStatus(Light $light, $token)
  {
//    $token = $token->__toString();
    return $this->curlPostHelper('sendmessage', $light, $token);
  }

}
