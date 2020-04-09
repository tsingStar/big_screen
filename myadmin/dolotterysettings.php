<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/CacheFactory.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
require_once(dirname(__FILE__) . '/../models/system_config_model.php');
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'set_showstyle':
		set_showstyle();
		break;
	case 'setlotteryshow':
		setlotteryshow();
		break;
}
function set_showstyle(){
	$key=isset($_GET['key'])?$_GET['key']:'';
	$val=isset($_GET['val'])?intval($_GET['val']):1;
	if(!in_array($key,array('cjshowtype','threedimensionallotteryshowtype','cjxshowtype','zjdshowtype'))){
		echo '{"code":-1,"message":"数据格式错误"}';
		return ;
	}
	$val=($val>3 ||$val<1)?1:$val;
	$system_config_model=new System_Config_model();
	$return=$system_config_model->set($key,$val);
	if($return){
		echo '{"code":1,"message":"修改成功"}';
		return ;
	}else{
		echo '{"code":-2,"message":"修改失败"}';
		return ;
	}
}
function setlotteryshow(){
	$key=isset($_GET['key'])?$_GET['key']:'';
	$val=isset($_GET['val'])?intval($_GET['val']):1;
	if(!in_array($key,array('3dlotteryshow'))){
		echo '{"code":-1,"message":"数据格式错误"}';
		return ;
	}
	$val=($val>3 ||$val<1)?1:$val;
	$system_config_model=new System_Config_model();
	$return=$system_config_model->set($key,$val);
	if($return){
		echo '{"code":1,"message":"修改成功"}';
		return ;
	}else{
		echo '{"code":-2,"message":"修改失败"}';
		return ;
	}
}