<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'setxingyunhaomaconfig':
		setxingyunhaomaconfig();
		break;
	case 'setdesignated':
		setdesignated();
		break;
	case 'deletelucknum':
		deletelucknum();
		break;
	case 'clearlucknum':
		clearlucknum();
		break;
}
//清空中奖记录
function clearlucknum(){
	$xingyunhaoma_m=new M('xingyunhaoma');
	$result=$xingyunhaoma_m->delete('designated!=2 and designated!=3');
	$xingyunhaoma_m->update('designated=2 or designated=3',array('status'=>1));
	$resultdata=array('code'=>1,'message'=>'中奖信息已经删除');
	echo json_encode($resultdata);
	return;
}

function deletelucknum(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id==0){
		$resultdata=array('code'=>-1,'message'=>'数据错误');
		echo json_encode($resultdata);
		return;
	}
	$xingyunhaoma_m=new M('xingyunhaoma');
	$result=$xingyunhaoma_m->delete('id='.$id);
	if($result>0){
		$resultdata=array('code'=>1,'message'=>'删除成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-1,'message'=>'删除失败');
		echo json_encode($resultdata);
		return;
	}
}
function setdesignated(){
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	$ordernum=isset($_POST['ordernum'])?intval($_POST['ordernum']):0;
	$lucknum=isset($_POST['lucknum'])?intval($_POST['lucknum']):0;
	$designated=isset($_POST['designated'])?intval($_POST['designated']):2;
	$xingyunhaomaconfig_m=new M('xingyunhaoma_config');
	$xingyunhaomaconfig=$xingyunhaomaconfig_m->find('1 limit 1');
	if($ordernum==0){
		$resultdata=array('code'=>-1,'message'=>'中奖序号必须设置大于0的数字');
		echo json_encode($resultdata);
		return;
	}
	if($lucknum==0 || $lucknum<$xingyunhaomaconfig['minnum'] || $lucknum>$xingyunhaomaconfig['maxnum'] ){
		$resultdata=array('code'=>-2,'message'=>'中奖数字必须设置大于0,并且大于设置中的最小值，小于设置中的最大值');
		echo json_encode($resultdata);
		return;
	}
	$xingyunhaoma_m=new M('xingyunhaoma');
	//检查中奖序号设置，同一个序号，只能有一条必中记录，可以有多条不会中的记录
	$where= 'ordernum='.$ordernum.' and (designated=2 or designated=1)';
	if($id>0){
		$where=$where.' and id!='.$id;
	}
	$olddata=$xingyunhaoma_m->find($where,'*','count');
	if($olddata>0){
		$resultdata=array('code'=>-4,'message'=>'第'.$ordernum.'个中奖的记录已经存在');
		echo json_encode($resultdata);
		return;
	}
	//检查中奖号码，一个中奖号码，在中奖或内定必中状态的只能有一个，但是在不中奖状态中可以有多个
	$where='lucknum='.$lucknum.' and (designated=2 or designated=1)';
	$olddata=$xingyunhaoma_m->find($where,'*','count');
	if($olddata>0){
		$resultdata=array('code'=>-5,'message'=>'幸运号码：'.$lucknum.'已经中过奖或者已经被内定过了');
		echo json_encode($resultdata);
		return;
	}
	$designatedtext=array('','已中','必中','不会中');
	$where="lucknum=".$lucknum." and ordernum=".$ordernum." and designated=".$designated;
	$olddata=$xingyunhaoma_m->find($where,'*','count');
	if($olddata>0){
		$resultdata=array('code'=>-7,'message'=>'幸运号码：'.$lucknum.'已经在第'.$ordernum.'个中奖序号中设置过'.$designatedtext[$designated]);
		echo json_encode($resultdata);
		return;
	}
	
	$data=array('ordernum'=>$ordernum,'lucknum'=>$lucknum,'status'=>1,'designated'=>$designated,'created_at'=>time());
	$return=false;
	if($id>0){
		$return=$xingyunhaoma_m->update('id='.$id, $data);
	}else{
		$return=$xingyunhaoma_m->add($data);
	}
	if($return){
		$resultdata=array('code'=>1,'message'=>'保存成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-3,'message'=>'保存失败');
		echo json_encode($resultdata);
		return;
	}
	
}
	
function setxingyunhaomaconfig(){
	$minnum=isset($_POST['minnum'])?intval($_POST['minnum']):1;
	$maxnum=isset($_POST['maxnum'])?intval($_POST['maxnum']):2000;
	$xingyunhaomaconfig_m=new M('xingyunhaoma_config');
	$data=array('minnum'=>$minnum,'maxnum'=>$maxnum);
	$result=$xingyunhaomaconfig_m->update('1=1', $data);
	
// 	$wall_m=new M('wall');
// 	$result=$wall_m->add($data);
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

