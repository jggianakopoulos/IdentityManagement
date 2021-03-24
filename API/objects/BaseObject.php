<?php

require("../config/Connection.php");

abstract class BaseObject {
    protected $pdo;
    abstract protected function fields();

    public function __construct($data) {
        foreach($this->fields() as $f) {
            if (array_key_exists($f, $data)) {
                $this->$f = $data[$f];
            }
        }

        $this->pdo = new Connection;
    }

}