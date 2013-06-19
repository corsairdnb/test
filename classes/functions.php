<?php

require_once("config.php");
require_once("class_Mysql.php");

$mysql = new Mysql();

function getQuestion ($params) {
    global $mysql;
    $question_id = $params["question_id"];
    $test_id = $params["test_id"];
    $user_id = $params["user_id"];
    $q = "  SELECT `ts_data_question`.*, `ts_data_question_answer`.answer
            FROM `ts_data_question`, `ts_data_question_answer`
            WHERE `ts_data_question`.`id`='$question_id'
            AND `ts_data_question_answer`.`id`='$question_id'";
    $ar = $mysql->sql_query_get($q);
        /*$q3 = "SELECT * FROM `ts_data_user_answer` WHERE `user_id`=$user_id AND `test_id`=$test_id AND `question_id`=$question_id";
        if ($mysql->sql_query_get($q3)) {
            $ar[0]["complete"]=true;
        }*/
    $answer = $ar[0]["answer"];
    $answer = explode(".",$answer);
    $answer = array_filter( $answer, function($el) {return !empty($el);} );
    $or = "";
    foreach ($answer as $key => $val) {
        $or .= "`id`=".$val." OR ";
    }
    $or = substr($or, 0, -3);
    $q2 = "SELECT * FROM `ts_data_answer` WHERE $or";
    $ar_2 = $mysql->sql_query_get($q2);
    $result["question"] = $ar;
    $result["answer"] = $ar_2;
    return $result;
}

?>