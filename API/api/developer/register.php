<?php require("../../config/settings.php");
?>

<!DOCTYPE html>
<html class="background-color">
<head>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="../sha256.js" type="text/javascript"></script>
    <link rel='stylesheet' type='text/css' href='../styles.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">

</head>
<body class="background-color">
<div>
    <div class="container">
        <div class="branding" style="margin: 50px auto 0 auto;">
            <img style="width:100px" src="../user.svg">
        </div>
        <div style="font-weight: 600;">Developer Login</div>
        <form action='register_action.php'>
            <div class='content'>
                <div style="flex-direction: row;display: flex;">
                    <div class="tab">IdentityManagement</div>
                </div>
                <div id="login">
                    <div>
                        <div class="input-field">
                            <label for='email'>Email</label>
                            <input type='text' name='email' id='email'>
                        </div>
                        <div class="input-field">
                            <label for='company'>Company (Optional)</label>
                            <input type='text' name='company' id='company'>
                        </div>
                        <div class="input-field">
                            <label for='password'>Password</label>
                            <input type='password' name='password' id='password'>
                        </div>
                        <div class="input-field">
                            <label for='confirm_password'>Confirm Password</label>
                            <input type='password' name='confirm_password' id='confirm_password'>
                        </div>
                    </div>
                    <div style="margin:5px;">`
                        <button class="btn" style="float:right;margin:8px" id='login-btn' id='continue'>Register</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="content" style="padding:2px 10px;display:inline-block;">Already have an account? <a href="http://<?php echo $server; ?>/api/developer/login.php">Login.</a></div>
    </div>
</div>
</body>
</html>