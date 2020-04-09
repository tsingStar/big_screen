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
	case 'switchautoaudit':
		switchautoaudit();
		break;
	case 'switchloopplay':
		switchloopplay();
		break;
	case 'playrate':
		playrate();
		break;
	case 'setsendrate':
		setsendrate();
		break;
	case 'setblacklist':
		setblacklist();
		break;
	case 'set_msg_historynum':
		set_msg_historynum();
		break;
	case 'set_msg_showstyle':
		set_msg_showstyle();
		break;
	case 'set_msg_showbig':
		set_msg_showbig();
		break;
	case 'set_msg_showbigtime':
		set_msg_showbigtime();
		break;
	case 'set_msg_color':
		set_msg_color();
		break;
	case 'set_nickname_color':
		set_nickname_color();
		break;
	case 'set_msg_num':
		set_msg_num();
		break;
	case 'set_showstyle':
		set_showstyle();
		break;
}

function set_showstyle(){
	$key=isset($_GET['key'])?$_GET['key']:'';
	$val=isset($_GET['val'])?intval($_GET['val']):1;
	if(!in_array($key,array('wallnameshowstyle'))){
		echo '{"code":-1,"message":"数据格式错误"}';
		return ;
	}
	
	$val=($val>3 ||$val<1)?1:$val;
	$load=Loader::getInstance();
	$load->model('System_Config_model');
	$return = $load->system_config_model->set($key,$val);
	if($return){
		echo '{"code":1,"message":"修改成功"}';
		return ;
	}else{
		echo '{"code":-2,"message":"修改失败"}';
		return ;
	}
}

function set_msg_num(){
	$msg_num=isset($_POST['msg_num'])?intval($_POST['msg_num']):3;
	$wall_config_m=new M('wall_config');
	$data=array('msg_num'=>$msg_num);
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
//设置昵称颜色
function set_nickname_color(){
	
	$nickname_color=isset($_POST['nickname_color'])?strval($_POST['nickname_color']):'#4b9e09';
	$wall_config_m=new M('wall_config');
	$data=array('nickname_color'=>$nickname_color);
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
//设置上墙消息颜色
function set_msg_color(){
	
	$msg_color=isset($_POST['msg_color'])?strval($_POST['msg_color']):'#4b9e09';
	$wall_config_m=new M('wall_config');
	$data=array('msg_color'=>$msg_color);
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
//自动审核开关
function switchautoaudit(){
	$shenghe =isset($_POST['shenghe'])?intval($_POST['shenghe']):0;
	$wall_config_m=new M('wall_config');
	$data=array('shenghe'=>$shenghe);
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
//循环播放开关
function switchloopplay(){
	$circulation=isset($_POST['circulation'])?intval($_POST['circulation']):0;
	$wall_config_m=new M('wall_config');
	$data=array('circulation'=>$circulation);
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
function set_msg_historynum(){
	$msg_historynum=isset($_POST['msg_historynum'])?intval($_POST['msg_historynum']):0;
	$wall_config_m=new M('wall_config');
	$data=array('msg_historynum'=>$msg_historynum);
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
function set_msg_showstyle(){
	$msg_showstyle=isset($_POST['msg_showstyle'])?intval($_POST['msg_showstyle']):0;
	$wall_config_m=new M('wall_config');
	$data=array('msg_showstyle'=>$msg_showstyle);
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
function playrate(){
	$refreshtime=isset($_POST['refreshtime'])?intval($_POST['refreshtime']):0;
	$wall_config_m=new M('wall_config');
	$data=array('refreshtime'=>$refreshtime);
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