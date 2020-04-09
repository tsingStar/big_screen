<?php
require_once('common.php');
require_once('../Modules/Menu/Controllers/Api.php');
require_once('../Modules/Menu/Models/Menu_model.php');
use Modules\Menu\Controllers\Api;

$load->model('Plugs_model');
$plugs=$load->plugs_model->getPlugs(1);

$openid=$_GET['rentopenid'];
$isopen=false;
foreach($plugs as $item){
	if($item[name]=='vote'){
		$isopen=true;
	}
}
if(!$isopen){
	header('location:qiandao.php?rentopenid='.$openid);
}

//投票相关系统配置

$load->model('Flag_model');
$myinfo=$load->flag_model->getUserinfo($openid);


$myinfo['nickname']=pack('H*', $myinfo['nickname']);
$myinfo['datetime']=date('Y-m-d H:i:s',$myinfo['datetime']);

$load->model('Vote_model');
$vote_config=$load->vote_model->getCurrentVoteConfig2();



$vote_items=$load->vote_model->getVoteItemsByVoteConfigId($vote_config['id']);


foreach($vote_items as $k=>$v){
	$vote_total_num+=$v['votecount'];
}


$vote_record_m=new M('vote_record');
$vote_record=$vote_record_m->select('voteconfigid='.$vote_config['id'].' and openid="'.$openid.'"');

$check_voted=2;
$check_voted_id=array();
if($vote_record){
	$check_voted=1;
	foreach($vote_record as $item){
		array_push($check_voted_id,$item['voteitemid']);
	}
}else{
	$check_voted=2;
}



//与个人相关的投票信息

//模版页面相关内容
$smarty = new Smarty;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->compile_dir =COMPILEPATH;
$smarty->assign('openid',$openid);

$menu_api=new Api();
$custommenu=$menu_api->getAll(array('rentopenid'=>$openid));
$smarty->assign('custommenu',$custommenu);

$smarty->assign('user',$myinfo);
$smarty->assign('title','投票');
$smarty->assign('vote_config',$vote_config);
$smarty->assign('vote_items',$vote_items);
$smarty->assign('vote_total_num',$vote_total_num<=0?1:$vote_total_num);
$smarty->assign('check_voted',$check_voted);
//check_voted
$smarty->assign('check_voted_id',$check_voted_id);
$smarty->assign('erweima',$weixin_config['erweima']);
$smarty->assign('plugs',$plugs);

$smarty->display('template/app_header.html');
$smarty->display('template/app_vote.html');
$smarty->display('template/app_footer.html');