<?php
	require("../../factories/DeveloperFactory.php");
	require("../../config/settings.php");

	$df = new DeveloperFactory();
	$dev = $df->considerLogin($_REQUEST);

   if (is_null($dev) || $df->_hasError($dev)) {
        header("Location: http://$server/api/developer/login.php");
   } else {
   		session_start();
   		$_SESSION['email'] = $dev["email"];
		$_SESSION['password']   = $dev["password"];
   		header("Location: http://$server/api/developer/home.php");
   }

?>


