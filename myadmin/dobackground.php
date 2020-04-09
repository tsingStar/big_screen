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
$action=isset($_GET['action'])?strval($_GET['action']):'';
switch($action){
case 'bg':
    setbg();
    break;
case 'resetbg':
    resetbg();
    break;
}

function resetbg(){
    $plugname=isset($_POST['plugname'])?strval($_POST['plugname']):'';
    $load=Loader::getInstance();
    $load->model('Background_model');
    $data=array('attachmentid'=>0,'plugname'=>$plugname,'bgtype'=>1);
    $result=$load->background_model->setBackground($data);
    $returndata=array('code'=>1,"message"=>"修改成功");
    echo json_encode($returndata);
    return ;
}

function setbg(){
    $plugname=isset($_POST['plugname'])?strval($_POST['plugname']):'';
    $mimetype=isset($_POST['type'])?strval($_POST['type']):'';
    if ($plugname==''|| $mimetype=='') {
        $returndata=array('code'=>-1,"message"=>"上传失败，可能是文件太大了");
        echo json_encode($returndata);
        return ;
    }
    
    $load=Loader::getInstance();
    $data=array('attachmentid'=>0,'plugname'=>$plugname);
    if (!empty($_FILES['file']['type'])) {
        //上传的文件
        $allowtypes='image/jpg,image/jpeg,image/png,video/mp4';
        $load->model('Attachment_model');
        $file=$load->attachment_model->saveFormFile($_FILES['file'], $allowtypes);
        // echo var_export($file);
        $data['attachmentid']=$file['id'];
        $bgtype=isVideo($_POST['type'])?2:1;
        $data['bgtype']=$bgtype;
    }
    $load->model('Background_model');
    $result=$load->background_model->setBackground($data);
    $returndata=array('code'=>1,"message"=>"上传成功");
    echo json_encode($returndata);
    return ;
}

// echo "<script>alert('微信墙自定义主题背景已经更换成功！');history.go(-1);</script>";
//上传的是否为视频
function isVideo($mimetype)
{
    $videomimetypes=array('video/mp4');
    if (in_array(strtolower($mimetype), $videomimetypes)) {
        return true;
    }
    return false;
}