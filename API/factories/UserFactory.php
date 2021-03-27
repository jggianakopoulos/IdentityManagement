<?php

require("AccountFactory.php");

class UserFactory extends AccountFactory {
    public function __construct() {
        parent::__construct();
        $this->table = "user";
        $this->table_id = "user_id";
    }

    protected function _registerQuery($values) {
        $stmt = $this->pdo->prepare("insert into {$this->table} (email, password, first_name, last_name) values (?,?,?,?)");
        $stmt->execute(array($this->_getValue($values, "email"), $this->_getValue($values, "password"),$this->_getValue($values, "first_name"), $this->_getValue($values, "last_name")));
    }

    public function considerPasswordChange($data) {
        $values = $this->_validatePasswordChange($data);

        if (!$this->_noError($values)) {
            return $values;
        } else {
            return $this->passwordChange($values);
        }
    }

    protected function passwordChange($values) {
        $stmt = $this->pdo->prepare("update user set password = ? where user_id = ?");
        $stmt->execute(array($this->_getValue($values, "password"), $this->_getValue($values, "user_id")));

        $user = $this->getByID($this->_getValue($values, "user_id"));

        if (is_null($user)) {
            return $this->errorArray("Invalid credentials");
        } else {
            return $user;
        }

    }

    protected function _validatePasswordChange($data) {
        $email = $this->_getValue($data, "email");
        $password = $this->_getValue($data, "password");
        $confirm_password = $this->_getValue($data, "confirm_password");
        $new_password = $this->_getValue($data, "new_password");
        if ($this->_hasValue($email) && $this->_hasValue($password) && $this->_hasValue($confirm_password) && $this->_hasValue($new_password) && $confirm_password == $new_password) {
            $user = $this->login($email, $password);
            if ($this->_noError($user)) {
                return array(
                    "user_id" => $user["user_id"],
                    "password" => $new_password
                );
            } else {
                return $user;
            }
        } else {
            return $this->errorArray("You must enter valid passwords");
        }
    }

    public function considerProfileUpdate($data) {
        $values = $this->_validateProfileUpdate($data);

        if (!$this->_noError($values)) {
            return $values;
        } else {
            return $this->profileUpdate($values);
        }
    }

    protected function profileUpdate($values) {
        $stmt = $this->pdo->prepare("update user set email = ?, first_name = ?, last_name = ? where user_id = ?");
        $stmt->execute(array($this->_getValue($values, "email"), $this->_getValue($values, "first_name"), $this->_getValue($values, "last_name"), $this->_getValue($values, "user_id")));

        if ($user = $this->getByEmail($this->_getValue($values, "email"))) {
            return $user;
        } else {
            return $this->errorArray("An error occurred while updating your profile");
        }
    }

    protected function _validateProfileUpdate($data) {
        $email = $this->_getValue($data, "old_email");
        $new_email = $this->_getValue($data, "email");
        $first_name = $this->_getValue($data, "first_name");
        $last_name = $this->_getValue($data, "last_name");
        $password = $this->_getValue($data, "password");

        if ($this->_hasValue($email) && $this->_hasValue($new_email) && $this->_hasValue($password)) {
            if ($this->_hasValue($user = $this->login($email, $password))) {
                if ($email != $new_email && $this->_hasValue($this->getByEmail($new_email))) {
                    return $this->errorArray("The new email you want to use is already taken");
                } else {
                    return array(
                        "email" => $new_email,
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "user_id" => $user["user_id"]
                    );
                }
            } else {
                return $this->errorArray("Invalid credentials");
            }
        } else {
            return $this->errorArray("You must fill in all required fields");
        }

    }

}