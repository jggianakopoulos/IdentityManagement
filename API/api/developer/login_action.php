<?php
	require("../../factories/DeveloperFactory.php");

	$df = new DeveloperFactory();
	$dev = $df->considerLogin($_REQUEST);

   if (is_null($dev) || !$df->_noError($dev)) {
        header("Location: http://localhost/identitymanagement/api/developer/login.php");
   } else {
   		session_start();
   		$_SESSION['email'] = $dev["email"];
		$_SESSION['password']   = $dev["password"];
   		header("Location: http://localhost/identitymanagement/api/developer/home.php");
   }

?>


