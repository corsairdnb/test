<?php

header('Content-type: text/json');
include_once("../../classes/config.php");

sleep(1);

global $response;
$response=array("status"=>"");

if (!empty($_POST['json'])) {
    $object=new Ajax($_POST['json']);
    if (!$object->param("type")) {
        setStatus("no type");
        echo json_encode($response);
        die();
    } elseif (!$object->param("i")) {
        setStatus("no id");
        echo json_encode($response);
        die();
    }
    else {
        if ($x=$object->remove()) {
            setStatus("true");
        } else {
            setStatus("error deleting");
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