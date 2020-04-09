<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
require_once(dirname(__FILE__) . '/../common/function.php');
$style='meepo';

$load->model('Wall_model');
$wall_config=$load->wall_model->getConfig();
$load->model('Weixin_model');
$weixin_config=$load->weixin_model->getConfig();




$smarty = new Smarty;
$smarty->caching = false;
$apppath=str_replace(DIRECTORY_SEPARATOR.'wall', '', dirname(__FILE__));
$smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
$smarty->assign('from','wall');
$smarty->assign('qd_maxid',$qd_maxid);
$smarty->assign('wall_config',$wall_config);
$smarty->assign('personJson',json_encode($flag));
$smarty->assign('erweima',$weixin_config['erweima']);
$smarty->assign('signlogoimg',$wall_config['signlogoimg']);

$smarty->display('themes/'.$style.'/header.html');
$smarty->display('themes/'.$style.'/wall.html');
$smarty->display('themes/'.$style.'/footer.html');