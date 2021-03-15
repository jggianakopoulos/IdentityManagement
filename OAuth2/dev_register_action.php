<?php

$email = (array_key_exists("email", $_REQUEST)) ? $_REQUEST["email"] : "";
$password = (array_key_exists("password", $_REQUEST)) ? $_REQUEST["password"] : "";
$company = (array_key_exists("company", $_REQUEST)) ? $_REQUEST["company"] : "";
$confirm_password = (array_key_exists("confirm_password", $_REQUEST)) ? $_REQUEST["confirm_password"] : "";

function not_empty($var) {
    return !is_null($var) && $var != "";
}

$error_message = "";
if (not_empty($email) && not_empty($password) && not_empty($confirm_password) && $password == $confirm_password) {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=identity", 'root', "");
        $stmt = $pdo->prepare("select * from developer where Email = ?");
        $stmt->execute(array($email));
        $dev = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dev) {
            $error_message = "A user with this email already exists";
        } else {
            $stmt = $pdo->prepare("insert into developer (Email, PasswordHash, Company, ClientID, ClientSecret) values (?,?,?,?,?)");
            $stmt->execute(array($email, $password,$company, md5(uniqid()), md5(uniqid())));
            $stmt = $pdo->prepare("select * from developer where Email = ?");
            $stmt->execute(array($email));
            $dev = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!is_null($dev) && $dev) {
                session_start();
                $_SESSION["email"] = $email;
                $_SESSION["password"] = $password;
                $_SESSION["company"] = $company;
                header("Location: dev_home.php");
            } else {
                $error_message = "An error occurred with your login1";
            }
        }
    } catch (Exception $e) {
        $error_message = "An error occurred with your login2";
    }

    if ($error_message != "") {
        echo $error_message;
    }
} else {
    header("Location: dev_register.php");
}

