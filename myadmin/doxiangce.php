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
	case 'deleteitem':
		deleteitem();
		break;
	case 'addphoto':
		addphoto();
		break;
}

//删除照片
function deleteitem(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id==0){
		echo '{"code":-1,"message":"数据不正确"}';
		return ;
	}
	
	$xiangce_m=new M('xiangce');
	$result=$xiangce_m->delete('id='.$id);
	if($result){
		echo '{"code":1,"message":""}';
		return ;
	}else{
		echo '{"code":-2,"message":"删除失败"}';
		return ;
	}
}
//添加照片
function addphoto(){
	if (!empty($_FILES['photo']['type'])) {
		//上传文件
		/*** example usage ***/
		// $filename = $_FILES['photo']['name'];
		// $info = pathinfo($filename);
		// require_once('../common/FileUploadFactory.php');
		// $fuf=new FileUploadFactory(SAVEFILEMODE);
		// $file=$fuf->SaveFormFile($_FILES['photo']);
		
		$load=Loader::getInstance();
	    $load->model('Attachment_model');
	    $file=$load->attachment_model->saveFormFile($_FILES['photo']);
		
		$xiangce_m = new M('xiangce');
		$data=array('id'=>NULL,'imagepath'=>$file['id']);
		$result= $xiangce_m->add($data);
		if($result){
			$resultdata=array('code'=>1,'message'=>'保存成功','filepath'=>$file['filepath']);
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
}