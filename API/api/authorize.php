<?php

    require("../factories/DeveloperFactory.php");

    $f = new DeveloperFactory();
    if ($f->_hasValue($_REQUEST, "cancel_url")  && $f->_hasValue($_REQUEST, "redirect_url") && $f->_hasValue($_REQUEST, "client_id") && $f->validateClientID($_REQUEST["client_id"])) {

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
	<body class="background-color">

		<div>
			<div class="container">
				<div class="branding" style="margin: 50px auto 0 auto;">
					<img style="width:100px" src="user.svg">
				</div>
				<div style="font-weight: 600;">Verify Your Identity</div>
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
									<label for='password'>Password</label>
									<input type='password' name='password' id='password'>
								</div>
							</div>
							<div style="margin:5px;">
								<button class="btn" onclick="window.location.href='<?php echo $_REQUEST["cancel_url"] ?>';" type="button"style="margin:8px;" >Cancel</button>
								<button class="btn" style="float:right;margin:8px" id='login-btn' type="button" id='continue'>Login</button>
							</div>
						</div>
						<div id="verify" class="hidden" style="display: flex;flex-direction:column;">
							<div class="input-field">
								<label for="facefile">Upload Face (Front Angle)</label>
                                <div id="video-holder"></div>
                                <video id="webcam"  playsinline width="300" height="200"></video>
                                <canvas id="canvas" class="d-none"></canvas>
                                <div style="display:flex;flex-direction:row;justify-content: center;margin-top: 10px;">
                                    <button class="btn" id="snap" type="button">Snap photo</button>
                                    <button class="btn d-none" id="retake" type="button">Retake Picture</button>
                                </div>
<!--                                <audio id="snapSound" src="audio/snap.wav" preload = "auto"></audio>-->
							</div>
							<button class="btn" style="text-align:center;margin: 5px;" id="submit">Verify and Continue</button>
						</div>
				</div>
				<div class="content" style="padding:2px 10px;display:inline-block;">No account? <a href="#">Create one.</a></div>
			</div>
		</div>


		<script>
            const webcamElement = document.getElementById('webcam');
            const canvasElement = document.getElementById('canvas');
            const snapSoundElement = document.getElementById('snapSound');
            const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
            webcam.start();

            // On start
            //  Add button to start camera. Camera and canvas hidden

            //  Add button for "snap photo"

            // When snap clicked, show canvas, hide camera, stop camera

            // Display "take a new picture" button that -> empties canvas, hides canvas, shows camera, starts camera

            var picture_taken = function(e)  {
                console.log("bbbbb");
                $("#canvas").removeClass("d-none");
                var img = webcam.snap();
                $("#webcam").addClass("d-none");
                $("#snap").addClass("d-none");
                $("#retake").removeClass("d-none");
                //webcam.stop();
            };

            var start_camera = function(e) {
                console.log("aaaa");
                $("#canvas").addClass("d-none");
                webcam.start();
                $("#webcam").removeClass("d-none");
                $("#retake").addClass("d-none");
                $("#snap").removeClass("d-none");

            }

			var verify_page = function(e) {
				console.log("verify page");
			  	$("#verify").removeClass("hidden");
			  	$("#login").addClass("hidden");
			};

			var login_page = function(e) {
				console.log("login page");
				console.log(e);
			  	$("#verify").addClass("hidden");
			  	$("#login").removeClass("hidden");

			};

			var credential_check = function() {
				console.log("in credential function");

				var user_email = $("#email").val().trim();
				var user_pw = $("#password").val().trim();

                console.log(SHA256(user_pw));

				verify_page();

				$.ajax({
					  method: "POST",
					  url: "http://localhost/identitymanagement/api/user/login.php",
					  data: { email: user_email, password: SHA256(user_pw) },
					  dataType: 'json',
					  success: verify_page,
					  error: login_page
					})
			};


            //This function needs to redirect to the redirect_url, include an auth token, and include the verification token from the original request
            var redirect_with_token = function(token) {
                console.log("redirect with token");
                //window.location.replace("<?php echo $_REQUEST["redirect_url"] ?>" + "?token=" + token);
            };

            var face_check = function() {
                var user_email = $("#email").val().trim();
				var user_pw = $("#password").val().trim();

				var formData = new FormData();
                var canvas = document.getElementById('canvas');
				console.log(canvas.toDataURL());
				formData.append('image', canvas.toDataURL());
				formData.append("email", user_email);
                formData.append("password", SHA256(user_pw));

				$.ajax({
					  method: "POST",
					  url: "http://localhost/identitymanagement/api/user/facecheck.php",
					  data: formData,
					  processData:false,
					  dataType: 'text',
					  contentType: false,
					  cache: false,
					  success: function(e) {
					  	redirect_with_token(e);
					  },
					  enctype: 'multipart/form-data',
					  error: function(e) {
					      console.log(e);
					      login_page();
                      }
					})
			};




			login_page();
			$("#login-btn").click(credential_check);
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