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




$id=isset($_GET['id'])?intval($_GET['id']):0;

$load->model('Vote_model');
$vote_config=$load->vote_model->getCurrentVoteConfig($id);

$smarty = new Smarty;
$smarty->caching = false;
$apppath=str_replace(DIRECTORY_SEPARATOR.'wall', '', dirname(__FILE__));
$smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;

$smarty->assign('from','vote');
$smarty->assign('wall_config',$wall_config);
$smarty->assign('erweima',$weixin_config['erweima']);
$smarty->assign('preid',$vote_config['previd']);
$smarty->assign('nextid',$vote_config['nextid']);
$smarty->assign('vote_config',$vote_config);
$smarty->display('themes/'.$style.'/header.html');
$smarty->display('themes/'.$style.'/vote.html');
$smarty->display('themes/'.$style.'/footer.html');

