<?php
@header("Content-type: text/html; charset=utf-8");
include(dirname(__FILE__) . '/../common/db.class.php');
$wall_config_m=new M('wall_config');
$wall_config=$wall_config_m->find('1 limit 1');

$smarty = new Smarty;
$smarty->caching = false;
$apppath=str_replace(DIRECTORY_SEPARATOR.'myadmin', '', dirname(__FILE__));
$smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
$smarty->assign('wall_config',$wall_config);
$smarty->display('templates/login.html');