<?php

session_start();
require_once("classes/config.php");
require_once("classes/functions.php");

if (empty($_SESSION['sid'])) {
    $_SESSION['sid'] = uniqid(rand(), true);
} else {

}

$test=new Test();

include("tpl/index.html");

?>