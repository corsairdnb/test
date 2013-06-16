<?php

header('Content-type: text/json');
include_once("../classes/config.php");
include_once("../classes/functions.php");

//sleep(1);

global $response;
$response=array("status"=>"","content"=>"");

if (!empty($_POST['json'])) {
    $json=json_decode(str_replace("\n","",$_POST['json']),true);
}

if (!empty($json)) {
    if ($action = $json["action"]) {
        $params = $json["params"];
        switch ($action) {
            case ("getQuestion"):
                if ($response['content'] = getQuestion($params["question_id"])) {
                    setStatus("true");
                    break;
                } else {
                    setStatus("false");
                    break;
                }
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

?>