<?php require("../../config/settings.php")?>
<!DOCTYPE html>
<html class="background-color" style="background-color: #fbfbfb">
<head>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="../sha256.js" type="text/javascript"></script>
    <link rel='stylesheet' type='text/css' href='../styles.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">

</head>
<body class="background-color" style="
    background-color: #fbfbfb;
">
<div style="background-color: #fbfbfb"><div style="margin: auto;background-color: #c3c3c3;padding-top: 80px;max-width: 1140px;font-weight: bold;display: flex;font-size: 30px;padding-left: 20px;"><img style="width: 50px;padding: 5px;" src="../user.svg"><div style="align-self: flex-end;padding-left: 10px;align-items: end;">IdentityManagement</div>
    </div></div>
<div class="container" style="display: block;padding: 0;margin: auto;background-color: #fbfbfb;">
    <div style="
    background-color: #e8e8e8;
    display: flex;
    flex-direction: column;

    ">
        <div style="flex-direction: row;display: flex;">

        </div>
        <div id="login">
            <div>
                <div class="display-field">
                    <label class="display-label" for='email'>Email</label>
                    <div name="email" id='email'><?php echo $_SESSION["email"]; ?></div>
                </div>
                <div class="display-field">
                    <label class="display-label" for='Company'>Company</label>
                    <div name="company" id='company'><?php echo $_SESSION["company"]; ?></div>
                </div>
                <div class="display-field">
                    <label class="display-label" for='client_id'>Client ID</label>
                    <div id='client_id'><?php echo $_SESSION["client_id"]; ?></div>
                </div>
                <div class="display-field">
                    <label class="display-label" for='client_secret'>Client Secret</label>
                    <div id='client_secret'><?php echo $_SESSION["client_secret"]; ?></div>
                </div>
            </div>
        </div>
    </div>
    <a style="padding: 2px 10px;background-color: #c3c3c3;margin-top: 2px;float: right;color: black;" <a="" href="http://localhost/identitymanagement/api/developer/logout.php">Click here to logout</a>
</div>



</body>
</html>