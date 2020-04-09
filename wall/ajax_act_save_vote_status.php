<?php
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');

// if (!isset($_SESSION['views']) || $_SESSION['views'] != true) {
// 	echo '{"ret":-1}';
// 	return ;
// }

$id=isset($_GET['id'])?intval($_GET['id']):0;
$status=isset($_GET['status'])?intval($_GET['status']):1;
if($id<=0){
	$returndata=array('code'=>-2,"message"=>"参数错误");
	echo json_encode($returndata);
	return;
}

$vote_config_m=new M('vote_config');
$vote_config=array('status'=>$status);

$vote_config_m->update('id='.$id,$vote_config);
$returndata=array('code'=>1,"message"=>"设置成功");
echo json_encode($returndata);
return;