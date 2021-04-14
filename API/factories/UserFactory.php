<?php

require_once("AccountFactory.php");
require_once("FaceFactory.php");

class UserFactory extends AccountFactory {
    private $ff;
    public function __construct() {
        parent::__construct();
        $this->table = "user";
        $this->table_id = "user_id";
        $this->ff = new FaceFactory();
    }

    public function considerSignInMethods($data) {
        $settings = $this->_validateMethods($data);

        if ($this->_hasError($settings)) {
            return $settings;
        }

        $user = $this->considerLogin($data);

        if ($this->_noError($user)) {
            return $this->_updateSignInMethods($user["user_id"], $settings);
        } else {
            return $user;
        }
    }

    protected function _updateSignInMethods($user_id, $settings) {
        $stmt = $this->pdo->prepare("update user set use_password = ?, use_face = ?, use_code = ? where user_id = ?");
        $stmt->execute(array($this->_getValue($settings, "use_password"),$this->_getValue($settings, "use_face"),$this->_getValue($settings, "use_code"), $user_id));

        $user = $this->getByID($user_id);

        if (is_null($user)) {
            return $this->errorArray("Invalid credentials");
        } else {
            return $user;
        }
    }

    protected function _validateMethods($data) {
        $use_password = $this->_getValue($data, "use_password");
        $use_face = $this->_getValue($data, "use_face");
        $use_code = $this->_getValue($data, "use_code");

        if ($this->_isNegative($use_password) && $this->_isNegative($use_face) && $this->_isNegative($use_code)) {
            return $this->errorArray("You must enable at least one sign in method");
        } else {
            return array(
                "use_password" => ($use_password == 1) ? $use_password : 0,
                "use_face" => ($use_face == 1) ? $use_face : 0,
                "use_code" => ($use_code == 1) ? $use_code : 0,
            );
        }
    }

    protected function _registerQuery($values) {
        $stmt = $this->pdo->prepare("insert into {$this->table} (email, password, first_name, last_name) values (?,?,?,?)");
        $stmt->execute(array($this->_getValue($values, "email"), $this->_getValue($values, "password"),$this->_getValue($values, "first_name"), $this->_getValue($values, "last_name")));
    }

    public function considerPasswordChange($data) {
        $values = $this->_validatePasswordChange($data);

        if ($this->_hasError($values)) {
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

        if ($this->_hasError($values)) {
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

    public function emailCheck($data) {
        $email = $this->_getValue($data, "email");

        if ($this->_hasValue($email)) {
            $user = $this->getByEmail($email);

            if (is_null($user)) {
                return $this->errorArray("A user with these credentials doesn't exist");
            } else {
                return $this->assembleReturnValue($user, "email", $data);
            }
        } else {
            return $this->errorArray("You must provide an email");
        }

    }

    public function userIDExists($user_id) {
        $user = $this->getByID($user_id);
        return !is_null($user);
    }

    public function passwordCheck($data) {
        $user = $this->considerLogin($data);

        if ($this->_noError($user)) {
            return $this->assembleReturnValue($user, "password", $data);
        } else {
            return $user;
        }
    }

    public function codeCheck($data) {
        $code = $this->userCodeExists($data);

        if ($this->_noError($code)) {
            return $this->assembleReturnValue($code, "code", $data);
        } else {
            return $code;
        }
    }

    public function faceCheck($data) {
        $user = $this->ff->considerCompareFaces($data);
        if ($this->_noError($user)) {
            $this->assembleReturnValue($user, "face", $data);
        } else {
            return $user;
        }
    }


    protected function assembleReturnValue($user, $source, $data) {
        $use_password = $user["use_password"];
        $use_code = $user["use_code"];
        $use_face = $user['use_face'];

        switch($source) {
            case "email":
                if ($use_password) {
                    $next = "password";
                } else if ($use_code) {
                    $next = "code";
                } else if ($use_face) {
                    $next = "face";
                } else {
                    $next = "password";
                }
            case "password":
                if ($use_code) {
                    $next = "code";
                } else if ($use_face) {
                    $next = "face";
                } else {
                    //gen token;
                }
            case "code":
                if ($use_face) {
                    $next = "face";
                } else {
                    //gen token;
                }
            case "face":
                //gen token
            default:

        }
    }


    public function getFaceStatus($email) {
        $user = $this->getByEmail($email);

        if (is_null($user) || $this->_isNegative($user["face_uploaded"])) {
            return $this->errorArray("Invalid user");
        } else if ($this->_isNegative($user["face_uploaded"])) {
            return $this->errorArray("You must configure facial recognition in the app.");
        } else {
            return $user;
        }
    }

}