<?php
require_once('error_reporting.php');

session_start();
unset($_SESSION['user']);
header("Location: /index.php");