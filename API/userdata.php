
<script>
    console.log("start");
</script>
<?php
require("config/settings.php");
$client_secret = "c2be2d061d0c4f56060ae9d18a2c0ca0";
$token = $_POST["authtoken"];
echo "Before curl";
$ch = curl_init ();
curl_setopt_array ( $ch, array (
    CURLOPT_URL => 'http://$server/api/developer/convert_token.php',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array (
        'authtoken' => $token,
        'client_secret' => $client_secret
    ),
    CURLOPT_RETURNTRANSFER => 1
) );
$response = curl_exec($ch);
echo "after curl";

$data = json_decode($response, true);
echo "after data";
?>
<script>
    console.log(<?php echo $data?>);
</script>
<?php

if (array_key_exists("error_message", $data)) {
    // Stop everything and assess error
}


    if (array_key_exists("first_name", $data)) {
        $first = $data["first_name"];
    } else {
        $first = "Not shared";
    }
    if (array_key_exists("last_name", $data)) {
        $last = $data["last_name"];
    } else {
        $last = "Not shared";
    }
    if (array_key_exists("email", $data)) {
        $email = $data["email"];
    } else {
        $email = "Not shared";
    }
?>

<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="assets/styles/bootstrap.css">
<link rel="stylesheet" type="text/css" href="assets/styles/util.css">
<link rel="stylesheet" type="text/css" href="assets/styles/main.css">
<head>
    <title>Welcome</title>
    <script>
        console.log(<?php echo json_encode($data);?>);
        console.log(<?php echo json_encode($_REQUEST);?>);

    </script>
</head>

<body>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-l-110 p-r-110 p-t-62 p-b-33">
            <form class="login100-form validate-form flex-sb flex-w">
					<span class="login100-form-title p-b-53">
						Welcome,
						<?php
                        if ($first!=''){
                            echo $first;
                        }
                        elseif ($email != ''){
                            echo $email;
                        }
                        else{
                            echo "User";
                        }
                        ?>
					</span>
                <span class="txt3">
					Identity Management has shared this information with us:
					</span>
                <div>
						<span class="userdatatxt">
							Email: <?php echo $email;?><br>
						</span>
                    <span class="userdatatxt">
							First Name: <?php echo $first;?><br>
						</span>
                    <span class="userdatatxt">
							Last Name: <?php echo $last;?><br>
						</span>
                </div>
            </form>
        </div>
    </div>
</div>

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
</body>
</html>
