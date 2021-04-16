<?php

require_once("UserFactory.php");

class FaceFactory extends BaseFactory {
    public function __construct() {
        parent::__construct();
        $this->table = "face";
        $this->table_id = "face_id";
    }

    public function considerCompareFaces($data) {
        $user = $this->_validateCompareFaces($data);

        if ($this->_hasError($user)) {
            return $user;
        }

        return $this->compareFaces($user, $data["image"]);
    }

    protected function _validateCompareFaces($data) {
        $valid_data = $this->_validateImage($data);
        if ($this->_hasError($valid_data)) {
            return $valid_data;
        }
        if ($this->_hasValue($email = $this->_getValue($data, "email"))) {
            $uf = new UserFactory();
            return $uf->getFaceStatus($email);
        } else {
            return $this->errorArray("Must include a valid email");
        }

    }

    protected function compareFaces($user, $image) {
        $userdatapath = "/var/www/idm/API/assets/userdata/";
        $userfacepath = $userdatapath . "faces/";
//        $face = $this->getUserFace($user["user_id"]);
//
//        if ($this->_hasError($face)) {
//            return $face;
//        }

        $new_face = $this->storeImage($this->base64toImage($image), $user["user_id"]);

        if ($new_face == "") {
            return $this->errorArray("Error with new image");
        }
        $result = $this->faceRecognition($userfacepath . $user["user_id"], $new_face);

        if ($result) {
            return $user;
        } else {
            return $this->errorArray("Different faces");
        }
    }

    protected function storeImage($image, $user) {
        $userfacepath = "/var/www/idm/API/assets/userdata/faces/";
        date_default_timezone_set('UTC');
        $path = $userfacepath . $user["user_id"]."/Attempts/";
        $name = date("Ymdhis") . '.png';

        if (file_put_contents($path . $name, $image)) {
            return $path . $name;
        } else {
            return "";
        }
    }

    protected function faceRecognition($compare_to, $new_face) {
        $facerecognitionpath = "/home/steverobertscott/.virtualenvs/dlib/bin/face_recognition";
        $command = escapeshellcmd($facerecognitionpath . " " . $compare_to . " " . $new_face);
        $output = shell_exec($command);

        if ($this->str_contains($output, 'unknown_person') || $this->str_contains($output, 'no_persons_found'))
        {
            return false;
        }
        else {
            return true;
        }
    }

    protected function str_contains($string, $substring){
        if (strpos($string, $substring) !== false) {
            return true;
        }
        else {
            return false;
        }
    }

    protected function base64toImage($base64) {
        $img = str_replace('data:image/png;base64,', '', $base64);
        $img = str_replace(' ', '+', $img);
        return base64_decode($img);
    }

    public function considerUpdate($data) {
        $values = $this->_validateUpdate($data);

        if ($this->_hasError($values)) {
            return $values;
        }

        return $this->updateFace($values, $data["image"]);
    }

    protected function _validateUpdate($data) {
        $valid_data = $this->_validateImage($data);
        if ($this->_hasError($valid_data)) {
            return $valid_data;
        }
        $uf = new UserFactory();
        $user = $uf->considerLogin($data);

        if (!array_key_exists("user_id", $user) || !($user["user_id"] > 0)) {
            return $this->errorArray("Invalid user");
        } else {
            return $user;
        }
    }

    protected function _validateImage($data) {
        if (!array_key_exists("image", $data) || !is_string($data["image"])) {
            return $this->errorArray("Invalid image uploaded");
        } else {
            return $data;
        }
    }

    protected function updateFace($user, $data) {
        $image = $this->base64toImage($data);

        if (!$image) {
            return $this->errorArray("Invalid image uploaded");
        }
        $path = '../../assets/userdata' . $user["user_id"];

        $dir = true;
        if (!file_exists($path)) {
            $dir = mkdir($path);
        }

        if ($dir && file_put_contents($path . "/face.png", $image)) {
            $this->trackFile($user["user_id"], $path);
            return $user;
        } else {
            return $this->errorArray("Error adding file");
        }
    }

    protected function trackFile($user_id, $path) {
        $stmt = $this->pdo->prepare("insert into {$this->table} (user_id, path) values (?,?)");
        $stmt->execute(array($user_id, $path));
    }

    protected function getUserFace($user_id) {
        $stmt = $this->pdo->prepare("select * from {$this->table} where user_id = ? order by created desc");
        $stmt->execute(array($user_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->_hasValue($result)) {
            if (file_exists($result["path"])) {
                return $result;
            } else {
                return $this->errorArray("Try reuploading your face from the app");
            }
        } else {
            return $this->errorArray("You never scanned your face");
        }
    }

//    protected function getUserFaceFile($user_id) {
//        $face = $this->getUserFace($user_id);
//
//        if ($this->_hasError($face)) {
//            return $face;
//        }
//
//        $image = file_get_contents($face["path"]);
//
//        if ($this->_hasValue($image)) {
//            return $image;
//        } else {
//            return $this->errorArray("This file does not exist");
//        }
//    }
}