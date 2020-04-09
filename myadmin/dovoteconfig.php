<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');;
require_once(dirname(__FILE__) . '/../common/session_helper.php');
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'savevoteconfig':
		savevoteconfig();
		break;
	case 'getvoteconfig':
		getvoteconfig();
		break;
	case 'delitem':
		delitem();
		break;
	case 'clearvote':
		clearvote();
		break;
	case 'savevoteitem':
		savevoteitem();
		break;
	case 'getvoteitem':
		getvoteitem();
		break;
	case 'delvoteitem':
		delvoteitem();
		break;
	case 'getvoterecordstatistics':
		getvoterecordstatistics();
		break;
}
//投票记录统计
function getvoterecordstatistics(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id<=0){
		$resultdata=array('code'=>-1,'message'=>'获取参数失败');
		echo json_encode($resultdata);
		return;
	}
	$load=Loader::getInstance();
	$load->model('Vote_model');
	$voteitems=$load->vote_model->getVoteItemsByVoteConfigId($id);
	for($i=0,$l=count($voteitems);$i<$l;$i++){
		$echartdata['voteitem'][$i]=$voteitems[$i]['voteitem'];
		$echartdata['votecount'][$i]=$voteitems[$i]['votecount'];
	}
	$returndata=array('code'=>1,'message'=>'','echartdata'=>$echartdata);
	echo json_encode($returndata);
	return;
}
//删除投票选项
function delvoteitem(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id<=0){
		$resultdata=array('code'=>-1,'message'=>'获取参数失败');
		echo json_encode($resultdata);
		return;
	}
	//删除投票项
	$vote_items_m=new M('vote_items');
	$vote_items_m->delete('id='.$id);
	//删除投票项对应的数据
	$vote_record_m=new M('vote_record');
	$vote_record_m->delete('voteitemid='.$id);

	$returndata=array('code'=>1,'message'=>"删除成功");
	echo json_encode($returndata);

}
//保存投票选项
function savevoteitem(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	$configid=isset($_POST['voteconfigid'])?intval($_POST['voteconfigid']):0;

	if($configid<=0){
		$resultdata=array('code'=>-3,'message'=>'参数错误');
		echo json_encode($resultdata);
		return;
	}
	$voteitem=isset($_POST['voteitem'])?strval($_POST['voteitem']):'';
	if($voteitem==''){
		$resultdata=array('code'=>-2,'message'=>'投票项必须填写');
		echo json_encode($resultdata);
		return;
	}
	$votecount=isset($_POST['votecount'])?intval($_POST['votecount']):0;
	$votecount=$votecount<0?0:$votecount;
	//添加
	$voteiteminfo=array();
	$voteiteminfo['voteitem']=$voteitem;
	$voteiteminfo['voteconfigid']=$configid;
	$voteiteminfo['votecount']=$votecount;
	if (!empty($_FILES['imagepath']['type'])) {
		//上传的文件
		// require_once('../common/FileUploadFactory.php');
		// $fuf=new FileUploadFactory(SAVEFILEMODE);
		// $file=$fuf->SaveFormFile($_FILES['imagepath']);

		$load=Loader::getInstance();
	    $load->model('Attachment_model');
	    $file=$load->attachment_model->saveFormFile($_FILES['imagepath']);
	    
		$voteiteminfo['imageid']=$file['id'];
	}

	$vote_items_m=new M('vote_items');
	if($id>0){
		$result=$vote_items_m->update('id='.$id,$voteiteminfo);
	}else{
		$voteiteminfo['created_at']=time();
		$result=$vote_items_m->add($voteiteminfo);
	}
	if($result){
		$resultdata=array('code'=>1,'message'=>'保存成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-1,'message'=>'保存失败');
		echo json_encode($resultdata);
		return;
	}
}
function getvoteitem(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id<=0){
		$resultdata=array('code'=>-1,'message'=>'获取参数失败');
		echo json_encode($resultdata);
		return;
	}
	$vote_items_m=new M('vote_items');
	$vote_item=$vote_items_m->find('id='.$id);

	$load=Loader::getInstance();
	$load->model('Attachment_model');

	if(intval($vote_item['imageid'])>0){
		$img=$load->attachment_model->getById($vote_item['imageid']);
		$vote_item['imagepath']=$img['filepath'];
	}else{
		$vote_item['imagepath']='/wall/themes/meepo/assets/images/noimage200x200.png';
	}
	$resultdata=array('code'=>1,"message"=>'','data'=>$vote_item);
	echo json_encode($resultdata);
	return;
}
//删除投票
function delitem(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		//删除投票主题
		$vote_config_m=new M('vote_config');
		$query=$vote_config_m->delete('id='.$id);
		//还需删除相关的投票选项
		$vote_items_m=new M('vote_items');
		$query=$vote_items_m->delete('voteconfigid='.$id);
		//删除投票记录
		$vote_record_m=new M('vote_record');
		$query=$vote_record_m->delete('voteconfigid='.$id);
		
		$returndata=array('code'=>1,'message'=>"删除成功");
		echo json_encode($returndata);
	}
	return;
}

function clearvote(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		//还需删除相关的投票选项
		$vote_items_m=new M('vote_items');
		$query=$vote_items_m->update('voteconfigid='.$id,array('votecount'=>0));
		//删除投票记录
		$vote_record_m=new M('vote_record');
		$query=$vote_record_m->delete('voteconfigid='.$id);
		$returndata=array('code'=>1,'message'=>"投票数据清除成功");
		echo json_encode($returndata);
	}
}
//保存投票主题
function savevoteconfig(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	//投票标题
	$votetitle=isset($_POST['votetitle'])?strval($_POST['votetitle']):'';
	//每个观众的投票数
	$votenum=isset($_POST['votenum'])?intval($_POST['votenum']):1;
	//状态1进行中2结束
	$status=isset($_POST['status'])?intval($_POST['status']):1;

	//1表示最大投票数，2表示固定投票数，3表示最少投票数
	$votemode=isset($_POST['votemode'])?intval($_POST['votemode']):1;
	//1横向2纵向3图片
	$showtype=isset($_POST['showtype'])?intval($_POST['showtype']):1;

	$unit=isset($_POST['unit'])?strval($_POST['unit']):'';
	
	//可改投1不可以改2可以改
	$editable=isset($_POST['editable'])?intval($_POST['editable']):1;
	if(empty($votetitle)){
		$returndata=array('code'=>-1,'message'=>"投票主题必须填写");
		echo json_encode($returndata);
		return;
	}
	if($votenum<=0){
		$returndata=array('code'=>-2,'message'=>"每个人的可投票数必须大于0");
		echo json_encode($returndata);
		return;
	}
	if($status<=0){
		$returndata=array('code'=>-3,'message'=>"投票状态错误");
		echo json_encode($returndata);
		return;
	}

	if($showtype<=0){
		$returndata=array('code'=>-5,'message'=>"显示形式错误");
		echo json_encode($returndata);
		return;
	}
	$vote_config_m=new M('vote_config');
	if($id>0){
		$vote_config=$vote_config_m->find('id='.$id);
		$vote_config['votetitle']=$votetitle;
		$vote_config['votenum']=$votenum;
		$vote_config['status']=$status;
		$vote_config['votemode']=$votemode;
		$vote_config['showtype']=$showtype;
		$vote_config['editable']=$editable;
		$vote_config['unit']=$unit;
		unset($vote_config['id']);
		$vote_config_m->update('id='.$id,$vote_config);
		$returndata=array('code'=>2,'message'=>"修改成功");
		echo json_encode($returndata);
		return;
	}else{
		$vote_config=array();
		$vote_config['votetitle']=$votetitle;
		$vote_config['votenum']=$votenum;
		$vote_config['status']=$status;
		$vote_config['currentshow']=2;
		$vote_config['votemode']=$votemode;
		$vote_config['showtype']=$showtype;
		$vote_config['editable']=$editable;
		$vote_config['unit']=$unit;
		$vote_config['refreshtime']=3;
		$vote_config['created_at']=time();
		$return=$vote_config_m->add($vote_config);
		$returndata=array('code'=>1,'message'=>"添加成功");
		echo json_encode($returndata);
		return;
	}
	// $params=$_POST;
}

function getvoteconfig(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id<=0){
		$returndata=array('code'=>-1,'message'=>"无数据");
		echo json_encode($returndata);
		return;
	}
	$vote_config_m=new M('vote_config');
	$vote_config=$vote_config_m->find('id='.$id);
	$returndata=array('code'=>1,"message"=>"","data"=>$vote_config);
	echo json_encode($returndata);
	return;
}
