<?php
require("config/settings.php");
$redirect_url = "http://$server/redirect-success.php";
$client_id = "664c6fc3325ffd77746c02e185601a50 ";
$verification_token = "abc123";
$cancel_url = "http://$server/testsite.php";
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
        <button><a href="http://<?php echo $server; ?>/api/authorize.php?client_id=<?php echo $client_id;?>&redirect_url=<?php echo $redirect_url;?>&verification_token=<?php echo $verification_token;?>&cancel_url=<?php echo $cancel_url;?>">Identity Login</a></button>
    </body>
</html>