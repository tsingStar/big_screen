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
	case 'setimage':
		setimage();
		break;
	case "setfullscreen":
		setfullscreen();
		break;
}

function setfullscreen(){
	$fullscreen=isset($_POST['fullscreen'])?intval($_POST['fullscreen']):1;
	$bimu_config_m=new M('bimu_config');
	$data=array('fullscreen'=>$fullscreen);
	$save = $bimu_config_m->update('1', $data);
	echo '{"code":1,"message":"修改成功"}';
	return;
}

function setimage(){
	$bimu_config_m=new M('bimu_config');
	if (!empty($_FILES['imagepath']['type'])) {
		//上传的文件
		// require_once('../common/FileUploadFactory.php');
		// $fuf=new FileUploadFactory(SAVEFILEMODE);
		// $file=$fuf->SaveFormFile($_FILES['imagepath']);


		$load=Loader::getInstance();
	    $load->model('Attachment_model');
	    $file=$load->attachment_model->saveFormFile($_FILES['imagepath']);
		// 		echo $file;
		$data=array('imagepath'=>$file['id']);
		$save = $bimu_config_m->update('1', $data);
		// 		echo var_export($save);
	}else{
		$data=array('imagepath'=>'');
		$save = $bimu_config_m->update('1', $data);
	}
	echo "<script>alert('闭幕墙图片已经更换成功！');history.go(-1);</script>";
}