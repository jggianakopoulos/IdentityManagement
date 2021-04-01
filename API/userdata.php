<?php

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
?>
<!DOCTYPE html>
<html style="background-color:grey;">
    <head>
        <script>
            console.log(<?php echo json_encode($data);?>);
        </script>
    </head>
    <body>
    <div style="width: 1000px;text-align: center;margin: auto;display: flex;flex-direction: column;justify-content: center;">
        <h1>Welcome! Your information is displayed below.</h1>
        <div style="display: flex;margin: 10px 75px;flex-direction: row;">
            <div style="width: 500px;">Email</div>
            <div><?php echo $email; ?></div>
        </div>
        <div style="display: flex;margin: 10px 75px;flex-direction: row;">
            <div style="width: 500px;">First Name</div>
            <div><?php echo $first; ?></div>
        </div>
        <div style="display: flex;margin: 10px 75px;flex-direction: row;">
            <div style="width: 500px;">Last Name</div>
            <div><?php echo $last; ?></div>
        </div>
    </div>
    </body>
</html>