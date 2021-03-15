<?php
session_start();
$error_message = "";

if (is_null($_SESSION) || !array_key_exists("password", $_SESSION) || !array_key_exists("email",  $_SESSION)) {
	$error_message = "Invalid credentials";
} else {
	$password = $_SESSION["password"];
	$email = $_SESSION["email"];

	try {
         $pdo = new PDO("mysql:host=localhost;dbname=identity", 'root', "");
         $stmt = $pdo->prepare("select * from developer where Email = ? and PasswordHash = ?");
         $stmt->execute(array($email, $password));
         $dev = $stmt->fetch(PDO::FETCH_ASSOC);
         if (is_null($dev) || !$dev || !array_key_exists("DeveloperID", $dev)) {
            $error_message = "Invalid account";
         }
      } catch (Exception $e) {
         $error_message = "An error occurred with your login";
      }

      if ($error_message != "") {
  	    echo $error_message;
      } else {
          $_SESSION["company"] = $dev["Company"];
          $_SESSION["client_id"] = $dev["ClientID"];
          $_SESSION["client_secret"] = $dev["ClientSecret"];

          include("dev_home_layout.php");

      }
}

if ($error_message != "") {
    header("Location: dev_login.php");
}

?>