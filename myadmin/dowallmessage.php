<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../library/emoji/emoji.php');
require_once(dirname(__FILE__) . '/../common/biaoqing.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'getmore':
		getmore();
		break;
	case 'accept':
		accept();
		break;
	case 'refuse':
		refuse();
		break;
}
//获取更多消息
function getmore(){
	$ret=isset($_GET['ret'])?intval($_GET['ret']):0;
	$ret=$ret>2?0:$ret;
	$ret=$ret<0?0:$ret;
	$wall_m=new M('wall');
	$where='ret ='.$ret.' order by id desc limit 50';
	$messagelist=$wall_m->select($where);
	if(!$messagelist){
		$returndata=array('code'=>1,'data'=>array(),'lastid'=>0);
		echo json_encode($returndata);
		return;
	}
	$count=count($messagelist);
	$lastid=count($messagelist)>0?$messagelist[$count-1]['id']:0;
	$newmessagelist=[];
	foreach($messagelist as $item){
		$newmessagelist[]=processmessage($item);
	}
	$returndata=array('code'=>1,'data'=>$newmessagelist,'lastid'=>$lastid);
	echo json_encode($returndata);
	return;
}

//通过审核
function accept(){
	$id=isset($_GET['id'])?intval($_GET['id']):0;
	if($id<=0){
		$returndata=array('code'=>-1,'message'=>'修改失败');
		echo json_encode($returndata);
		return;
	}
	$where='id='.$id;
	$data=array('ret'=>1);
	$wall_m=new M('wall');
	
	$maxshenheorder=time();

	$data['shenhetime']=$maxshenheorder;
	$return=$wall_m->update($where,$data);
	if($return){
		$returndata=array('code'=>1,'message'=>'修改成功');
		echo json_encode($returndata);
		return;
	}
	$returndata=array('code'=>-2,'message'=>'修改失败');
	echo json_encode($returndata);
	return;
}
//拒绝通过审核消息
function refuse(){
	$id=isset($_GET['id'])?intval($_GET['id']):0;
	if($id<=0){
		$returndata=array('code'=>-1,'message'=>'修改失败');
		echo json_encode($returndata);
		return;
	}
	$where='id='.$id;
	$data=array('ret'=>2,'shenhetime'=>0);
	$wall_m=new M('wall');
	$return=$wall_m->update($where,$data);
	if($return){
		$returndata=array('code'=>1,'message'=>'修改成功');
		echo json_encode($returndata);
		return;
	}
	$returndata=array('code'=>-2,'message'=>'修改失败');
	echo json_encode($returndata);
	return;
}


function processmessage($message){
	$message['nickname']=emoji_unified_to_html(emoji_softbank_to_unified(pack('H*', $message['nickname'])));
	$message['content']=emoji_unified_to_html(emoji_softbank_to_unified(pack('H*', $message['content'])));
	$message = emoji_unified_to_html(emoji_softbank_to_unified($message));
	$message['content']=biaoqing($message['content']);
	$message['type']=1;
	$load=Loader::getInstance();
	$load->model('Attachment_model');
	if(!empty($message['image'])){
		$message['type']=2;
		$image=$load->attachment_model->getById($message['image']);
		$message['content']=$image['filepath'];
	}
	$newmessage=array();
	$newmessage['id']=$message['id'];
	$newmessage['content']=$message['content'];
	$newmessage['type']=$message['type'];
	$newmessage['nickname']=$message['nickname'];
	$newmessage['avatar']=$message['avatar'];
	$newmessage['openid']=$message['openid'];
	// echo var_export($newmessage);
	return $newmessage;
}