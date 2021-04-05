<?php
    require("factories/TokenFactory.php");
    $f = new TokenFactory();
    $data =  $f->getDataFromToken($_REQUEST);
    if ($data["permission_firstname"] == 1) {
        $first = $data["first_name"];
    } else {
        $first = "";
    }
    if ($data["permission_lastname"] == 1) {
        $last = $data["last_name"];
    } else {
        $last = "";
    }
    if ($data["permission_email"] == 1) {
        $email = $data["email"];
    } else {
        $email = "";
    }
?>

<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="assets/styles/bootstrap.css">
<link rel="stylesheet" type="text/css" href="assets/styles/util.css">
<link rel="stylesheet" type="text/css" href="assets/styles/main.css">
<head>i
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
							Email:
							<?php
                            if ($email != ''){
                                echo $email;
                            }
                            else{
                                echo 'None';
                            }
                            ?><br>
						</span>
                    <span class="userdatatxt">
							First Name:
							<?php
                            if ($first != ''){
                                echo $first;
                            }
                            else{
                                echo 'None';
                            }
                            ?><br>
						</span>
                    <span class="userdatatxt">
							Last Name:
							<?php
                            if ($last != ''){
                                echo $last;
                            }
                            else{
                                echo 'None';
                            }
                            ?><br>
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
