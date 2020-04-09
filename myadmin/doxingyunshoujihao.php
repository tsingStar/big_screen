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
$action=$_GET['action'];
switch ($action){
	case 'setdesignated':
		setdesignated();
		break;

	case 'del':
		deldesignated();
		break;
	case 'delitem':
		delitem();
		break;
	case 'clearlucknum':
		clearlucknum();
		break;
}
function clearlucknum(){
	$xingyunshoujihao_m=new M('xingyunshoujihao');
	$where='designated !=2 and designated !=3';
	$xingyunshoujihao_m->delete($where);
	
	$where='1';
	$data=array('status'=>1,'created_at'=>0);
	$xingyunshoujihao_m->update($where, $data);
	
	$resultdata=array('code'=>1,'message'=>'删除成功');
	echo json_encode($resultdata);
	return;
	
}
//删除中奖记录
function delitem(){
	$id=isset($_GET['id'])?intval($_GET['id']):0;
	if($id==0){
		$resultdata=array('code'=>-1,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	$xingyunshoujihao_m=new M('xingyunshoujihao');
	$where='id='.$id;
	$xingyunshoujihao=$xingyunshoujihao_m->find($where);
	if($xingyunshoujihao['designated']==2){
		$data=array('status'=>1);
		$xingyunshoujihao_m->update($where, $data);
		$resultdata=array('code'=>1,'message'=>'删除成功');
		echo json_encode($resultdata);
		return;
	}
	$result=$xingyunshoujihao=$xingyunshoujihao_m->delete($where);
	if($result){
		$resultdata=array('code'=>1,'message'=>'删除成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-2,'message'=>'删除失败');
		echo json_encode($resultdata);
		return;
	}
}
//删除内定
function deldesignated(){
	$id=isset($_GET['id'])?intval($_GET['id']):0;
	if($id==0){
		$resultdata=array('code'=>-1,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	$xingyunshoujihao_m=new M('xingyunshoujihao');
	//当前序号是否有设置，如果有设置就提示已经设置过了
	$result=$xingyunshoujihao_m->delete('id='.$id);
	if($result){
		$resultdata=array('code'=>1,'message'=>'删除成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-2,'message'=>'删除失败');
		echo json_encode($resultdata);
		return;
	}
}
//设置内定
function setdesignated(){
	$openid=isset($_POST['openid'])?strval($_POST['openid']):'';
	$designated=isset($_POST['designated'])?intval($_POST['designated']):0;
	$ordernum=isset($_POST['ordernum'])?intval($_POST['ordernum']):0;
	
	//openid或者designated或者ordernum没有传入时返回数据错误的提示
	if(empty($openid) || $designated==0 || $ordernum==0){
		$resultdata=array('code'=>-1,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	
	$xingyunshoujihao_m=new M('xingyunshoujihao');
	//当前序号是否有设置，如果有设置就提示已经设置过了
	$result=$xingyunshoujihao_m->find('(openid="'.$openid.'" or (designated=2 or status=2)) and ordernum='.$ordernum);
	if(!empty($result)){
		$resultdata=array('code'=>-2,'message'=>'这条内定信息已经设置过了');
		echo json_encode($resultdata);
		return;
	}
	
	$result=$xingyunshoujihao_m->find('openid="'.$openid.'" and designated=2 ');
	if(!empty($result)){
		$resultdata=array('code'=>-3,'message'=>'这个人已经在其他序号上有必中设置了。');
		echo json_encode($resultdata);
		return;
	}
	
	//这个序号已经设置过内定信息了
	$data=array('openid'=>$openid,'ordernum'=>$ordernum,'designated'=>$designated);
	$result=$xingyunshoujihao_m->add($data);
	if($result){
		$resultdata=array('code'=>1,'message'=>'内定成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-4,'message'=>'内定失败，请联系管理员');
		echo json_encode($resultdata);
		return;
	}
// 	$result=$xingyunshoujihao_m->find('(designated=2 or status=2) and ordernum='.$ordernum);
// 	if(!empty($result)){
// 		$resultdata=array('code'=>-3,'message'=>'第'.$ordernum.'位中奖的人已经中奖或者已经内定必中过了');
// 		echo json_encode($resultdata);
// 		return;
// 	}
	
	
}
