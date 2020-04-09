<?php
include(dirname(__FILE__) . '/../common/db.class.php');
require_once('../common/session_helper.php');
include("../common/biaoqing.php");
include("../library/emoji/emoji.php");
// if (!isset($_SESSION['views']) || $_SESSION['views'] != true) {
// 	echo '{"ret":-1}';
// 	return ;
// }
//用审核顺序来得到数序的排序 shenheorder
$lastshenhetime=isset($_GET['shenhetime'])?intval($_GET['shenhetime']):0;
$danmu_config_m=new M('danmu_config');
$danmu_config=$danmu_config_m->find('1');
$num=intval($danmu_config['historynum']);
if($num==0 && $lastshenhetime==0){
	$num=0;
	$returndata=array('data'=>array(),'ret'=>1,'lasttime'=>time());
	echo json_encode($returndata);
	return;
}else{
	$num=50;
}
// $num=($num<=0 &&$lastshenhetime==0) ?3:$num;
$messagelist=GetWallMessage($lastshenhetime,$num);


$load->model('Attachment_model');
foreach($messagelist as $k=>$message){
	$message['nick_name']=emoji_unified_to_html(emoji_softbank_to_unified(pack('H*', $message['nickname'])));
	unset($message['nickname']);
	$message['content']=emoji_unified_to_html(emoji_softbank_to_unified(pack('H*', $message['content'])));
	$message['content']=biaoqing($message['content']);
	$message['type']=1;
	if(!empty($message['image'])){
		$message['type']=2;
		$image=$load->attachment_model->getById($message['image']);
		$message['content']=$image['filepath'];
	}
	$messagelist[$k]=$message;
}

$returndata=array();
$returndata['data']=$messagelist;

$returndata['ret']=0;
echo json_encode($returndata);
return;

function GetWallMessage($shenhetime,$limit=100){
	if($limit==0){
		return array();
	}
	$wall_m=new M('wall');
	$data=$wall_m->select('shenhetime > '.$shenhetime.' and image=0 and ret=1 order by shenhetime desc limit '.$limit);
	return $data;
}
