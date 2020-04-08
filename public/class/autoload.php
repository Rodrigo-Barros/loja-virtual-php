<?php 

ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);

spl_autoload_register(function ($ClassName) {
    if($ClassName != "Database"){
        include(__DIR__ . "/" . $ClassName . ".php");
    }else{
        include(__DIR__."../../../connection.php");
    }
});

?>