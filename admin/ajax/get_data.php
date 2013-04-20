<?php

header('Content-type: text/json');
include_once("../../classes/config.php");

sleep(1);

global $response;
$response=array("status"=>"","type"=>"","content"=>"");

if (!empty($_POST['json'])) {
    $object=new Ajax($_POST['json']);
    if (!$object->param("type")) {
        setStatus("no type");
        echo json_encode($response);
        die();
    }
    else {
        if ($x=$object->get()) {
            setStatus("true");
            $response['type']=$object->param("type");
            $response['content']=$x;
        } else {
            setStatus("nothing to get");
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