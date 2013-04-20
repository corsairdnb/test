<?php

include_once("../classes/config.php");

$modules=new Modules;
$response=$modules->getMenu();
$li='';
foreach ($response as $item=>$val) {
    $li.='<li><span id="';
    $li.=$item;
    $li.='">';
    $li.=$val;
    $li.='</span></li>';
    echo $li;
    $li='';
}

?>