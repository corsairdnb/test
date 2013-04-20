<?php

header('Content-type: text/plain');

(isset($_POST['key']))?$key=trim($_POST['key']):die(false);
(isset($_POST['subject'])&&(int)$_POST['subject'])?$subject=trim($_POST['subject']):die(false);
sleep(2);
if (strlen($key)==8&&$key=="12345678") {
    /*$query = "SELECT * FROM `af_catalog_images` WHERE `item_id`=$id";
    $res = sql_query($query) or die(mysql_error());*/
    /*if (mysql_numrows($res)>0) {
        $query = "UPDATE `af_catalog_images` SET `filename`='".$filename."' WHERE `item_id`='".$id."'";
        sql_query($query);
    } else {*/
    /*$query = "INSERT INTO `af_catalog_images` SET `item_id`='".$id."', `priority`='10', `filefolder`='/userfiles/af_catalog_images/', `filename`='".$filename."', `alt`=''";
    sql_query($query);*/
    echo 'true';
}
else {
    echo 'false';
}