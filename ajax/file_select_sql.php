<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dmitry Rychkov
 * Date: 04.07.12
 * Time: 14:00
 * To change this template use File | Settings | File Templates.
 */
header('Content-type: text/plain');

require_once('../../modules/config.php');
require_once('../../modules/suslik/mod_mysql.php');

if (isset($_POST['id'])&&(int)$_POST['id']&&isset($_POST['filename'])) {
    $id=$_POST['id'];
    $filename=$_POST['filename'];
    echo $id;
    $query = "SELECT * FROM `af_catalog_images` WHERE `item_id`=$id";
    $res = sql_query($query) or die(mysql_error());
    /*if (mysql_numrows($res)>0) {
        $query = "UPDATE `af_catalog_images` SET `filename`='".$filename."' WHERE `item_id`='".$id."'";
        sql_query($query);
    } else {*/
        $query = "INSERT INTO `af_catalog_images` SET `item_id`='".$id."', `priority`='10', `filefolder`='/userfiles/af_catalog_images/', `filename`='".$filename."', `alt`=''";
        sql_query($query);

}

?>