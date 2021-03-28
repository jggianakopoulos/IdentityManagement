<?php

require_once("AccountFactory.php");

class DeveloperFactory extends AccountFactory {

    public function __construct() {
        parent::__construct();
        $this->table = "developer";
        $this->table_id = "developer_id";
    }

    public function logout() {
        session_start();
        session_unset();
        header("Location: login.php");
    }

    protected function _registerQuery($values) {
        $stmt = $this->pdo->prepare("insert into {$this->table} (email, password, company, client_id, client_secret) values (?,?,?,?,?)");
        $stmt->execute(array($this->_getValue($values, "email"), $this->_getValue($values, "password"),$this->_getValue($values, "company"), md5(uniqid()), md5(uniqid())));
    }

    public function validateClientID($client_id) {
        $stmt = $this->pdo->prepare("select * from {$this->table} where client_id = ?");
        $stmt->execute(array($client_id));
        $dev = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->_hasValue($dev)) {
            return true;
        } else {
            return false;
        }
    }
}