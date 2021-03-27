<?php

require("BaseFactory.php");
require("UserFactory.php");
require("FaceFactory.php");

class TokenFactory extends BaseFactory
{
    public function __construct() {
        parent::__construct();
        $this->table = "token";
        $this->table_id = "token_id";
    }

    public function attemptCreateToken($data) {
        $user = $this->_validateCreateToken($data);

        return $this->createToken($user);
    }

    protected function _validateCreateToken($data) {
        $f = new FaceFactory();
        //$f->considerCompareFaces($user, $data["image"]);
    }

    protected function createToken($user) {
        $stmt = $this->pdo->prepare("insert into token (user_id, token, type) values (?,?,'auth')");
        $stmt->execute(array($user["user_id"], md5(uniqid())));
    }

    // Convert from authorization token to access token
    public function attemptRetrieveAccessToken($data) {
        $token = $this->_validateTokenData($data, "auth");

        if (!$this->_noError($token)) {
            return $token;
        }

        return $this->convertToken($token);
    }

    // Make sure it exists, isn't too old, and has a valid secret
    protected function _validateTokenData($data, $type) {
        $authtoken = $this->_getValue($data, $type . "token");
        $secret = $this->_getValue($data, "client_secret");

        if (!$this->_hasValue($authtoken) || !$this->_hasValue($secret)) {
            return $this->errorArray("You must send an {$type}token and your client secret.");
        }

        return $this->getToken($authtoken, $secret, $type);
    }

    protected function getToken($authtoken, $secret, $type) {
        $stmt = $this->pdo->prepare("select token.* from token join developer using (developer_id) where token.token = ? and token.type = ? and developer.client_secret = ?");
        $stmt->execute(array($authtoken, $type, $secret));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->_hasValue($result)) {
            return $result;
        } else {
            return $this->errorArray("Invalid credentials");
        }
    }

    protected function convertToken($token) {
        $stmt = $this->pdo->prepare("update token set token = ?, type='access' where token_id = ?");
        $stmt->execute(array(md5(uniqid()), $token["token_id"]));

        $token = $this->getByID($token["token_id"]);

        if (is_null($token)) {
            return $this->errorArray("Invalid token");
        } else {
            return $token["token"];
        }
    }

    public function attemptGetUserData($data) {
        $token = $this->_validateTokenData($data, "access");

        if (!$this->_noError($token)) {
            return $token;
        }

        return $this->getUserData($token);
    }

    protected function getUserData($token) {
        $f = new UserFactory();
        $user = $f->getByID($token["user_id"]);
        if (is_null($user)) {
            return $this->errorArray("Invalid user");
        }

        $values = array(
            "user_id" => $user["user_id"]
        );

        if ($token["permission_email"] > 0) {
            $values["email"] = $user["email"];
        }
        if ($token["permission_firstname"] > 0) {
            $values["first_name"] = $user["first_name"];
        }
        if ($token["permission_lastname"] > 0) {
            $values["last_name"] = $user["last_name"];
        }

        return $values;
    }

}