<?php

require("UserFactory.php");

class FaceFactory extends BaseFactory {

    public function __construct() {
        parent::__construct();
        $this->table = "face";
        $this->table_id = "face_id";
    }

    protected function base64toImage($base64) {
        $img = str_replace('data:image/png;base64,', '', $base64);
        $img = str_replace(' ', '+', $img);
        return base64_decode($img);
    }

    public function considerUpdate($data) {
        $values = $this->_validateUpdate($data);

        if (array_key_exists("error_message", $values) || !array_key_exists("user_id", $values) || !($values["user_id"] > 0)) {
            return $values;
        } else {
            return $this->updateFace($values, $data["image"]);
        }
    }

    protected function _validateUpdate($data) {
        if (!array_key_exists("image", $data) || !is_string($data["image"])) {
            return $this->errorArray("Invalid image uploaded");
        }
        $uf = new UserFactory();
        return $uf->considerLogin($data);
    }

    protected function updateFace($user, $data) {
        $image = $this->base64toImage($data);

        if (!$image) {
            return $this->errorArray("Invalid image uploaded");
        }
        $path = '../../assets/user' . $user["user_id"];

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

    protected function getUserPicture() {

    }
}