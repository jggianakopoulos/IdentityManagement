<?php
require('dbconn.php');
   $email = (array_key_exists('email', $_POST)) ? $_POST["email"] : "";
   $password = (array_key_exists('password', $_POST)) ? $_POST["password"] : "";
   $new_password = (array_key_exists('new_password', $_POST)) ? $_POST["new_password"] : "";
   $confirm_password = (array_key_exists('confirm_password', $_POST)) ? $_POST["confirm_password"] : "";

   $error_message = "";

   if ($email == "" || $password == "" || $new_password == "" || $new_password != $confirm_password) {
      $error_message = "You must include a valid password";
   } else {
      try {
         $pdo = new PDO("mysql:host=$servername;dbname=$database", $dbusername, $dbpassword);
         $stmt = $pdo->prepare("select * from user where Email = ? and PasswordHash = ?");
         $stmt->execute(array($email, $password));
         $user = $stmt->fetch(PDO::FETCH_ASSOC);

         if (!$user || is_null($user)) {
         	$error_message = "Incorrect password";
         } else {
	         $stmt = $pdo->prepare("update user set PasswordHash = ? where UserID = ?");
	         $stmt->execute(array($new_password, $user["UserID"]));

            $stmt = $pdo->prepare("select * from user where UserID = ?");
            $stmt->execute(array($user["UserID"]));
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(array(
               "user" => $user,
               "UserID" => $user["UserID"],
               "error_message" => ""
            ));
         }

      } catch (Exception $e) {
         $error_message = "There was an error with your login";
      }
   }

   if ($error_message != "") {
         echo json_encode(array(
            "error_message" => $error_message,
            "userID" => 0,
            "user" => ""
         ));
   }

?>
