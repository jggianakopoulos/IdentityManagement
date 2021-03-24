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
				<form action='login_action.php'>
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
								<div style="margin:20px 5px 5px 5px;display:flex;justify-content:center;">
                                    <button class="btn" style="width: 100%;" id="login-btn">Login</button>
								</div>
							</div>
					</div>
				</form>
				<div class="content" style="padding:2px 10px;display:inline-block;">No account? <a href="http://localhost/identitymanagement/api/developer/register.php">Create one.</a></div>
			</div>
		</div>
	</body>
</html>