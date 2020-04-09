<?php
include(dirname(__FILE__) . '/../common/db.class.php');
require_once('../common/session_helper.php');
// if (!isset($_SESSION['views']) || $_SESSION['views'] != true) {
// 	echo '{"ret":-1}';
// 	return ;
// }

$switch=isset($_GET['bgmusicstatus'])?intval($_GET['bgmusicstatus']):1;
$plugname=isset($_GET['plugname'])?strval($_GET['plugname']):'';

$switch=$switch==1?1:2;
$music_m=new M('music');
$data=array('bgmusicstatus'=>$switch);
$query=$music_m->update('plugname="'.$plugname.'"',$data);
$returndata=array();
if($query){
	// $returndata['switch']=$switch;
	$returndata['ret']=1;
}else{
	// $returndata['switch']=$switch;
	$returndata['ret']=-1;
}
echo json_encode($returndata);
return;