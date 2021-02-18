<?php

   $dbuser = 'IMApp';
   $dbpw = 't!5@m9N5QFAv8UWE';
   $email = $_POST['old_email'];
   $new_email = $_POST['email'];
   $first_name = (array_key_exists('first_name', $_POST)) ? $_POST["first_name"] : "";
   $last_name = (array_key_exists('last_name', $_POST)) ? $_POST["last_name"] : "";
   $password = $_POST['password'];
   $phone_number = (array_key_exists('phone_number', $_POST)) ? $_POST["phone_number"] : "";

   $error_message = "";

   if (is_null($new_email) || is_null($password)) {
      $error_message = "You must include a valid email and password";
   } else {
      try {
      	$pdo = new PDO("mysql:host=localhost;dbname=identity", 'IMApp', 't!5@m9N5QFAv8UWE');
         $stmt = $pdo->prepare("select * from user where email = ? and password = ?");
         $stmt->execute(array($email, $password));
         $user = $stmt->fetch(PDO::FETCH_ASSOC);

         if (!$user || is_null($user)) {
         	$error_message = "Incorrect Password";
         } else {
	         $stmt = $pdo->prepare("update user set email = ?, password = ?, first_name = ?, last_name = ?, phone_number = ? where user_id = ?");
	         $stmt->execute(array($new_email, $password, $first_name, $last_name, $phone_number, $user["user_id"]));

            $stmt = $pdo->prepare("select * from user where email = ?");
            $stmt->execute(array($new_email));
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