<!DOCTYPE html>
<html class="backgroud-color">
	<head>
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="sha256.js" type="text/javascript"></script>
		<link rel='stylesheet' type='text/css' href='styles.css'>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">       

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
	  							<input type="file" accept="image/*" name="file" id="facefile" name="facefile">
							</div>
							<button class="btn" style="text-align:center;margin: 5px;" id="submit">Verify and Continue</button>
						</div>
				</div>
				<div class="content" style="padding:2px 10px;display:inline-block;">No account? <a href="#">Create one.</a></div>
			</div>
		</div>


		<script>

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
					  url: "http://localhost/identitymanagement/login.php",
					  data: { email: user_email, password: SHA256(user_pw) },
					  dataType: 'json',
					  success: verify_page,
					  error: login_page
					})
			};


            //This function needs to redirect to the redirect_url, include an auth token, and include the verification token from the original request
            var redirect_with_token = function(token) {
                console.log("redirect with token");
                window.location.replace("<?php echo $_REQUEST["redirect_url"] ?>" + "?token=" + token);
            };

            var face_check = function() {
				var user_email = $("#email").val().trim();
				var user_pw = $("#password").val().trim();

				var formData = new FormData();
				formData.append('file', $('#facefile')[0].files[0]);
				formData.append("email", user_email);
                formData.append("password", SHA256(user_pw));

				$.ajax({
					  method: "POST",
					  url: "http://localhost/identitymanagement/facecheck.php",
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

		</script>
	</body>
</html>