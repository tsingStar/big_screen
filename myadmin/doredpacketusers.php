<?php 
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/CacheFactory.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'genscript':
		genscript();
		break;
	
}



function genscript(){
	$roundid=isset($_GET['roundid'])?intval($_GET['roundid']):0;
	if($roundid<=0){
		echo '//无数据';
		return;
	}

	$load=Loader::getInstance();
	$load->model('Weixin_model');
	$weixin_config=$load->weixin_model->getConfig();
	if(empty($weixin_config['appid'])){
		echo '//没有填写商户平台对应的公众号的appid';
		return;
	}
	$script=$weixin_config['appid']."\r\n";
	$redpacketdata=array();
	$load->model('Redpacket_model');
	//未发
	$winners=$load->redpacket_model->getRedpacketUserinfoByStatus(1,$roundid);
	foreach($winners as $v){
		if(isset($redpacketdata[$v['openid']])){
			$redpacketdata[$v['openid']]=$redpacketdata[$v['openid']]+$v['totalmoney'];
		}else{
			$redpacketdata[$v['openid']]=$v['totalmoney'];
		}
		
	}
	//发放中，卡住
	$winners=$load->redpacket_model->getRedpacketUserinfoByStatus(2,$roundid);
	foreach($winners as $v){
		if(isset($redpacketdata[$v['openid']])){
			$redpacketdata[$v['openid']]=$redpacketdata[$v['openid']]+$v['totalmoney'];
		}else{
			$redpacketdata[$v['openid']]=$v['totalmoney'];
		}
	}
	//发放失败
	$winners=$load->redpacket_model->getRedpacketUserinfoByStatus(4,$roundid);
	foreach($winners as $v){
		if(isset($redpacketdata[$v['openid']])){
			$redpacketdata[$v['openid']]=$redpacketdata[$v['openid']]+$v['totalmoney'];
		}else{
			$redpacketdata[$v['openid']]=$v['totalmoney'];
		}
	}
	foreach($redpacketdata as $k=>$v){
		$script.=$k.' '.sprintf("%.2f",($v/100))."\r\n";
	}
	echo $script;
	return;
}
