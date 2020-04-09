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
	case 'setvotetitle':
		setvotetitle();
		break;
	case 'setvotefresht':
		setvotefresht();
		break;
	case 'setvotecannum':
		setvotecannum();
		break;
	case 'setvoteshowway':
		setvoteshowway();
		break;
}
//设置投标标题
function setvotetitle(){
	$votetitle=isset($_POST['votetitle'])?strval($_POST['votetitle']):'';
	if(empty($votetitle)){
		$resultdata=array('code'=>-2,'message'=>'标题必须填写哦');
		echo json_encode($resultdata);
		return;
	}
	$wall_config_m=new M('wall_config');
	$data=array('votetitle'=>$votetitle);
	$result=$wall_config_m->update('1',$data);
	$cache=new CacheFactory(CACHEMODE);
    $cachename='wall_config';
    $cache->delete($cachename);
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
//设置投票前台页面刷新时间
function setvotefresht(){
	$votefresht=isset($_POST['votefresht'])?intval($_POST['votefresht']):0;
	if($votefresht==0){
		$resultdata=array('code'=>-2,'message'=>'刷新时间必须大于0');
		echo json_encode($resultdata);
		return;
	}
	$wall_config_m=new M('wall_config');
	$data=array('votefresht'=>$votefresht);
	$result=$wall_config_m->update('1',$data);
	$cache=new CacheFactory(CACHEMODE);
    $cachename='wall_config';
    $cache->delete($cachename);
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
//设置每个人可以投票的次数
function setvotecannum(){
	$votecannum=isset($_POST['votecannum'])?intval($_POST['votecannum']):0;
	if($votecannum==0){
		$resultdata=array('code'=>-2,'message'=>'每个人可投票数大于0');
		echo json_encode($resultdata);
		return;
	}
	$wall_config_m=new M('wall_config');
	$data=array('votecannum'=>$votecannum);
	$result=$wall_config_m->update('1',$data);
	$cache=new CacheFactory(CACHEMODE);
    $cachename='wall_config';
    $cache->delete($cachename);
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
//设置投票显示方式
function setvoteshowway(){
	$voteshowway=isset($_POST['voteshowway'])?intval($_POST['voteshowway']):1;
	if($voteshowway==0){
		$resultdata=array('code'=>-2,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	$wall_config_m=new M('wall_config');
	$data=array('voteshowway'=>$voteshowway);
	$result=$wall_config_m->update('1',$data);
	$cache=new CacheFactory(CACHEMODE);
    $cachename='wall_config';
    $cache->delete($cachename);
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