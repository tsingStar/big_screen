<?php
include(dirname(__FILE__) . '/../common/db.class.php');
require_once('../common/session_helper.php');

$switch=isset($_GET['danmuswitch'])?intval($_GET['danmuswitch']):1;
$switch=$switch==1?1:2;
$danmu_config_m=new M('danmu_config');
$data=array('danmuswitch'=>$switch);
$query=$danmu_config_m->update(1,$data);
$returndata=array();
if($query){
	$returndata['switch']=$switch;
	$returndata['ret']=1;
}
echo json_encode($returndata);
return;


