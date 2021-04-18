<?php

require_once("UserFactory.php");
require_once("FaceFactory.php");

class TokenFactory extends BaseFactory
{
    public function __construct() {
        parent::__construct();
        $this->table = "token";
        $this->table_id = "token_id";
    }

    public function getDataFromToken($data) {
        $token = $this->_getValue($data, "token");
        $secret = $this->_getValue($data, "client_secret");
        return $this->getTokenUser($token, $secret, "auth");
    }
    protected function getTokenUser($authtoken, $secret, $type) {
        $stmt = $this->pdo->prepare("select token.*, user.* from token join developer using (developer_id) join user using (user_id) where token.token = ? and token.type = ? and developer.client_secret = ?");
        $stmt->execute(array($authtoken, $type, $secret));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($this->_hasValue($result)) {
            return $result;
        } else {
            return $this->errorArray("Invalid credentials");
        }
    }

    // Validate, extract values, and create the token
    public function attemptCreateToken($user, $data) {
        $valid = $this->_validateCreateToken($user, $data);
        $permissions = $this->_getValue($data, "permissions");
        $developer_id = $this->_getDevIDByClientID($data["client_id"]);

        if (!$valid) {
            return $this->errorArray("Invalid user");
        }

        if (is_array($developer_id) && $this->_hasError($developer_id)) {
            return $developer_id;
        }

        if ($this->_hasValue($permissions)) {
            $permissions = explode(",", $permissions);

            $user["permission_email"] = (in_array("email", $permissions)) ? 1 : 0;
            $user["permission_firstname"] = (in_array("firstname", $permissions)) ? 1 : 0;
            $user["permission_lastname"] = (in_array("lastname", $permissions)) ? 1 : 0;
        } else {
            $user["permission_email"] = 0;
            $user["permission_firstname"] = 0;
            $user["permission_lastname"] = 0;
        }
        return $this->createToken($user, $developer_id);
    }


    protected function _validateCreateToken($user, $data) {
        $developer = $this->_getValue($data, "client_id");
        if (!$this->_hasValue($developer)) {
            return $this->errorArray("Invalid company sign in");
        }

        $f = new UserFactory();
        return $f->userIDExists($user["user_id"]);
    }

    // Create a new auth token
    protected function createToken($user, $developer_id) {
        $token = md5(uniqid());
        $stmt = $this->pdo->prepare("insert into token (user_id, developer_id, permission_email, permission_firstname, permission_lastname, token, type) values (?,?,?,?,?,?,'auth')");
        $stmt->execute(array($user["user_id"], $developer_id, $user["permission_email"], $user["permission_firstname"], $user["permission_lastname"], $token));

        $stmt = $this->pdo->prepare("select * from token where token = ?");
        $stmt->execute(array($token));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->_hasValue($result)) {
            return $result;
        } else {
            return $this->errorArray("Error creating token");
        }
    }

    protected function _getDevIDByClientID($client_id) {
        $stmt = $this->pdo->prepare("select developer_id from developer where client_id = ?");
        $stmt->execute(array($client_id));
        $result = $stmt->fetch(PDO::FETCH_COLUMN);

        if ($this->_hasValue($result)) {
            return $result;
        } else {
            return $this->errorArray("Invalid company sign-in");
        }
    }

    // Convert from authorization token to access token
    public function retrieveConvertedToken($data) {
        $token = $this->_validateTokenData($data, "auth");

        if ($this->_hasError($token)) {
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
            return $this->errorArray("A token with these credentials does not exist. Confirm your client secret and make sure the auth token hasn't already been converted.");
        }
    }

    // Convert an auth token to an access token
    protected function convertToken($token) {
        $stmt = $this->pdo->prepare("update token set token = ?, type='access' where token_id = ?");
        $stmt->execute(array(md5(uniqid()), $token["token_id"]));

        $token = $this->getByID($token["token_id"]);

        if (is_null($token)) {
            return $this->errorArray("Invalid token");
        } else {
            return $token;
        }
    }


    // Convert auth token and get new access token, refresh token, and user data
    public function attemptGetTokenData($data) {
        $token = $this->_validateTokenData($data, "auth");

        if ($this->_hasError($token)) {
            return $token;
        }

        $userdata =  $this->getUserData($token);

        $tf = new TokenFactory();
        $access_token = $tf->retrieveConvertedToken($data);

        if ($this->_hasError($access_token)) {
            return $this->errorArray("There was a problem with your token");
        }

        $refresh_token = $this->createRefreshToken($access_token["token_id"]);

        if ($this->_hasError($refresh_token)) {
            return $this->errorArray("Your token could not be converted.");
        }

        return array_merge($userdata, array("access_token" => $access_token["token"], "refresh_token" => $refresh_token["token"]));
    }

    // Create a refresh token, which is used to extend the expiration of an existing access token
    protected function createRefreshToken($access_token_id) {
        $token = md5(uniqid());
        $stmt = $this->pdo->prepare("insert into refreshtoken (token_id, token) values (?,?)");
        $stmt->execute(array($access_token_id, $token));

        $stmt = $this->pdo->prepare("select * from refreshtoken where token = ?");
        $stmt->execute(array($token));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->_hasValue($result)) {
            return $result;
        } else {
            return $this->errorArray("Error creating token");
        }
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
