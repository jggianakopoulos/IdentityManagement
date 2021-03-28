<?php

require("../../factories/TokenFactory.php");

$f = new TokenFactory();
return json_encode($f->attemptRetrieveAccessToken($_REQUEST));