<?php

require("../factories/DeveloperFactory.php");
require("../config/settings.php");

$f = new DeveloperFactory();
if ($f->_hasValue($_REQUEST, "cancel_url")  && $f->_hasValue($_REQUEST, "redirect_url") && $f->_hasValue($_REQUEST, "client_id") && $company = $f->validateClientID($_REQUEST["client_id"])) {
    $client_id = $_REQUEST["client_id"];
    $company = ($f->_hasValue($company)) ? $company : "Company";

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
                <div id="email-message" class="alert" style="margin: 10px;background-color:#b1bace;display:none;">Your login code has been emailed.</div>
                <div id="email-section">
                    <div>
                        <div class="input-field">
                            <label style='margin:0'for='email'>Email</label>
                            <input type='text' name='email' id='email'>
                        </div>
                    </div>
                    <div>
                        <!--								<button class="btn nav-btn" onclick="window.location.href='--><?php //echo $_REQUEST["cancel_url"] ?><!--////';" type="button" >Cancel</button>-->
                        <button class="btn nav-btn" style="float:right" id='email-btn' type="button">Login</button>
                    </div>
                </div>
                <div id="permission-section" class="hidden" >
                    <p style="margin:10px;max-width:350px;font-style:italic;">What information do you want to give to <b><?php echo $company; ?></b>?</p>
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
                <div id="password-section">
                    <div>
                        <div class="input-field">
                            <label style='margin:0'for='password'>Password</label>
                            <input type='password' name='password' id='password'>
                        </div>
                    </div>
                    <div>
                        <!--								<button class="btn nav-btn" onclick="window.location.href='--><?php //echo $_REQUEST["cancel_url"] ?><!--////';" type="button" >Cancel</button>-->
                        <button class="btn nav-btn" style="float:right" id='password-btn' type="button">Continue</button>
                    </div>
                </div>
                <div id="code-section">
                    <div>
                        <div class="input-field">
                            <label style='margin:0'for='code'>Sign-In Code</label>
                            <input type='text' name='code' id='code'>
                            <button id="send-code-btn" class="btn nav-btn">Email Code</button>
                        </div>
                    </div>
                    <div>
                        <!--								<button class="btn nav-btn" onclick="window.location.href='--><?php //echo $_REQUEST["cancel_url"] ?><!--////';" type="button" >Cancel</button>-->
                        <button class="btn nav-btn" style="float:right" id='code-btn' type="button">Continue</button>
                    </div>
                </div>
                <div id="face-section" class="hidden" style="display: flex;flex-direction:column;">
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
                    <button class="btn nav-btn" style="text-align:center;margin: 5px;" id="face-btn">Verify and Continue</button>
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

        //This function needs to redirect to the redirect_url, include an auth token, and include the verification token from the original request
        var redirect_with_token = function(token) {
            console.log("redirect with token");
            window.location.replace("<?php echo $_REQUEST["redirect_url"] ?>" + "?authtoken=" + token);
        };

        var redirect_handler = function(input) {
            console.log(input);
            if (input["error_message"]) {
                show_error(input["error_message"]);
                console.log("error");
            } else if (input["next"]) {
                switch(input["next"]) {
                    case "password":
                        password_page();
                        break;
                    case "code":
                        code_page();
                        break;
                    case "face":
                        face_page();
                        break;
                    default:
                        email_page();

                }

            } else if (input["token"]){
                redirect_with_token(input["token"])
            } else {
                show_error("An error occurred. Please try again");
            }
        };

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

        var hide_error = function() {
            $("#error-message").addClass("hidden");
        };

        var show_error = function(message) {
            $("#error-message").text(message).removeClass("hidden");
        }

        var hide_all = function() {
            hide_error();
            $("#email-section").addClass("hidden");
            $("#permission-section").addClass("hidden");
            $("#code-section").addClass("hidden");
            $("#password-section").addClass("hidden");
            $("#face-section").addClass("hidden");
        };

        var email_page = function() {
            hide_all();
            $("#email-section").removeClass("hidden");
        };

        var password_page = function() {
            hide_all();
            $("#password-section").removeClass("hidden");
        };


        var permissions_page = function() {
            hide_all();
            $("#permission-section").removeClass("hidden");
        };

        var code_page = function() {
            hide_all();
            $("#code-section").removeClass("hidden");
        };

        var face_page = function() {
            hide_all();
            $("#face-section").removeClass("hidden");
            $("#loading").addClass("hidden");
            $("#submit").removeClass("hidden");
        };

        var assemble_form_data = function(type) {
            var user_email = $("#email").val().trim();
            var user_pw = $("#password").val().trim();
            var user_code = $("#code").val().trim();
            var permission_els = $("input[name='permission[]']:checked");
            var permissions = [];

            for(var i=0;i<permission_els.length;i++) {
                permissions.push(permission_els[i].value.trim());
            }

            var formData = new FormData();
            formData.append("email", user_email);
            formData.append("password", SHA256(user_pw));
            formData.append("code", user_code);
            formData.append("permissions", permissions);
            formData.append("client_id", "<?php echo $client_id ?>");

            if (type == "face") {
                var canvas = document.getElementById('canvas');
                formData.append('image', canvas.toDataURL());
            }

            return formData;
        }

        var email_check = function() {
            console.log("email check");
            $.ajax({
                method: "POST",
                url: "http://<?php echo $server; ?>/api/auth/emailcheck.php",
                data: assemble_form_data(),
                processData:false,
                dataType: 'json',
                contentType: false,
                cache: false,
                success: function(e) {
                    redirect_handler(e);
                },
                enctype: 'multipart/form-data',
                error: function(e) {
                    show_error("An error occurred with your sign in.");
                }
            })
        };

        var password_check = function() {
            console.log("password check");
            $.ajax({
                method: "POST",
                url: "http://<?php echo $server; ?>/api/auth/passwordcheck.php",
                data: assemble_form_data(),
                processData:false,
                dataType: 'json',
                contentType: false,
                cache: false,
                success: function(e) {
                    redirect_handler(e);
                },
                enctype: 'multipart/form-data',
                error: function(e) {
                    show_error("An error occurred with your sign in.");
                }
            })
        };

        var send_code = function() {
            console.log("send code");
            $.ajax({
                method: "POST",
                url: "http://<?php echo $server; ?>/api/auth/generatelogincode.php",
                data: assemble_form_data(),
                processData:false,
                dataType: 'json',
                contentType: false,
                cache: false,
                success: function(e) {
                    console.log(e);
                    if (e["error_message"]) {
                        show_error(e["error_message"]);
                    } else {
                        $('#email-message').fadeIn();
                        setTimeout(function() {
                            $("#email-message").fadeOut();
                        }, 5000);
                    }
                },
                enctype: 'multipart/form-data',
                error: function(e) {
                    console.log(e);
                    show_error("An error occurred with your sign in.");
                }
            })
        };

        var code_check = function() {
            console.log("code check");
            $.ajax({
                method: "POST",
                url: "http://<?php echo $server; ?>/api/auth/codecheck.php",
                data: assemble_form_data(),
                processData:false,
                dataType: 'json',
                contentType: false,
                cache: false,
                success: function(e) {
                    redirect_handler(e);
                },
                enctype: 'multipart/form-data',
                error: function(e) {
                    show_error("An error occurred with your sign in.");
                }
            })
        };

        var loading_status = function(e) {
            $("#verify").removeClass("hidden");
            $("#loading").removeClass("hidden");
            $("#face-btn").addClass("hidden");
        };

        var face_check = function() {
            console.log("face check");
            loading_status();
            $.ajax({
                method: "POST",
                url: "http://<?php echo $server; ?>/api/auth/facecheck.php",
                data: assemble_form_data("face"),
                processData:false,
                dataType: 'json',
                contentType: false,
                cache: false,
                success: function(e) {
                    face_page();
                    redirect_handler(e);
                },
                enctype: 'multipart/form-data',
                error: function(e) {
                    face_page();
                    show_error("Invalid picture. Please try again.");
                }
            });
        };

        permissions_page();
        $("#permission-btn").click(email_page);
        $("#email-btn").click(email_check);
        $("#password-btn").click(password_check);
        $("#code-btn").click(code_check);
        $("#face-btn").click(face_check);
        $("#snap").click(picture_taken);
        $("#send-code-btn").click(send_code);
        $("#retake").click(start_camera);


    </script>
    </body>
    </html>

    <?php

} else {
    header("Location: error.html");
}