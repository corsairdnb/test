<?php

header('Content-type: text/json');
include_once("../classes/config.php");

sleep(1);

global $response;
$response=array("status"=>"","type"=>"","content"=>"");

if (!empty($_POST['json'])) {
    $json=str_replace("\n","",$_POST['json']);
}

if (!empty($json)) {

    $object=new Ajax($json);
    if ($action = $object->param("action")) {
        switch ($action) {
            case ("create"):
                if ($object->create()) {
                    setStatus("true");
                    break;
                } else continue;
            case ("update"):
                if ($object->update()) {
                    setStatus("true");
                    break;
                } else continue;
            case "delete":
                if ($object->remove()) {
                    setStatus("true");
                    break;
                } else continue;
            case "getData":
                if ($content=$object->get()) {
                    $response['type']=$object->param("type");
                    $response['content']=$content;
                    setStatus("true");
                    break;
                } else continue;
            case "getForm":
                if ($content=$object->getForm()) {
                    $response['content']=$content;
                    setStatus("true");
                    break;
                } else continue;
            default:
                setStatus("error");
                die();
        }
    }
    else {
        setStatus("no action");
    }
}
else {
    setStatus("no json");
}

echo json_encode($response);



function setStatus ($status) {
    global $response;
    $response['status']=$status;
}