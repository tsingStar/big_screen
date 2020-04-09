<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
require_once(dirname(__FILE__) . '/../common/function.php');
// if (!isset($_SESSION['views']) || $_SESSION['views'] != true) {
// 	$_SESSION['views'] = false;
// 	echo "<script>window.location='../wall/index.php?url=" . $_SERVER['PHP_SELF'] . "';</script>";
// 	die;
// }
$style='meepo';

$load->model('Wall_model');
$wall_config=$load->wall_model->getConfig();
$load->model('Weixin_model');
$weixin_config=$load->weixin_model->getConfig();


$xiangce_m=new M('xiangce');
$xiangce=$xiangce_m->select('1');

$load->model('Attachment_model');
foreach($xiangce as $k=>$v){
	$img=$load->attachment_model->getById($v['imagepath']);
	$xiangce[$k]['imagepath']=$img['filepath'];
}
$smarty = new Smarty;
$smarty->caching = false;
$apppath=str_replace(DIRECTORY_SEPARATOR.'wall', '', dirname(__FILE__));
$smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
$smarty->assign('from','qiandao');
$smarty->assign('wall_config',$wall_config);
$smarty->assign('erweima',$weixin_config['erweima']);
$smarty->assign('xiangce',$xiangce);
$smarty->display('themes/'.$style.'/header.html');
$smarty->display('themes/'.$style.'/xiangce.html');
$smarty->display('themes/'.$style.'/footer.html');