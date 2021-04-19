<?php
$client_secret = "eacf1535c5ca11e8d59189c08688596a";
$token = "98ff9e15d2b23d713f354ece20e239be";

$ch = curl_init ( 'localhost/identitymanagement/api/developer/convert_token.php' );
curl_setopt_array ( $ch, array (
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array (
        'authtoken' => $token,
        'client_secret' => $client_secret
    ),
    CURLOPT_RETURNTRANSFER => 1
) );

$data = curl_exec($ch);
