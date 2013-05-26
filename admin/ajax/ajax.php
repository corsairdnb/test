<?php

header('Content-type: text/json');
include_once("../../classes/config.php");

sleep(1);

global $response;
$response=array("status"=>"","content"=>"");

if (!empty($_POST['json'])) {
    $json=str_replace("\n","",$_POST['json']);
}

if (!empty($json)) {
    $object=new Ajax($json);
    if (!$object->param("type")) {
        setStatus("no type");
        echo json_encode($response);
        die();
    }
    else {
        //$response['action']=$object->param("action");
        if ($object->create()) {
            setStatus("true");
            $response['content']=$object->param("type")." ".$object->param("name");
        } else {
            setStatus("not created");
        }
        echo json_encode($response);
    }
} else {
    setStatus("no json");
    echo json_encode($response);
}

function setStatus ($status) {
    global $response;
    $response['status']=$status;
}