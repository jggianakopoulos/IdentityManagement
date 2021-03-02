<?php

require('dbconn.php');
   $email = $_POST['email'];
   $first_name = (array_key_exists('first_name', $_POST)) ? $_POST["first_name"] : "";
   $last_name = (array_key_exists('last_name', $_POST)) ? $_POST["last_name"] : "";
   $password = $_POST['password'];
   $confirm_password = $_POST['confirm_password'];
   $phone_number = (array_key_exists('phone_number', $_POST)) ? $_POST["phone_number"] : "";

   $error_message = "";

   if (is_null($email) || is_null($password) || is_null($confirm_password) || $password != $confirm_password) {
      $error_message = "You must include a valid email and password";
   } else {
      try {
         $pdo = new PDO("mysql:host=$servername;dbname=$database", $dbusername, $dbpassword);
         $stmt = $pdo->prepare("select * from user where email = ?");
         $stmt->execute(array($email));
         $user = $stmt->fetch(PDO::FETCH_ASSOC);
         if ($user) {
         	$error_message = "A user with this email already exists";
         } else {
	         $stmt = $pdo->prepare("insert into user (Email, PasswordHash, FirstName, LastName) values (?,?,?,?)");
	         $stmt->execute(array($email, $password, $first_name, $last_name));

            $stmt = $pdo->prepare("select * from user where email = ?");
            $stmt->execute(array($email));
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(array(
               "user" => $user,
               "UserID" => $user["UaerID"],
               "error_message" => ""
            ));
         }

      } catch (Exception $e) {
         $error_message = $e->getMessage();
      }
   }

   if ($error_message != "") {
         echo json_encode(array(
            "error_message" => $error_message,
            "UserID" => 0,
            "user" => ""
         ));
   }

?>
