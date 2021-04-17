<?php

require('../../factories/TokenFactory.php');
$f = new UserFactory();

echo json_encode($f->passwordCheck($_REQUEST));
?>
