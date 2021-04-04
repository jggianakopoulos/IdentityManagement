<?php

require("../factories/DeveloperFactory.php");
require("../config/settings.php");

$f = new DeveloperFactory();
if ($f->_hasValue($_REQUEST, "cancel_url")  && $f->_hasValue($_REQUEST, "redirect_url") && $f->_hasValue($_REQUEST, "client_id") && $f->validateClientID($_REQUEST["client_id"])) {
    $client_id = $_REQUEST["client_id"];

    ?>

    <!DOCTYPE html>
    <html class="background-color">
    <head>
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="sha256.js" type="text/javascript"></script>
        <link rel='stylesheet' type='text/css' href='styles.css'>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
        <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
    </head>
    <body class="background-color" style="font-family:roboto">

    <div>
        <div class="container">
            <div class='content'>
                <div class="branding" style="margin: 25px auto 10px;">
                    <img style="width:100px" src="user.svg">
                </div><div style="font-family: 'Work Sans', sans-serif;font-size:20px;font-weight: 500;margin: auto;">Verify Your Identity</div>
                <div id="error-message" class="hidden alert alert-danger" style="margin: 10px;">An error occurred with your sign-in</div>
                <div id="login">
                    <div>
                        <div class="input-field">
                            <label style='margin:0'for='email'>Email</label>
                            <input type='text' name='email' id='email'>
                        </div>
                        <div class="input-field">
                            <label style='margin:0'for='password'>Password</label>
                            <input type='password' name='password' id='password'>
                        </div>
                    </div>
                    <div>
                        <!--								<button class="btn nav-btn" onclick="window.location.href='--><?php //echo $_REQUEST["cancel_url"] ?><!--////';" type="button" >Cancel</button>-->
                        <button class="btn nav-btn" style="float:right" id='login-btn' type="button">Login</button>
                    </div>
                </div>
                <div id="permissions" class="hidden" >
                    <p style="margin:10px;max-width:350px;font-style:italic;">What information do you want to give to  <b>Company</b>?</p>
                    <div>
                        <div class="input-field" style="flex-direction: row">
                            <div class='checkbox-label'>Email</div>
                            <input type="checkbox" name="permission[]" value="email">
                        </div>
                        <div class="input-field" style="flex-direction: row">
                            <div class='checkbox-label'>First Name</div>
                            <input type="checkbox" name="permission[]" value="firstname">
                        </div>
                        <div class="input-field" style="flex-direction: row">
                            <div class='checkbox-label'>Last Name</div>
                            <input type="checkbox" name="permission[]" value="lastname">
                        </div>
                    </div>
                    <div style="margin:5px;">
                    </div>
                    <div style="margin:5px;">
                        <button class="btn nav-btn" style="float:left;" id='logout-btn' type="button">Return</button>
                        <button class="btn nav-btn" style="float:right;" id='permission-btn' type="button" >Continue</button>
                    </div>
                </div>
                <div id="verify" class="hidden" style="display: flex;flex-direction:column;">
                    <div class="input-field">
                        <label for="facefile">Upload Face</label>
                        <div id="video-holder"></div>
                        <video id="webcam"  playsinline width="300" height="200"></video>
                        <canvas id="canvas" class="d-none"></canvas>
                        <div style="display:flex;flex-direction:row;justify-content: center;margin-top: 10px;">
                            <button class="btn" id="snap" type="button">Snap photo</button>
                            <button class="btn d-none" id="retake" type="button">Retake Picture</button>
                        </div>
                        <!--                                <audio id="snapSound" src="audio/snap.wav" preload = "auto"></audio>-->
                    </div>
                    <button class="btn nav-btn" style="text-align:center;margin: 5px;" id="submit">Verify and Continue</button>
                    <div id='loading' class="btn nav-btn"><div style="margin-right:5px">Authenticating sign-in. Please Wait.</div><div class="lds-ring" style="float: right;"><div></div><div></div><div></div><div></div></div></div></div>
            </div>
        </div>
    </div>
    </div>


    <script>
        const webcamElement = document.getElementById('webcam');
        const canvasElement = document.getElementById('canvas');
        const snapSoundElement = document.getElementById('snapSound');
        const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
        webcam.start();

        var picture_taken = function(e)  {
            var img = webcam.snap();
            hide_error();
            $("#canvas").removeClass("d-none");
            $("#webcam").addClass("d-none");
            $("#snap").addClass("d-none");
            $("#retake").removeClass("d-none");
            //webcam.stop();
        };

        var start_camera = function(e) {
            hide_error();
            $("#canvas").addClass("d-none");
            webcam.start();
            $("#webcam").removeClass("d-none");
            $("#retake").addClass("d-none");
            $("#snap").removeClass("d-none");

        }

        var verify_page = function(e) {
            $("#verify").removeClass("hidden");
            $("#permissions").addClass("hidden");
            $("#login").addClass("hidden");
            $("#loading").addClass("hidden");
            $("#submit").removeClass("hidden");
        };

        var login_page = function(e) {
            $("#verify").addClass("hidden");
            $("#permissions").addClass("hidden");
            $("#login").removeClass("hidden");

        };

        var permissions_page = function(e) {
            $("#verify").addClass("hidden");
            $("#login").addClass("hidden");
            $("#permissions").removeClass("hidden");
        };

        var hide_error = function() {
            $("#error-message").addClass("hidden");
        };

        var show_error = function(message) {
            $("#error-message").text(message);
            $("#error-message").removeClass("hidden");
        }

        var credential_check = function() {
            var user_email = $("#email").val().trim();
            var user_pw = $("#password").val().trim();

            $.ajax({
                method: "POST",
                url: "http://<?php echo $server; ?>/api/user/login.php",
                data: { email: user_email, password: SHA256(user_pw) },
                dataType: 'json',
                success: function(e) {
                    console.log(["a", e]);
                    if (e["error_message"]) {
                        show_error("Invalid credentials.");
                        console.log("error");
                    } else {
                        hide_error();
                        permissions_page();
                    }
                },
                error: function() {
                    login_page();
                    show_error("There was an error with your login");
                }
            })
        };

        //This function needs to redirect to the redirect_url, include an auth token, and include the verification token from the original request
        var redirect_with_token = function(token) {
            console.log("redirect with token");
            window.location.replace("<?php echo $_REQUEST["redirect_url"] ?>" + "?token=" + token);
        };

        var loading_status = function(e) {
            $("#verify").removeClass("hidden");
            $("#loading").removeClass("hidden");
            $("#submit").addClass("hidden");
        };

        var face_check = function() {
            console.log("facecheck");
            loading_status();
            var user_email = $("#email").val().trim();
            var user_pw = $("#password").val().trim();
            var permission_els = $("input[name='permission[]']:checked");
            var permissions = [];

            for(var i=0;i<permission_els.length;i++) {
                permissions.push(permission_els[i].value.trim());
            }

            var formData = new FormData();
            var canvas = document.getElementById('canvas');
            formData.append('image', canvas.toDataURL());
            formData.append("email", user_email);
            formData.append("password", SHA256(user_pw));
            formData.append("permissions", permissions);
            formData.append("client_id", "<?php echo $client_id ?>");

            $.ajax({
                method: "POST",
                url: "http://<?php echo $server; ?>/api/user/facecheck.php",
                data: formData,
                processData:false,
                dataType: 'text',
                contentType: false,
                cache: false,
                success: function(e) {
                    var json = JSON.parse(e);
                    if (json["token"]) {
                        redirect_with_token(json["token"]);
                    } else {
                        show_error("Incorrect face. Please try again.");
                        verify_page(e);
                    }
                },
                enctype: 'multipart/form-data',
                error: function(e) {
                    show_error("There was an error with your picture. Please try again.");
                    login_page();
                }
            })
        };

        var logout = function() {
            //Clear data here too
            login_page();
        };


        login_page();
        $("#login-btn").click(credential_check);
        $("#logout-btn").click(logout);
        $("#permission-btn").click(verify_page);
        $("#submit").click(face_check);
        $("#snap").click(picture_taken);
        $("#retake").click(start_camera);


    </script>
    </body>
    </html>

    <?php

} else {
    header("Location: error.html");
}