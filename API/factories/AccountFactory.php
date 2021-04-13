<?php

require_once("BaseFactory.php");

// This factory is extended by all entities that have login/register/update profile functionality

abstract class AccountFactory extends BaseFactory {

    abstract protected function _registerQuery($values);

    public function __construct() {
        parent::__construct();
    }

    public function considerLogin($data) {
        list($username, $password) = $this->_validateLogin($data);

        if (is_null($username)) {
            return $this->errorArray("You must enter an email and password");
        }

        return $this->login($username, $password);
    }

    protected function login($email, $password) {
        $stmt = $this->pdo->prepare("select * from {$this->table} where email = ? and password = ?");
        $stmt->execute(array($email, $password));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->_hasValue($result)) {
            return $result;
        } else {
            return $this->errorArray("A user with these credentials doesn't exist");
        }
    }

    protected function _validateLogin($data) {
        $email = $this->_getValue($data, "email");
        $password =$this->_getValue($data, "password");

        if ($this->_hasValue($email) && $this->_hasValue($password)) {
            return array($email, $password);
        } else {
            return array(null, null);
        }
    }

    protected function getByEmail($email) {
        $stmt = $this->pdo->prepare("select * from {$this->table} where email = ?");
        $stmt->execute(array($email));
        $dev = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->_hasValue($dev)) {
            return $dev;
        } else {
            return null;
        }
    }

    public function considerRegistration($data) {
        $values = $this->_validateRegistration($data);

        if ($this->_noError($values)) {
            return $this->register($values);
        } else {
            return $this->errorArray($values["error_message"]);
        }
    }

    protected function _validateRegistration($data) {
        $email = $this->_getValue($data, "email");
        $password = $this->_getValue($data, "password");
        $confirm_password = $this->_getValue($data, "confirm_password");

        if ($this->_hasValue($email) && $this->_hasValue($password) && $this->_hasValue($confirm_password) && $password == $confirm_password) {
            $existing = $this->getByEmail($email);

            if (!is_null($existing)) {
                return $this->errorArray("A user with this email already exists");
            } else {
                return array(
                    "email" => $email,
                    "password" => $password,
                    "first_name" => $this->_getValue($data, "first_name"),
                    "last_name" => $this->_getValue($data, "last_name"),
                    "company" => $this->_getValue($data, "company")
                );
            }
        } else {
            return $this->errorArray("You must enter an email and confirm your password.");
        }

    }

    protected function register($values) {
        $this->_registerQuery($values);
        $account = $this->getByEmail($this->_getValue($values, "email"));

        if ($this->_hasValue($account)) {
            return $account;
        } else {
            return $this->errorArray("There was an error with your registration"
            );
        }
    }

}