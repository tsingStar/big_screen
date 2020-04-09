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
$flag=$flag_m->select(' status=1 and flag=2 ');
$womenlist=array();
$menlist=array();
foreach($flag as $item){
	if($item['sex']==2){//女
		$womenlist[]=formatpersonitem($item);
	}else{//男
		$menlist[]=formatpersonitem($item);
	}
}

$smarty = new Smarty;
$smarty->caching = false;
$apppath=str_replace(DIRECTORY_SEPARATOR.'wall', '', dirname(__FILE__));
$smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
$smarty->assign('from','qiandao');
$smarty->assign('wall_config',$wall_config);
$smarty->assign('women',$womenlist);
$smarty->assign('men',$menlist);
$smarty->assign('erweima',$weixin_config['erweima']);
$smarty->display('themes/'.$style.'/header.html');
$smarty->display('themes/'.$style.'/ddp.html');
$smarty->display('themes/'.$style.'/footer.html');

function formatpersonitem($person){
	$newperson=array();
	$newperson['id']=$person['id'];
	$newperson['avatar']=$person['avatar'];
	$newperson['nick_name']=pack('H*', $person['nickname']);//$person['nickname'];
	return $newperson;
}