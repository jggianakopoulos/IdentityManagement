<?php

require("BaseFactory.php");
class AuthTokenFactory extends BaseFactory {
    public function __construct() {
        parent::__construct();
        $this->table = "developer";
        $this->table_id = "DeveloperID";
    }

}