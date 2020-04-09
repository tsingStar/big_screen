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
$action=$_POST['oper'];
switch ($action){
	case 'edit':
		editvoteitem();
		break;
	case 'add':
		addvoteitem();
		break;
	case 'del':
		delvoteitem();
		break;
	case 'clearvote':
		clearvote();
		break;
}
function editvoteitem(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	$name=isset($_POST['name'])?strval($_POST['name']):'';
	if($id<=0){
		$resultdata=array('code'=>-2,'message'=>'ID错误');
		echo json_encode($resultdata);
		return;
	}
	$data=array('name'=>$name);
	$vote_m=new M('vote');
	$result=$vote_m->update('id='.$id,$data);
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
function addvoteitem(){
	$name=isset($_POST['name'])?strval($_POST['name']):'';
	$data=array('name'=>$name,'res'=>0);
	$vote_m=new M('vote');
	$result=$vote_m->add($data);
	if($result){
		$resultdata=array('code'=>1,'message'=>'修改成功','id'=>$result);
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-1,'message'=>'修改失败','id'=>0);
		echo json_encode($resultdata);
		return;
	}
}
function delvoteitem(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id<=0){
		$resultdata=array('code'=>-2,'message'=>'ID错误');
		echo json_encode($resultdata);
		return;
	}
	$vote_m=new M('vote');
	$result=$vote_m->delete('id='.$id);
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
//可能已经不用了，一会检查一下
//清空投票的数据
function clearvote(){
	$vote_m=new M('vote');
	$vote_m->update('1',array('res'=>0));
	$flag_m=new M('flag');
	$flag_m->update('1',array('vote'=>''));
	$resultdata=array('code'=>1,'message'=>'投票数据已经清空');
	echo json_encode($resultdata);
	return;
}