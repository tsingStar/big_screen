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
	case 'setavatarnum':
		setavatarnum();
		break;
	case 'setavatarsize':
		setavatarsize();
		break;
	case 'setavatargap':
		setavatargap();
		break;
	case 'setlogo_pj':
		setlogo_pj();
		break;
	case 'setpages':
		setpages();
		break;

}

//设置拼接logo
function setlogo_pj(){
// 	echo var_export($_FILES);exit();
	if (!empty($_FILES['logo_pj']['type'])) {
		//上传文件
		/*** example usage ***/
		// $filename = $_FILES['logo_pj']['name'];
		// $info = pathinfo($filename);
		// require_once('../common/FileUploadFactory.php');
		// $fuf=new FileUploadFactory(SAVEFILEMODE);
		// $result=$fuf->SaveFormFile($_FILES['logo_pj']);

		$load=Loader::getInstance();
	    $load->model('Attachment_model');
	    $file=$load->attachment_model->saveFormFile($_FILES['logo_pj']);
	    
		if($file){
			$resultdata=array('code'=>1,'message'=>'保存成功','filepath'=>'/imageproxy.php?id='.$file['id']);
			echo json_encode($resultdata);
			return;
		}else{
			$resultdata=array('code'=>-1,'message'=>'修改失败');
			echo json_encode($resultdata);
			return;
		}
		
		
	}else{
		$resultdata=array('code'=>-2,'message'=>'上传失败');
		echo json_encode($resultdata);
		return;
	}
	//echo "<script>alert('微信墙拼接Logo已经配置成功！');history.go(-1);</script>";
}

//设置调用的头像数量
function setavatarnum(){
	$avatarnum=isset($_POST['avatarnum'])?strval($_POST['avatarnum']):30;
	$threedimensional_m=new M('threedimensional');
	$data=array('avatarnum'=>$avatarnum);
	$result=$threedimensional_m->update('1',$data);
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
//设置调用的头像大小
function setavatarsize(){
	$avatarsize=isset($_POST['avatarsize'])?strval($_POST['avatarsize']):5;
	$threedimensional_m=new M('threedimensional');
	$data=array('avatarsize'=>$avatarsize);
	$result=$threedimensional_m->update('1',$data);
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

//设置调用的头像间距
function setavatargap(){
	$avatargap=isset($_POST['avatargap'])?strval($_POST['avatargap']):10;
	$threedimensional_m=new M('threedimensional');
	$data=array('avatargap'=>$avatargap);
	$result=$threedimensional_m->update('1',$data);
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

//设置页面数据
function setpages(){
	$datastr=isset($_POST['datastr'])?strval($_POST['datastr']):'';
// 	if($datastr==''){
// 		$resultdata=array('code'=>-2,'message'=>'数据错误');
// 		echo json_encode($resultdata);
// 		return;
// 	}
	$threedimensional_m=new M('threedimensional');
	$data=array('datastr'=>$datastr);
	$result=$threedimensional_m->update('1',$data);
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