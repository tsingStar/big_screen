<?php
require_once('../common/session_helper.php');
include(dirname(__FILE__) . '/../common/db.class.php');
include(dirname(__FILE__) . '/../common/function.php');
require_once dirname(__FILE__) . '/../library/emoji/emoji.php';
$omid=isset($_GET['mid'])?intval($_GET['mid']):0;
$num=isset($_GET['num'])?intval($_GET['num']):1;
if($num<=0){
	echo returnmsg(-1,'每次取的数据必须大于1');
	return;
}

$load->model('System_Config_model');
$showtype = $load->system_config_model->get("signnameshowstyle");

$load->model('Flag_model');
$flags=$load->flag_model->getShenheUsers($omid,$num);
if(empty($flags)){
	$returndata=array(
		'omid'=>$omid,
		'mid'=>$omid,
		'users'=>array()
	);

	echo returnmsg(1,'',$returndata);
	return;
}else{
	$returndata=array();
	$returndata['users']=array();
	$returndata['omid']=$omid;
	$returndata['mid']=$omid;
	for($i=0,$l=count($flags);$i<$l;$i++){
		$returndata['mid']=$flags[$i]['signorder']>$returndata['mid']?$flags[$i]['signorder']:$returndata['mid'];
		$flags[$i]['nickname']=emoji_unified_to_html(emoji_softbank_to_unified(pack('H*', $flags[$i]['nickname'])));
		$flag=processuserlist($flags[$i],$showtype['configvalue']);
		array_push($returndata['users'],$flag);
	}
	echo returnmsg(1,'',$returndata);
	return;
}

function processuserlist($user,$showtype){
	$newuser=array();
	$newuser['nickname']=processNickname($user,$showtype);
	$newuser['avatar']=$user['avatar'];
	return $newuser;
}

