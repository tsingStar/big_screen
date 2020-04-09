<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'lottery_model.php');
$action=$_GET['action'];
switch ($action){
	case 'fj':
		fajiang();
		break;
	case 'del':
		delzj();
		break;
	case 'delzjdesignated':
		delzjdesignated();
		break;
	case 'clearzjlist':
		clearzjlist();
		break;
}
//删除当前抽奖类型的中奖记录，不能删除内定信息
function clearzjlist(){
	$currentplug=isset($_POST['plug'])?strval($_POST['plug']):'';
	if(empty($currentplug)){
		$resultdata=array('code'=>-2,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	
	$lottery_model=new Lottery_model();
	$lottery_model->deleteZjrecord($currentplug);

	$resultdata=array('code'=>1,'message'=>'中奖记录已经删除');
	echo json_encode($resultdata);
	return;
}
//发奖
function fajiang(){
	$openid=isset($_POST['openid'])?strval($_POST['openid']):'';
	$currentplug=isset($_POST['plug'])?strval($_POST['plug']):'';
	if(empty($openid) || empty($currentplug)){
		$resultdata=array('code'=>-2,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	$zjlist_m=new M('zjlist');
	$data=array('status'=>3,'fjdatetime'=>time());
	$result=$zjlist_m->update('openid="'.$openid.'" and fromplug="'.$currentplug.'"',$data);
	if($result){
		$resultdata=array('code'=>1,'message'=>'发奖成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-1,'message'=>'发奖失败');
		echo json_encode($resultdata);
		return;
	}
	
}
//删除中奖信息
function delzj(){
	$openid=isset($_POST['openid'])?strval($_POST['openid']):'';
	$currentplug=isset($_POST['plug'])?strval($_POST['plug']):'';
	if(empty($openid) || empty($currentplug)){
		$resultdata=array('code'=>-2,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	$lottery_model=new Lottery_model();
	$lottery_model->deleteZjrecord($currentplug,0,$openid);
	$resultdata=array('code'=>1,'message'=>'删除成功');
	echo json_encode($resultdata);
	return;

}


//删除中奖信息
function delzjdesignated(){
	$openid=isset($_POST['openid'])?strval($_POST['openid']):'';
	$currentplug=isset($_POST['plug'])?strval($_POST['plug']):'';
	if(empty($openid) || empty($currentplug)){
		$resultdata=array('code'=>-2,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	$zjlist_m=new M('zjlist');
	$where='openid="'.$openid.'" and fromplug="'.$currentplug.'"';
	$result=$zjlist_m->delete($where);
	if($result){
		$resultdata=array('code'=>1,'message'=>'删除成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-1,'message'=>'删除失败');
		echo json_encode($resultdata);
		return;
	}
}