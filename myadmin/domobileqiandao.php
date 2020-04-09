<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) .'/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'setmobileqiandaobg':
		setmobileqiandaobg();
		break;
}

function setmobileqiandaobg(){
	$load=Loader::getInstance();
	$load->model('System_Config_model');
	if (!empty($_FILES['mobileqiandaobg']['type'])) {
		if ("image/jpeg" != $_FILES['mobileqiandaobg']["type"] && "image/png" != $_FILES['mobileqiandaobg']["type"]) {
			echo "不能上传该文件格式";
			exit;
		}
		//上传的文件
		// require_once('../common/FileUploadFactory.php');
		// $fuf=new FileUploadFactory(SAVEFILEMODE);
		// $file=$fuf->SaveFormFile($_FILES['mobileqiandaobg']);

		// $load=Loader::getInstance();
	    $load->model('Attachment_model');
	    $file=$load->attachment_model->saveFormFile($_FILES['mobileqiandaobg']);

		$save = $load->system_config_model->set("mobileqiandaobg",$file['id']);
	}else{
		$save = $load->system_config_model->set("mobileqiandaobg",0);
	}
	echo "<script>alert('手机签到背景图已经配置成功！');history.go(-1);</script>";
}