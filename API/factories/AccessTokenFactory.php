<?php

require("BaseFactory.php");

class AccessTokenFactory extends BaseFactory
{
    // Convert from authorization token to access token
    public function convertToken($auth_token, $client_secret) {

    }

    // Make sure it exists and isn't too old
    protected function _validateToken($auth_token, $client_secret) {

    }

}