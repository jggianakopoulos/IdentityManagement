<?php

require("BaseObject.php");

class Developer extends BaseObject {

    public function __construct() {
        parent::__construct();
    }

    public function fields() {
        return array( "email", "password", "company");
    }

}