<?php

require_once("BaseFactory.php");
require_once("UserFactory.php");

class LoginCodeFactory extends BaseFactory {
    public function __construct() {
        parent::__construct();
        $this->table = "logincode";
        $this->table_id = "logincode_id";
    }

    public function loginCodeExists($data) {
        $email = $this->_getValue($data, "email");
        $code = $this->_getValue($data, "code");

        if ($this->_hasValue($email) && $this->_hasValue($code)) {
            return $this->checkCodeExists($email, $code);
        } else {
            return $this->errorArray("You must enter a valid email and code");
        }
    }

    protected function checkCodeExists($email, $code) {
        $stmt = $this->pdo->prepare("select * from logincode join user using (user_id) where user.email = ? and logincode.code = ?");
        $stmt->execute(array($email, $code));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$this->_hasValue($result)) {
            return $this->errorArray("The code you entered is invalid.");
        } else {
            return $result;
        }
    }

    public function generateLoginCode($data) {
        $email = $this->_getValue($data, "email");

        if ($this->_hasValue($email)) {
            $uf = new UserFactory();
            $user = $uf->getByEmail($email);

            if (!is_null($user)) {
                return $this->createCode($user["user_id"]);
            } else {
                return $this->errorArray("Invalid email entered");
            }
        } else {
            return $this->errorArray("The email you entered is invalid");
        }

    }

    protected function createCode($user_id) {
        $code = md5(uniqid());
        $stmt = $this->pdo->prepare("insert into logincode (user_id, code) values (?,?)");
        $stmt->execute(array($user_id, $code));

        $stmt = $this->pdo->prepare("select * from code where code = ?");
        $stmt->execute(array($code));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->_hasValue($result)) {
            return $result;
        } else {
            return $this->errorArray("Error creating code");
        }
    }


}