<?php

$curl = curl_init();

$request = '{
    "name" : "generateToken",
    "param" : {
        "email" : "ssewankamboderick@gmail.com",
        "pass" : "admin123"
    }
}';

curl_setopt($curl, CURLOPT_URL, 'http://localhost/phprest/jwt-apis/');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['content-type: application/json']);
curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($curl);
$err = curl_error($curl);

if($err){
    echo 'Curl Error: ' . $err;
}
else{
    //incase u want to pass the token in json format 
    // header('content-type: application/json');

    // print_r($result);

    // incase u want to decode from json
    $response = json_decode($result, true);
    // print_r($response);

    //if u want to access the token
    $token = $response['response']['result']['token'];
    curl_close($curl);
    
$curl = curl_init();


/* call second api*/
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost/phprest/jwt-apis/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "name" : "getCustomerDetails",
    "param" : {
        "customerId" : 1
    }
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '. $token,  // add token here
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;



}

 ?>