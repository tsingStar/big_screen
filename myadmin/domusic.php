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
	case 'setbgmusic':
		setbgmusic();
		break;
	
}

//设置自定义背景
function setbgmusic(){
	$music_m=new M('music');
	
	if (!empty($_FILES['bgmusicpath']['type'])) {
		//上传的文件
		$allowtypes='audio/mp3';
		$load=Loader::getInstance();
	    $load->model('Attachment_model');
	    $file=$load->attachment_model->saveFormFile($_FILES['bgmusicpath'],$allowtypes);
		$data=array('bgmusic'=>intval($file['id']),'bgmusicstatus'=>isset($_POST['bgmusicstatus'])?1:2);
		$where='plugname="'.$_POST['plugname'].'"';
		$save = $music_m->update($where,$data);
	}else{
		$data=array('bgmusic'=>intval($_POST['bgmusic']),'bgmusicstatus'=>isset($_POST['bgmusicstatus'])?1:2);
		$where='plugname="'.$_POST['plugname'].'"';
		
		$save = $music_m->update($where, $data);
		// echo var_export($save);
	}

	echo "<script>alert('总背景音乐上传成功！');history.go(-1);</script>";
}

/*
$_FILES error值
其值为 0，没有错误发生，文件上传成功。 
其值为 1，上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。 
其值为 2，上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。 
其值为 3，文件只有部分被上传。 
其值为 4，没有文件被上传。 
其值为 6，找不到临时文件夹。PHP 4.3.10 和 PHP 5.0.3 引进。 
其值为 7，文件写入失败。PHP 5.1.0 引进。 
*/