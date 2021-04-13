<?php

require(dirname(__FILE__) . '/../config/Connection.php');

abstract class BaseFactory
{
    protected $table;
    protected $table_id;
    protected $pdo;

    public function __construct() {
        $connection = new Connection();
        $this->pdo = $connection->getConnection();
    }

    public function errorArray($error_message) {
        return array(
            "error_message" => $error_message,
            $this->table_id => 0
        );
    }

    public function _hasValue($var) {
        return !is_null($var) && $var != "" && $var;
    }

    public function _getValue($data, $key) {
        return (array_key_exists($key, $data)) ? trim($data[$key]) : "";
    }

    public function _hasError($values) {
        return array_key_exists("error_message", $values);
    }

    public function _isPositive($var) {
        return $this->_hasValue($var) && $var != 0;
    }

    public function _isNegative($var) {
        return !$this->_isPositive($var);
    }

    public function _noError($values) {
        return !array_key_exists("error_message", $values);
    }

    protected function getByID($id) {
        $stmt = $this->pdo->prepare("select * from {$this->table} where {$this->table_id} = ?");
        $stmt->execute(array($id));
        $dev = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->_hasValue($dev)) {
            return $dev;
        } else {
            return null;
        }
    }

    protected function str_contains($string, $substring){
        if (strpos($string, $substring) !== false) {
            return true;
        } else {
            return false;
        }
    }

}