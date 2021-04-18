<?php
require("config/settings.php");
$redirect_url = "http://$server/userdata.php";
$client_id = "664c6fc3325ffd77746c02e185601a50 ";
$verification_token = "abc123";
$cancel_url = "http://$server/testsite.php";
?>
<html lang="en"><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Login </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="assets/styles/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/styles/util.css">
    <link rel="stylesheet" type="text/css" href="assets/styles/main.css">

</head>
<body>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-l-110 p-r-110 p-t-62 p-b-33">
            <form class="login100-form validate-form flex-sb flex-w">
<span class="login100-form-title p-b-53">
Sign In With
</span>
                <a href="http://<?php echo $server; ?>/api/authorize.php?client_id=<?php echo $client_id;?>&redirect_url=<?php echo $redirect_url;?>&verification_token=<?php echo $verification_token;?>&cancel_url=<?php echo $cancel_url;?>" class="btn-im m-b-20">
                    <img src="assets/styles/icon-lock.png" alt="Identity Management">
                    Identity Management
                </a>
                <a href="#" class="btn-google m-b-20">
                    <img src="assets/styles/icon-google.png" alt="GOOGLE">
                    Google
                </a>
                <div class="p-t-31 p-b-9">
<span class="txt1">
Username
</span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Username is required">
                    <input class="input100" type="text" name="username">
                    <span class="focus-input100"></span>
                </div>
                <div class="p-t-13 p-b-9">
<span class="txt1">
Password
</span>
                    <a href="#" class="txt2 bo1 m-l-5">
                        Forgot?
                    </a>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="pass">
                    <span class="focus-input100"></span>
                </div>
                <div class="container-login100-form-btn m-t-17">
                    <button class="login100-form-btn">
                        Sign In
                    </button>
                </div>
                <div class="w-full text-center p-t-55">
<span class="txt2">
Not a member?
</span>
                    <a href="#" class="txt2 bo1">
                        Sign up now
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="dropDownSelect1"></div>

<script src="assets/styles/jquery-3.js"></script>

<script src="assets/styles/bootstrap.js"></script>

<script src="assets/styles/main.js"></script>

<script async="" src="assets/styles/js"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-23581568-13');
</script>


</body></html>
