<?php

session_start();
require_once("classes/config.php");
require_once("classes/functions.php");

$user=new User();
$test=new Test();
$question=new Question();
$answer=new Answer();

$userData = $user->data;

include("tpl/test.html");

?>