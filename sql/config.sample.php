<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
@header("Content-type: text/html; charset=utf-8");
define('SYSTEMPATH',str_replace('data', '', dirname(__FILE__)));
$host=strtolower($_SERVER['HTTP_HOST']);
$configfile=__DIR__.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR.$host.'.php';
if(file_exists($configfile)){
    require $configfile;
}else{
    if(strpos($host,'woyaohudong.com')!==false){
        header('Location:https://manage.woyaohudong.com/end');
        exit();
    }
}