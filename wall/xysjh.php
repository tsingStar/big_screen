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


$flag_m=new M('flag');
$flag=$flag_m->select('phone is not NULL and phone !="" limit 50');

$personlist=array();
foreach($flag as $item){
	$personlist[]['mobile']=substr_replace($item['phone'],'****',3,4);
}
$xingyunshoujihao_m=new M('xingyunshoujihao');
$maxordernum=$xingyunshoujihao_m->find('status=2','*','count');
//当前的获奖序号
$currentordernum=$maxordernum+1;
//排除已经中奖 或者内定不中当前奖项的
$personcount=$flag_m->find(' flag=2 and weixin_flag.status=1 and openid not in(select openid from  weixin_xingyunshoujihao where  weixin_xingyunshoujihao.status=2  or (ordernum='.$currentordernum.' and designated=3))','*','count');

$smarty = new Smarty;
$smarty->caching = false;
$apppath=str_replace(DIRECTORY_SEPARATOR.'wall', '', dirname(__FILE__));
$smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
// $smarty->assign('qd_maxid',$qd_maxid);
$smarty->assign('from','qiandao');
$smarty->assign('wall_config',$wall_config);
$smarty->assign('personcount',$personcount);
$smarty->assign('personJson',json_encode($personlist));
$smarty->assign('erweima',$weixin_config['erweima']);
// $smarty->assign('signlogoimg',$wall_config['signlogoimg']);
$smarty->display('themes/'.$style.'/header.html');
$smarty->display('themes/'.$style.'/xysjh.html');
$smarty->display('themes/'.$style.'/footer.html');