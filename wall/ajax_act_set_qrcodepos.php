<?php
include(dirname(__FILE__) . '/../common/db.class.php');
require_once('../common/session_helper.php');
// if (!isset($_SESSION['views']) || $_SESSION['views'] != true) {
// 	echo '{"ret":-1}';
// 	return ;
// }

$w=isset($_POST['w'])?intval($_POST['w']):1;
$h=isset($_POST['h'])?intval($_POST['h']):1;
$posx = isset($_POST['x']) ? intval($_POST['x']):1 ;
$posy = isset($_POST['y']) ? intval($_POST['y']):1 ;

$data = ['w'=>$w,'h'=>$h,'x'=>$posx,'y'=>$posy];
$data_str = serialize($data);
$load->model('System_Config_model');
$result = $load->system_config_model->set('qrcodepos',$data_str);

$returndata=array();
if($result){
	$returndata=array("code"=>1,"message"=>"保存成功","data"=>$data);
}else{
	$returndata=array("code"=>-1,"message"=>"保存失败");
}
echo json_encode($returndata);
return;