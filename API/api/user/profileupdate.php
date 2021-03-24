<?php

try {

    require("../../factories/UserFactory.php");

    $uf = new UserFactory();
    echo json_encode($uf->considerProfileUpdate($_REQUEST));

} catch (Exception $e) {
    echo json_encode(array(
        "error_message" => "There was an error with your profile update."
    ));
}