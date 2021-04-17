<?php

require('../../factories/LoginCodeFactory.php');
$f = new LoginCodeFactory();

echo json_encode($f->generateLoginCode($_REQUEST));
?>
