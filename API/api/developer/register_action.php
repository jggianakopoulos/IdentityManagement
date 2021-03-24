<?php

require("../../factories/DeveloperFactory.php");

session_start();
$df = new DeveloperFactory();

$dev = $df->considerRegistration($_REQUEST);

if ($df->_noError($dev)) {
    session_start();
    $_SESSION["email"] = $dev["email"];
    $_SESSION["password"] = $dev["password"];
    $_SESSION["company"] = $dev["company"];
    header("Location: home.php");
} else {
//    echo $dev["error_message"];
    $error_message = $dev["error_message"];
    header("Location: logout.php");
}

