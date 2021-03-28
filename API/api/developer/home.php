<?php

require("../../factories/DeveloperFactory.php");

session_start();
$df = new DeveloperFactory();
$error_message = "";

if (is_null($_SESSION) || !array_key_exists("password", $_SESSION) || !array_key_exists("email",  $_SESSION)) {
	$error_message = "Invalid credentials";
} else {
	$password = $_SESSION["password"];
	$email = $_SESSION["email"];

    $dev = $df->considerLogin(array(
        "email" => $email,
        "password" => $password
    ));

    if (!$df->_noError($dev)) {
        $error_message = $dev["error_message"];
    } else {
        $_SESSION["company"] = $dev["company"];
        $_SESSION["client_id"] = $dev["client_id"];
        $_SESSION["client_secret"] = $dev["client_secret"];

        include("home_layout.php");
    }
}

if ($error_message != "") {
    $df->logout();
}

?>