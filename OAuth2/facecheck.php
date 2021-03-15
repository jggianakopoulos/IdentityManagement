<?php
//
//echo print_r($_FILES, true);
//
//file_put_contents("test.png", file_get_contents($_FILES["file"]["tmp_name"]));

$email = $_REQUEST["email"];
$password = $_REQUEST["password"];
$error_message = "";

$pdo = new PDO("mysql:host=localhost;dbname=identity", 'root', "");
$stmt = $pdo->prepare("select * from user where Email = ? and PasswordHash = ?");
$stmt->execute(array($email, $password));
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (is_null($user) || !$user || !array_key_exists("UserID", $user)) {
    $error_message = "User is wrong";
}

if ($error_message == "") {
    $stmt = $pdo->prepare("insert into authToken (UserID, token) values (?,?)");
    $token = md5(uniqid());
    $stmt->execute(array($user["UserID"], $token));
//    $stmt = $pdo->prepare("select * from authToken where token = ?");
//    $stmt->execute(array($token));
//    $token_arr = $stmt->fetch(PDO::FETCH_ASSOC);
//    if (is_null($user) || !$user || !array_key_exists("UserID", $user)) {
//        $error_message = "Error getting the token after an add";
//    }

    echo $token;
} else {
    echo md5(uniqid());
}




?>
