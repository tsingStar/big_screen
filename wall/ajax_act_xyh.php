<?php

require_once(dirname(__FILE__) . '/../common/function.php');
include(dirname(__FILE__) . '/../common/db.class.php');

$action = $_GET['action'];
switch ($action){
	case 'getlucknum':
		getlucknum();
		break;
	case 'getoldlucknum':
		getoldlucknum();
		break;
}
function getoldlucknum(){
	$xingyunhaoma_m=new M('xingyunhaoma');
	$lucknumdata=$xingyunhaoma_m->select('status=2','lucknum');
	$lucknum_arr=array();
	foreach($lucknumdata as $v){
		array_push($lucknum_arr, $v['lucknum']);
	}
	$returndata=array('code'=>1,'lucknum_arr'=>$lucknum_arr);
	echo json_encode($returndata);
	return;
}
//获取幸运数字
function getlucknum(){
	$xingyunhaomaconfig_m=new M('xingyunhaoma_config');
	$xingyunhaomaconfig=$xingyunhaomaconfig_m->find('1 limit 1');
	$xingyunhaoma_m=new M('xingyunhaoma');
	$maxordernum=$xingyunhaoma_m->find('status=2','*','count');
	
	//当前的获奖序号
	$currentordernum=$maxordernum+1;
	$bizhong=$xingyunhaoma_m->find('ordernum='.$currentordernum.' and designated=2 and status!=2 limit 1');
	//如果是必中的就直接返回结果
	if(!empty($bizhong)){
		$xingyunhaoma_m->update('ordernum='.$currentordernum.' and lucknum='.$bizhong['lucknum'].' and designated=2', array('status'=>2));
		$returndata=array('code'=>1,'lucknum'=>$bizhong['lucknum']);
 		echo json_encode($returndata);
 		return;
	}
	//找出第n次抽奖中不会中的数字
	$buhuizhong=$xingyunhaoma_m->select('ordernum='.$currentordernum.' and designated=3','lucknum');
	$buhuizhong_num_arr=array();
	foreach($buhuizhong as $v){
		array_push($buhuizhong_num_arr, $v['lucknum']);
	}
	//找出在其他轮次中设置过必中或者在之前轮次中已经中过奖的数字
	$buhuizhong=$xingyunhaoma_m->select('ordernum!='.$currentordernum.' and (designated=2 or status=2)','lucknum');
	foreach($buhuizhong as $v){
		array_push($buhuizhong_num_arr, $v['lucknum']);
	}
	if(count($buhuizhong_num_arr)==($xingyunhaomaconfig['maxnum']-$xingyunhaomaconfig['minnum']+1)){
		$returndata=array('code'=>-1,'message'=>'已经没有数字可以用于抽奖了');
		echo json_encode($returndata);
		return;
	}
	$lucknum=getrandnum($xingyunhaomaconfig['minnum'],$xingyunhaomaconfig['maxnum'],$buhuizhong_num_arr);
	$xingyunhaoma_m->add(array('ordernum'=>$currentordernum,'status'=>2,'lucknum'=>$lucknum,'created_at'=>time(),'designated'=>1));
	$returndata=array('code'=>1,'lucknum'=>$lucknum);
	echo json_encode($returndata);
	return;
}

function getrandnum($min,$max,$except_arr){
	$lucknum_arr=array();
	for($i=0,$l=$max-$min;$i<=$l;$i++){
		array_push($lucknum_arr,$min+$i);
	}
	$left_arr=array_diff($lucknum_arr, $except_arr);
	$left_sort_arr=array();
	foreach($left_arr as $v){
		array_push($left_sort_arr, $v);
	}
	$randnum=$left_sort_arr[rand(0,count($left_sort_arr)-1)];
	return $randnum;
}




