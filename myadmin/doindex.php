<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/function.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
require_once dirname(__FILE__).'/../Facades/ActivityFacade.php';
use Facades\ActivityFacade;
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'switchplugs':
		switchplugs();
		break;
	case 'changescreenpaw':
		changescreenpaw();
		break;
	case 'cleardata':
		cleardata();
		break;
	case 'copyright':
		copyright();
		break;
	case 'copyrightlink':
		copyrightlink();
		break;
	case 'changepwd':
		changepwd();
		break;
	case 'resetmobileurl':
		resetmobileurl();
		break;
}

function resetmobileurl(){
	$load=Loader::getInstance();
	$load->model('Wall_model');
	$verifycode=uniqid();
	$data=array('verifycode'=>$verifycode);
	$result=$load->wall_model->setConfig($data);
	if($result){
		$resultdata=array('code'=>1,'message'=>'修改成功','vcode'=>$verifycode);
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-1,'message'=>'修改失败');
		echo json_encode($resultdata);
		return;
	}
}

//各个功能的开关
function switchplugs(){
	$plugname=isset($_POST['name'])?strval($_POST['name']):'';
	if($plugname==''){
		$resultdata=array('code'=>-2,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	$switchstatus=isset($_POST['switchstatus'])?intval($_POST['switchstatus']):0;
	$switchstatus=$switchstatus==0?2:1;

	$load=Loader::getInstance();
	$load->model('Plugs_model');
	$result=$load->plugs_model->switchPlug($plugname,$switchstatus);

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
//修改开场密码
function changescreenpaw(){
	$screenpaw=isset($_POST['screenpaw'])?$_POST['screenpaw']:'';
	if($screenpaw==''){
		$resultdata=array('code'=>-1,'message'=>'开场密码不能为空');
		echo json_encode($resultdata);
		return;
	}

	$load=Loader::getInstance();
	$load->model('Wall_model');
	$data=array('screenpaw'=>$screenpaw);
	$result=$load->wall_model->setConfig($data);

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
//清空上墙系统的所有数据，包括 签到用户信息，上墙的消息，摇一摇数据，中奖名单
function cleardata(){
	$activityfacade=new ActivityFacade();
	$activityfacade->reset();
	$resultdata=array('code'=>1,'message'=>'微信墙已经焕然一新，可以开始一场新的活动了');
	echo json_encode($resultdata);
	return;
}
//修改管理员密码
function changepwd(){
	$oldpwd=isset($_POST['oldpwd'])?strval($_POST['oldpwd']):'';
	$newpwd=isset($_POST['newpwd'])?strval($_POST['newpwd']):'';
	$validpwd=isset($_POST['validpwd'])?strval($_POST['validpwd']):'';
	if(empty($oldpwd) || empty($newpwd) || empty($validpwd)){
		$resultdata=array('code'=>-1,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	if($newpwd!=$validpwd){
		$resultdata=array('code'=>-2,'message'=>'2次输入的新密码不一致，请重新输入');
		echo json_encode($resultdata);
		return;
	}
	$admin_m=new M('admin');
	$admin=$admin_m->find('1');
	if($admin['pwd']!=$oldpwd){
		$resultdata=array('code'=>-3,'message'=>'原密码错误');
		echo json_encode($resultdata);
		return;
	}
	
	$return=$admin_m->update(' 1 ', array('pwd'=>$newpwd));
	if($return!==false){
		$resultdata=array('code'=>1,'message'=>'修改成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-4,'message'=>'修改失败');
		echo json_encode($resultdata);
		return;
	}
}


function copyright(){
	$copyright=isset($_POST['copyright'])?$_POST['copyright']:'';
	$load=Loader::getInstance();
	$load->model('Wall_model');
	$data=array('copyright'=>$copyright);
	$result=$load->wall_model->setConfig($data);

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
function copyrightlink(){
	$copyrightlink=isset($_POST['copyrightlink'])?$_POST['copyrightlink']:'';

	$load=Loader::getInstance();
	$load->model('Wall_model');
	$data=array('copyrightlink'=>$copyrightlink);
	$result=$load->wall_model->setConfig($data);

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

