<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dmitry Rychkov
 * Date: 03.07.12
 * Time: 17:40
 * To change this template use File | Settings | File Templates.
 */
header('Content-type: text/json');

$dir="../../userfiles/af_catalog_images/lit";
$files_arr=scandir($dir);
$ar=array();
foreach ($files_arr as $file) {
    $file="$dir/$file";
    $rev=strrev($file);
    list($type, $name)=explode(".", $rev);
    $name=strrev($name);
    $type=strrev($type);
    $filename=$name.".".$type;
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) array_push($ar,$filename);
}
unset($ar[0]);
echo json_encode($ar);

?>