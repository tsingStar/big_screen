<?php
require_once('common.php');
require_once('../Modules/Menu/Controllers/Api.php');
require_once('../Modules/Menu/Models/Menu_model.php');
use Modules\Menu\Controllers\Api;

$load->model('Plugs_model');
$plugs=$load->plugs_model->getPlugs(1);

$openid=$_GET['rentopenid'];

$load->model('Flag_model');

$myinfo=$load->flag_model->getUserinfo($openid,true);

//模版页面相关内容

$smarty = new Smarty;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->compile_dir = COMPILEPATH;
$smarty->assign('title','签到');
$smarty->assign('wall_config',$wall_config);
if($myinfo['flag']==1){//没有签到
	$fromurl=isset($_GET['fromurl'])?strval($_GET['fromurl']):'';
		
	$smarty->assign('openid',$openid);
	$smarty->assign('user',$myinfo);
	$load->model('System_Config_model');
	//手机端背景图
	$data=$load->system_config_model->get("mobileqiandaobg");
	$load->model('Attachment_model');
	$mobileqiandaobg=$load->attachment_model->getById(intval($data['configvalue']));
	if(!$mobileqiandaobg){
		$mobileqiandaobg='template/app/images/bg.jpg';
	}else{
		$mobileqiandaobg=empty($mobileqiandaobg['filepath'])?'template/app/images/bg.jpg':$mobileqiandaobg['filepath'];
	}
	$smarty->assign('mobileqiandaobg',$mobileqiandaobg);
	//签到姓名
	$data=$load->system_config_model->get("qiandaosignname");
	$qiandaosignname=intval($data['configvalue']);
	$smarty->assign('qiandaosignname',$qiandaosignname);
	//签到手机号
	$data=$load->system_config_model->get("qiandaophone");
	$qiandaophone=intval($data['configvalue']);
	$smarty->assign('qiandaophone',$qiandaophone);
	
	$load=Loader::getInstance();
	$load->model('Flag_model');
	$columns=$load->flag_model->getExtentionColumns();
	foreach($columns as $k=>$v){
		if($v['coltype']=='select'){
			$columns[$k]['options_arr']=unserialize($v['options']);
		}
	}
	$smarty->assign('diycolumns',$columns);
	$smarty->assign('erweima',$weixin_config['erweima']);
	$smarty->assign('plugs',$plugs);
	$smarty->assign('redirecturl',urldecode($fromurl));
	$smarty->display('template/app_header.html');
	$smarty->display('template/app_register.html');
	$smarty->display('template/app_footer.html');
}else{//完成签到
	$fromurl=isset($_GET['fromurl'])?strval($_GET['fromurl']):'';
	if(!empty($fromurl)){
		header('location:'.urldecode($fromurl));
		return;
	}
	$smarty->assign('erweima',$weixin_config['erweima']);
	$myinfo['datetime']=date('Y-m-d H:i:s',$myinfo['datetime']);
	$menu_api=new Api();
	$custommenu=$menu_api->getAll(array('rentopenid'=>$openid));
	$load->model('System_Config_model');
	$data=$load->system_config_model->get("mobileqiandaobg");
	$load->model('Attachment_model');
	$mobileqiandaobg=$load->attachment_model->getById(intval($data['configvalue']));
	if(!$mobileqiandaobg){
		$mobileqiandaobg='template/app/images/bg.jpg';
	}else{
		$mobileqiandaobg=empty($mobileqiandaobg['filepath'])?'template/app/images/bg.jpg':$mobileqiandaobg['filepath'];
	}
	$menucolor=$load->system_config_model->get("mobilemenufontcolor");
	// echo $mobileqiandaobg;
	$smarty->assign('mobileqiandaobg',$mobileqiandaobg);
	$menucolor['configvalue']=isset($menucolor['configvalue'])?$menucolor['configvalue']:'#000000';
	$smarty->assign('menucolor',$menucolor['configvalue']);
	$smarty->assign('custommenu',$custommenu);
	$smarty->assign('openid',$openid);
	$smarty->assign('user',$myinfo);
	$smarty->assign('plugs',$plugs);
	
	$smarty->display('template/app_header.html');
	$smarty->display('template/app_qd.html');
}



