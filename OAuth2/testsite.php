<?php
$redirect_url = "http://localhost/identitymanagement/redirect-success.php";
//$response_type = "code";
$client_id = "client_id";
$verification_token = "abc123";
$cancel_url = "http://localhost/identitymanagement/testsite.php";

?>

<html style="background-color:grey;">
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">       

		<style type="text/css">
			button {
				border:1px black solid;
				width:300px;
			}
		</style>
	</head>
	<body style="height:50vh;margin:auto;text-align: center;display: flex;flex-direction: column;justify-content: center;align-items: center;background-color:grey;">
		<h1> Test Site</h1>
		<button>Login With this Site</button>
		<button><a href="http://localhost/identitymanagement/authorize.php?client_id=<?php echo $client_id;?>&redirect_url=<?php echo $redirect_url;?>&verification_token=<?php echo $verification_token;?>&cancel_url=<?php echo $cancel_url;?>">Identity Login</a></button>
	
	</body>
</html>