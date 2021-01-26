<?php

	
   $email = $_POST['email'];
   $password = $_POST['password'];

   if (is_null($email) || is_null($password)) {
      echo 0;
   } else {
      try {
      
         $pdo = new PDO("mysql:host=localhost;dbname=identitymanagement", 'root', "");
         $stmt = $pdo->prepare("select * from user where email = ? and password = ?");
         $stmt->execute(array($email, $password));
         $user = $stmt->fetch(PDO::FETCH_ASSOC);
         if (!is_null($user) && array_key_exists("user_id", $user)) {
         	echo $user["user_id"];
         } else {
         	echo 0;
         }

      } catch (Exception $e) {
         echo 0;
      }
   }



?>