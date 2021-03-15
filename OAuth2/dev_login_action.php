<?php

	$email = (array_key_exists("email", $_REQUEST)) ? $_REQUEST["email"] : "";
	$password = (array_key_exists("password", $_REQUEST)) ? $_REQUEST["password"] : "";
	$error_message = "";

	if ($email == "" || is_null($email) || $email == "" || is_null($password)) {
		$error_message = "You must enter valid credentials.";
	}

   if ($error_message != "") {
        header("Location: http://localhost/identitymanagement/dev_login.php");
   } else {
   		session_start();
   		$_SESSION['email'] = $email;
		$_SESSION['password']   = $password;
   		header("Location: http://localhost/identitymanagement/dev_home.php");
   }

?>


