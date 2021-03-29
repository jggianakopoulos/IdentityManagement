<?php

require("../../factories/UserFactory.php");
$uf = new UserFactory();
echo json_encode($uf->considerProfileUpdate($_REQUEST));
