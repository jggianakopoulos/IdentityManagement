<?php
require("../../factories/UserFactory.php");

$uf = new UserFactory();
echo json_encode($uf->considerLogin($_REQUEST));

?>
