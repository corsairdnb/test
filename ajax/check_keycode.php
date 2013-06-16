<?php

header('Content-type: text/plain');
session_start();
require_once("../classes/config.php");
require_once("../classes/class_Mysql.php");

(isset($_POST['key'])) ? $key=trim($_POST['key']) : die(false);
(isset($_POST['test']) && (int)$_POST['test']) ? $test=trim($_POST['test']) : die(false);
sleep(1);

$mysql = new Mysql();

if ($mysql->sql_test_isActive($key,$test)) {
    echo 'true';
}
else {
    echo 'false';
}