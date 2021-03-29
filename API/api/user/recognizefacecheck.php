<?php

require("../../factories/FaceFactory.php");
$f = new FaceFactory();
echo json_encode($f->testFaceRecognition($_REQUEST));