<?php
$redirect_url = "http://34.69.148.52/userdata.php";
$client_secret = "c2be2d061d0c4f56060ae9d18a2c0ca0";
$token = $_REQUEST["token"];
?>
<html style="background-color:grey;">
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    </head>
        <body style="height:50vh;margin:auto;text-align: center;display: flex;flex-direction: column;justify-content: center;align-items: center;background-color:grey;">
        <p>Success on token retrieval. Token is: <?php echo $token; ?></p>
        <p>This token will be sent to the resource server with the Client Secret. The access token will be returned to
            the designated url, which will be used to retrieve the user data associated with this token. To make this happen an d display the returned user data, </p><a href="<?php echo $redirect_url ?>?token=<?php echo $token;?>&client_secret=<?php echo $client_secret;?>">CLICK HERE</a>
    </body>
</html>