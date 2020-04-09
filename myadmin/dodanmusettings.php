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
	//是否显示昵称
	case 'switchshowname':
		switchshowname();
		break;
	case 'switchisloop':
		switchisloop();
		break;
	case 'setlooptime':
		setlooptime();
		break;
	case 'setsendrate':
		setsendrate();
		break;

	case 'sethistorynum':
		sethistorynum();
		break;
	case 'setpositionmode':
		setpositionmode();
		break;

	case 'set_msg_color':
		set_msg_color();
		break;

}


//设置上墙消息颜色
function set_msg_color(){
	$textcolor=isset($_POST['textcolor'])?strval($_POST['textcolor']):'#4b9e09';
	$danmu_config_m=new M('danmu_config');
	$data=array('textcolor'=>$textcolor);
	$result=$danmu_config_m->update('1',$data);
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
//自动审核开关
function switchshowname(){
	$showname=isset($_POST['showname'])?intval($_POST['showname']):1;
	$danmu_config_m=new M('danmu_config');
	$data=array('showname'=>$showname);
	$result=$danmu_config_m->update('1',$data);
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
//循环播放开关
function switchisloop(){
	$isloop=isset($_POST['isloop'])?intval($_POST['isloop']):1;
	$danmu_config_m=new M('danmu_config');
	$data=array('isloop'=>$isloop);
// 	echo var_export($data);
	$result=$danmu_config_m->update('1',$data);
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
function sethistorynum(){
	$historynum=isset($_POST['historynum'])?intval($_POST['historynum']):30;
	$danmu_config_m=new M('danmu_config');
	$data=array('historynum'=>$historynum);
	$result=$danmu_config_m->update('1',$data);
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
function setpositionmode(){
	$positionmode=isset($_POST['positionmode'])?intval($_POST['positionmode']):1;
	$danmu_config_m=new M('danmu_config');
	$data=array('positionmode'=>$positionmode);
	$result=$danmu_config_m->update('1',$data);
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
function set_msg_showbig(){
	$msg_showbig=isset($_POST['msg_showbig'])?intval($_POST['msg_showbig']):0;
	$wall_config_m=new M('wall_config');
	$data=array('msg_showbig'=>$msg_showbig);
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
function set_msg_showbigtime(){
	$msg_showbigtime=isset($_POST['msg_showbigtime'])?intval($_POST['msg_showbigtime']):0;
	$wall_config_m=new M('wall_config');
	$data=array('msg_showbigtime'=>$msg_showbigtime);
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
//大屏幕播放消息的频率
function setlooptime(){
	$looptime=isset($_POST['looptime'])?intval($_POST['looptime']):3;
	$danmu_config_m=new M('danmu_config');
	$data=array('looptime'=>$looptime);
	$result=$danmu_config_m->update('1',$data);
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
//观众信息发送的频率
function setsendrate(){
	$timeinterval=isset($_POST['timeinterval'])?intval($_POST['timeinterval']):0;
	$wall_config_m=new M('wall_config');
	$data=array('timeinterval'=>$timeinterval);
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
//屏蔽关键字列表
function setblacklist(){
	$black_word=isset($_POST['black_word'])?strval($_POST['black_word']):'';
	$black_word=str_replace('，', ',', $black_word);//转换中文都到到半角逗号
	$wall_config_m=new M('wall_config');
	$data=array('black_word'=>$black_word);
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