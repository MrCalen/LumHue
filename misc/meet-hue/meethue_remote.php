<?php


$R = 200;
$B = 255;
$G = 200;

$bri = 255;

$X = 0.4124*$R + 0.3576*$G + 0.1805*$B;
$Y = 0.2126*$R + 0.7152*$G + 0.0722*$B;
$Z = 0.0193*$R + 0.1192*$G + 0.9505*$B;

$x = $X / ($X + $Y + $Z);
$y = $Y / ($X + $Y + $Z);


#$url = 'https://client-eastwood-dot-hue-prod-us.appspot.com/api/sendmessage?token=WGF4TXNzVUtJWXRrVGFSQXhlcWNrenhobk16UkIvRGgwNDJ6RmJydVhsWT0%3D';
$url = 'https://www.meethue.com/api/sendmessage?token=WGF4TXNzVUtJWXRrVGFSQXhlcWNrenhobk16UkIvRGgwNDJ6RmJydVhsWT0%3D';
$clipmessage = [
    'clipCommand' =>
    [
        'url' => "/api/328f4f1a291e01cd550e24950d02d4e/lights/2/state",
        'method' => "PUT",
        'body' => [
            "on" => true,
            "alert" => "none",
            "bri" => $bri,
            "xy" => [
                $x,$y            ],
        ],
    ]
];



$fields = array(
    'clipmessage' => urlencode(json_encode($clipmessage)),
);
$fields_string = "";
//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

return;