<?php
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../library/emoji/emoji.php');
$maxid=isset($_GET['mid'])?intval($_GET['mid']):0;

$load->model('System_Config_model');
$showtype=$load->system_config_model->get('signnameshowstyle');

//签到名单
$load->model("Flag_model");
$signpeople=$load->flag_model->getRecentSignedUsers(1,$maxid);

if(!empty($signpeople)){
	$signpeople=$signpeople[0];
	$signpeople['nickname']=emoji_unified_to_html(emoji_softbank_to_unified(pack('H*', $signpeople['nickname'])));
	if($showtype['configvalue']==2 && !empty($signpeople['signname'])){
		//显示姓名
		$signpeople['nickname']=$signpeople['signname'];
	}
	if($showtype['configvalue']==3 && !empty($signpeople['phone'])){
		//显示电话
		$signpeople['nickname']=substr_replace($signpeople['phone'],'****',3,4);
	}
	$returndata=$signpeople;
	$returndata['error']=1;
	$returndata['mid']=$signpeople['signorder'];
	$returndata['omid']=$maxid;
	echo json_encode($returndata);
}else{
	echo '{"error":-1}';
}