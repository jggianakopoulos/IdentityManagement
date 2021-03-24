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
    <div class="container" style="display: block;padding: 0 100px;">
            <div class='content'>
                <div style="flex-direction: row;display: flex;">
                    <div class="tab">IdentityManagement</div>
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
        <a style="padding: 2px 10px;display: inline-block;background-color: #d3d4ea;border: 1px #7d7d7d solid;border-radius: 6px;margin-top: 2px;"<a href="http://localhost/identitymanagement/api/developer/logout.php">Logout.</a>
    </div>
</div>
</body>
</html>