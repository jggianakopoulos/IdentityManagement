<?php

session_start();
session_unset();
header("Location: dev_login.php");