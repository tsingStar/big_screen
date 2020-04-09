<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'db.class.php');
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'session_helper.php');
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'flag_model.php');
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'function.php');
require_once '..'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'ActivityFacade.php';
use Facades\ActivityFacade;
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'setband':
		setband();
	break;	
}

//设置审核状态
function setband(){
	$openid=isset($_POST['openid'])?strval($_POST['openid']):'';
	$status=isset($_POST['status'])?intval($_POST['status']):0;
	if($openid==''){
		echo returnmsg(-1,"用户openid格式不正确");
		return;
	}
	if($status==0){
		echo returnmsg(-2,"设置的状态不正确");
		return;
	}
	if($status==3){
		deletesign();
		return;
	}
	$flag_model=new Flag_model();
	$userinfo=$flag_model->getUserinfo($openid);
	$oldstatus=$userinfo['status'];
	unset($userinfo['id']);
	$userinfo['status']=$status;
	// echo var_export($userinfo);
	unset($userinfo['nickname']);
	if($status==1 && empty($userinfo['signorder'])){
		$userinfo['signorder']=$flag_model->getMaxSignorder()+1;
	}
	$result=$flag_model->saveUserinfo($userinfo);

	if($result){
		//从审核通过切换到审核不通过
		echo returnmsg(1,"用户状态修改成功");
		return;
	}else{
		echo returnmsg(-3,"设用户状态修改失败");
		return;
	}
}
/**
 * 删除签到记录
 */
function deletesign(){
	$openid=isset($_POST['openid'])?strval($_POST['openid']):'';
	$activityfacade=new ActivityFacade();
	$activityfacade->deleteUser($openid);
	echo returnmsg(2,"该用户删除成功");
	return ;
}