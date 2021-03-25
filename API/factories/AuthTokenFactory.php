<?php

require("BaseFactory.php");
class AuthTokenFactory extends BaseFactory {
    public function __construct() {
        parent::__construct();
        $this->table = "authtoken";
        $this->table_id = "authtoken_id";
    }

}