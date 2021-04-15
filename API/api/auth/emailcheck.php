<?php

require('../../factories/UserFactory.php');
$f = new UserFactory();

echo json_encode($f->emailCheck($_REQUEST));
?>
