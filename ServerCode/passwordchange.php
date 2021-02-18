<?php
   
   $dbuser = 'IMApp';
   $dbpw = 't!5@m9N5QFAv8UWE';
   $email = (array_key_exists('email', $_POST)) ? $_POST["email"] : "";
   $password = (array_key_exists('password', $_POST)) ? $_POST["password"] : "";
   $new_password = (array_key_exists('new_password', $_POST)) ? $_POST["new_password"] : "";
   $confirm_password = (array_key_exists('confirm_password', $_POST)) ? $_POST["confirm_password"] : "";

   $error_message = "";

   if ($email == "" || $password == "" || $new_password == "" || $new_password != $confirm_password) {
      $error_message = "You must include a valid password";
   } else {
      try {
      	$pdo = new PDO("mysql:host=localhost;dbname=identity", 'IMApp', 't!5@m9N5QFAv8UWE');
         $stmt = $pdo->prepare("select * from user where email = ? and password = ?");
         $stmt->execute(array($email, $password));
         $user = $stmt->fetch(PDO::FETCH_ASSOC);

         if (!$user || is_null($user)) {
         	$error_message = "Incorrect password";
         } else {
	         $stmt = $pdo->prepare("update user set password = ? where user_id = ?");
	         $stmt->execute(array($new_password, $user["user_id"]));

            $stmt = $pdo->prepare("select * from user where user_id = ?");
            $stmt->execute(array($user["user_id"]));
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
	         
            echo json_encode(array(
               "user" => $user,
               "user_id" => $user["user_id"],
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
            "user_id" => 0,
            "user" => ""
         ));
   }

?>