<?php

require("BaseObject.php");

class User extends BaseObject {

    public function __construct()
    {
        parent::__construct();
    }

    public function getPermissions() {

    }

    public function fields() {
        return array(
          "email", "password"
        );
    }

}