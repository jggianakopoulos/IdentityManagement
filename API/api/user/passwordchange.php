<?php
require("../../factories/UserFactory.php");

$uf = new UserFactory();
echo json_encode($uf->considerPasswordChange($_REQUEST));

?>
