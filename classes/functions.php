<?php

require_once("config.php");
require_once("class_Mysql.php");

$mysql = new Mysql();

function getQuestion ($id) {
    global $mysql;
    $ar = $mysql->sql_query_get("
            SELECT * FROM `ts_data_question` WHERE `id`='$id'
          ");
    return $ar;
}

?>