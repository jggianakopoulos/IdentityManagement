<?php

require('../../factories/TokenFactory.php');
$f = new TokenFactory();

echo json_encode($f->attemptCreateToken($_REQUEST));
?>