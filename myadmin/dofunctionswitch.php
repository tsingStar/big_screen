<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
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
	case 'sethotkey':
		sethotkey();
		break;
}


//各个功能的开关
function sethotkey(){
	$plugname=isset($_POST['plugname'])?strval($_POST['plugname']):'';
	$plugname=str_replace('hotkey_','',$plugname);
	$hotkey=isset($_POST['hotkey'])?strval($_POST['hotkey']):'';
	if($hotkey!=''){
		$hotkey='ctrl+'.$hotkey;
	}
	if($plugname==''){
		$resultdata=array('code'=>-2,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}


	$load=Loader::getInstance();
	$load->model('Plugs_model');
	$result=$load->plugs_model->setHotkey($plugname,$hotkey);

	if($result){
		$resultdata=array('code'=>1,'message'=>'修改成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-1,'message'=>'修改失败');
		echo json_encode($resultdata);
		return;
	}
}
