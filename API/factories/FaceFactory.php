<?php

require_once("UserFactory.php");

class FaceFactory extends BaseFactory {
    public function __construct() {
        parent::__construct();
        $this->table = "face";
        $this->table_id = "face_id";
    }

    // Validate and compare a user's face with their sign-in key picture
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

    // Compare the new face with the face currently stored as a key
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

    // Stores an image for comparison
    protected function storeImage($image, $user) {
        $userfacepath = "/var/www/idm/API/assets/userdata/faces/" . $user["user_id"];
        date_default_timezone_set('UTC');
        $path = $userfacepath ."/Attempts/";
        $name = date("Ymdhis") . '.png';

        $dir = true;
            if (!file_exists($userfacepath)) {
                $dir = mkdir($userfacepath);
                $dir = mkdir($userfacepath . "/Attempts");
            }

        if (file_put_contents($path . $name, $image)) {
            return $path . $name;
        } else {
            return "";
        }
    }

    // Face Recognition function. Pass in two image paths and they'll be compared
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

    // Face detection. Assures there is one valid face in the picture located at a specified path.
    protected function faceDetection($path) {
            $facedetectionpath = "/home/steverobertscott/.virtualenvs/dlib/bin/face_detection";
            $command = escapeshellcmd($facedetectionpath . " " . $path);
            $output = shell_exec($command);
            $singleregex = '/^key\.png(,[0-9]{1,4}){4}$/';
            $multiregex = '/key\.png(,[0-9]{1,4}){4}/';

            if (!$this->_hasValue($output))
            {
                return $this->errorArray("No face detected");
            }
            //this regex is more strict so must happen first. A single face will also trip the multi-face regex
            elseif (preg_match($singleregex, $output)) {
                return true;
            }
            elseif (preg_match($multiregex, $output)) {
                return $this->errorArray("Multiple faces detected. Try again.");
            }
            else {
                return $this->errorArray("There was an error. Try again.");
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

    // Validate and update a user's face key.
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

    // The guts of a face update. Validate the image, detect the face, and store it if all requirements are met.
    protected function updateFace($user, $data) {
        $image = $this->base64toImage($data);

        if (!$image) {
            return $this->errorArray("Invalid image uploaded");
        }
        $userfacepath = "/var/www/idm/API/assets/userdata/faces/" . $user["user_id"];

        $dir = true;
        //The user's directory should already exist. In case it doesn't, create it.
        if (!file_exists($userfacepath)) {
            $dir = mkdir($userfacepath);
            $dir = mkdir($userfacepath . "/Attempts");
        }

        if ($dir && file_put_contents($userfacepath . "/key.png", $image)) {
            $output = $this->faceDetection($userfacepath . "/key.png");

            if (!$this->_hasValue($output)){
                $this->userFaceUploaded($user["user_id"]);
                $this->trackFile($user["user_id"], $userfacepath);
                return $user;
            }
            else{
            //We need to remove the key from the directory if it fails the detection check. Ideally we should have a staging area and only move it to the final key location after verifying.
                $command = escapeshellcmd('rm ' . $userfacepath . "/key.png");
                shell_exec($command);
                return $this->errorArray("No face was detected");
            }

        } else {
            return $this->errorArray("Error adding file");
        }
    }

    protected function userFaceUploaded($user_id) {
        $stmt = $this->pdo->prepare("update user set face_uploaded = 1 where user_id = ?");
        $stmt->execute(array($user_id));
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