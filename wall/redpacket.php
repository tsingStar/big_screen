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

$load->model('Flag_model');
//获取签到并通过审核人数
$flag_count=$load->flag_model->getShenheCount();
//获取最后签到的6个人
$flags=$load->flag_model->getRecentSignedUsers(6);


$load->model('Plugs_model');
$plugs=$load->plugs_model->getPlugs(1);

$load->model('Redpacket_model');
$load->redpacket_model->getCurrentRound();

//活动从前到后顺序进行
$currentredpacket_round=$load->redpacket_model->getCurrentRound();

$smarty = new Smarty;
$smarty->caching = false;
$apppath=str_replace(DIRECTORY_SEPARATOR.'wall', '', dirname(__FILE__));
$smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
$smarty->assign('from','redpacket');
$smarty->assign('wall_config',$wall_config);
$smarty->assign('currentredpacket_round',json_encode($currentredpacket_round));
$smarty->assign('erweima',$weixin_config['erweima']);
$smarty->assign('flag_count',$flag_count);
$smarty->assign('flags',$flags);
$smarty->assign('plugs',$plugs);
$smarty->display('themes/'.$style.'/header.html');
$smarty->display('themes/'.$style.'/redpacket.html');
$smarty->display('themes/'.$style.'/footer.html');