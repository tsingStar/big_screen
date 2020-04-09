<?php
@header("Content-type: text/html; charset=utf-8");
require_once('../common/session_helper.php');
require_once(dirname(__FILE__) . '/../common/function.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'save_rule':
		save_rule();
		break;
	case 'save_tips':
		save_tips();
		break;
	case 'save_sendname':
		save_sendname();
		break;
	case 'save_wishing':
		save_wishing();
		break;
}
function save_wishing(){
	$wishing=isset($_POST['wishing'])?strval($_POST['wishing']):'';
	$returnmessage=saveRedpacketConfig('wishing',$wishing);
	echo $returnmessage;
	return;
}

function save_sendname(){
	$sendname=isset($_POST['sendname'])?strval($_POST['sendname']):'';
	$returnmessage=saveRedpacketConfig('sendname',$sendname);
	echo $returnmessage;
	return;
}
function save_tips(){
	$tips=isset($_POST['tips'])?strval($_POST['tips']):'';
	$returnmessage=saveRedpacketConfig('tips',$tips);
	echo $returnmessage;
	return;
}

function save_rule(){
	$rule=isset($_POST['rule'])?strval($_POST['rule']):'';
	$returnmessage=saveRedpacketConfig('rule',$rule);
	echo $returnmessage;
	return;
}

function saveRedpacketConfig($key,$val){
	$data=array($key=>$val);
	$load=Loader::getInstance();
	$load->model('Redpacket_model');
	$result=$load->redpacket_model->updateRedacketConfig($data);
	if($result){
		return returnmsg(1,'修改成功');
	}else{
		return returnmsg(-1,'修改失败');
	}
}