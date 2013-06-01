<?php

define("SITE",$_SERVER['HTTP_HOST']); // template.ru
define("DOC_ROOT", $_SERVER['DOCUMENT_ROOT']); // /home/template.ru/www/

//mysql
define("MYSQL_SERVER","localhost");
define("MYSQL_LOGIN","FirstBase");
define("MYSQL_PASS","123");
define("MYSQL_DB","test");
define("MYSQL_PREFIX","ts");
define("MYSQL_PREFIX_DATA","data");
define("MYSQL_PREFIX_REL","rel");
define("MYSQL_INIT_CONNECT", "set names utf8");

spl_autoload_register ('autoload');

function autoload ($className) {
    $file=DOC_ROOT.'/classes/class_'.$className.'.php';
    if (file_exists($file)) {
        $fileName=DOC_ROOT.'/classes/class_'.$className.'.php';
    } else {
        $fileName=DOC_ROOT.'/classes/interface_'.$className.'.php';
    }
    include_once $fileName;
}

?>
