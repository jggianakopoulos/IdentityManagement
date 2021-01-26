<?php
   $email = $_POST['email'];
   $name = $_POST['name'];
   $password = $_POST['password'];
   $confirm_password = $_POST['confirm_password'];

   if (is_null($email) || is_null($password) || is_null($password) || $password != $confirm_password) {
      echo 0;
   } else {
      try {
      	$pdo = new PDO("mysql:host=localhost;dbname=identitymanagement", 'root', "");
         $stmt = $pdo->prepare("select * from user where email = ?");
         $stmt->execute(array($email));
         $user = $stmt->fetch(PDO::FETCH_ASSOC);
         if ($user) {
         	echo 0;
         } else {
	         $first_name = is_null($name) ? "" : $name;
	      	// $last_name = is_null($last_name) ? "" : $last_name;
	      	// $phone_number = is_null($phone_number) ? "" : $phone_number;

	         $stmt = $pdo->prepare("insert into user (email, password, first_name) values (?,?,?)");
	         $stmt->execute(array($email, $password, $name));
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
	         echo 1;
         }

      } catch (Exception $e) {
         echo 0;
      }
   }

?>