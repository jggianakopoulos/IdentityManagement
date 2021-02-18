<?php

   $dbuser = 'IMApp';
   $dbpw = 't!5@m9N5QFAv8UWE';
   $email = $_POST['email'];
   $password = $_POST['password'];
   $error_message = "";

   if (is_null($email) || is_null($password)) {
      $error_message = "You did not submit all of the required information";
   } else {
      try {
      	// We should reconsider the security of all of this. Especially the echoing of user info
      	// Also add an api access user to the database and change the line below
         $pdo = new PDO("mysql:host=localhost;dbname=identity", 'IMApp', 't!5@m9N5QFAv8UWE');
         $stmt = $pdo->prepare("select * from user where email = ? and password = ?");
         $stmt->execute(array($email, $password));
         $user = $stmt->fetch(PDO::FETCH_ASSOC);
         if (!is_null($user) && array_key_exists("user_id", $user)) {
         	echo json_encode(array(
         		"user" => $user,
         		"user_id" => $user["user_id"],
         		"error_message" => ""
         	));
         } else {
         	$error_message = "A user does not exist with these credentials";
         }

      } catch (Exception $e) {
         $error_message = "An error occurred with your login";
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