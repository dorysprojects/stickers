<?php

session_start();
$LineID = '@ypm8594w';
$client_id = '1577298076';
$client_secret = '11c361890297a9c1ca3a1e5b9c0d3c58';
$redirect_uri = 'https://rickytest.gadclubs.com/stickers/callback.php';

$Request['grant_type'] = 'authorization_code';
$Request['code'] = $_GET['code'];
$Request['redirect_uri'] = $redirect_uri;
$Request['client_id'] = $client_id;
$Request['client_secret'] = $client_secret;

/*
 * token
 */
$RequestData = '';
foreach ($Request as $key => $value) {
        $RequestData .= $key . '=' . $value . '&';
}
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.line.me/oauth2/v2.1/token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
    ),
    CURLOPT_POSTFIELDS => $RequestData,
));
$response_json = curl_exec($curl);
$response_json = json_decode($response_json, true);
$err = curl_error($curl);
curl_close($curl);

/*
 * profile
 */
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.line.me/v2/profile',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer " . $response_json['access_token'],
    )
));
$response_json = curl_exec($curl);
$response_json = json_decode($response_json, true);
$err = curl_error($curl);
curl_close($curl);

$redirect = $_SESSION['redirect'] . '?';
$data = json_decode($_SESSION['data'], true);
foreach ($response_json as $key => $value) {
        $redirect .= $key . '=' . $value . '&';
}
foreach ($data as $key => $value) {
        $redirect .= $key . '=' . $value . '&';
}

header("Location:" . substr($redirect, 0, -1));

?>