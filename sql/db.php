<?php
$datas = array();
//公众号相关
// echo dirname(__FILE___) ;
// echo IA_ROOT;
$path=IA_ROOT.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'db.sql';
$sql=file_get_contents($path);
//去除bom头
$sql=trim($sql, "\xEF\xBB\xBF");
$sql=str_replace("\r\n", "\r", $sql);
$sql=str_replace("\n", "\r", $sql);
$sql=str_replace(";\r", ";|", $sql);
$datas[] = $sql;
$dat = array();
$dat['datas'] = $datas;
return $dat;