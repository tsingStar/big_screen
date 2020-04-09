<?php
//http://xc.wxmiao.com/wall/ajax_act_shake.php?action=start&roundno=0
require_once(dirname(__FILE__) . '/../common/function.php');
require_once(dirname(__FILE__) . '/../common/http_helper.php');
include(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');

$action = $_GET['action'];

switch ($action){
	case 'result':
		voteresult();
		break;
	case 'votestart':
		votestart();
		break;
	case 'voteend':
		vateend();
		break;
}
function voteresult(){
	$vote_m=new M('vote');
	$vote=$vote_m->select('1 order by res desc');
// 	echo var_export($vote);
	$resultdata=array();
	$count=0;
	foreach ($vote as $item){
		$count+=$item['res'];
	}
	foreach ($vote as $item){
		$newitem['id']=$item['id'];
		$newitem['name']=$item['name'];
		$newitem['res']=$item['res'];
		$newitem['percent']=floor($item['res']/$count*100);
		$resultdata[]=$newitem;
	}
	echo json_encode($resultdata);
	return;
}

//开启投票
function votestart(){
	$wall_config_m=new M('wall_config');
	$where='1';
	$data=array('voteopen'=>1);
	$wall_config_m->update($where,$data);
	$cache=new CacheFactory(CACHEMODE);
    $cachename='wall_config';
    $cache->delete($cachename);
}
//结束投票
function vateend(){
	$wall_config_m=new M('wall_config');
	$where='1';
	$data=array('voteopen'=>2);
	$wall_config_m->update($where,$data);
	$cache=new CacheFactory(CACHEMODE);
    $cachename='wall_config';
    $cache->delete($cachename);
}