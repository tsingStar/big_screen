<?php
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
require_once(dirname(__FILE__) . '/../common/function.php');

// if (!isset($_SESSION['views']) || $_SESSION['views'] != true) {
// 	echo '{"ret":-1}';
// 	return ;
// }
$id=isset($_GET['id'])?intval($_GET['id']):0;
if($id<=0){
	$returndata=array('code'=>-2,"message"=>"参数错误");
	echo json_encode($returndata);
	return;
}
$load->model('Vote_model');
$vote_config=$load->vote_model->getVoteConfigById($id);

$data=array();
$cnt_sort_arr=array();

$vote_items=$load->vote_model->getVoteItemsByVoteConfigId($id);

$vote_total_num=0;
$vote_total_max=0;

$load->model('Attachment_model');
foreach($vote_items as $k=>$v){
	$data[$k]['voteitem']=$v['voteitem'];
	$data[$k]['id']=$v['id'];
	$data[$k]['cnt']=$v['votecount'];
	if(intval($v['imageid'])>0){
		$img=$load->attachment_model->getById($v['imageid']);
		$data[$k]['imagepath']=$img['filepath'];
	}else{
		$data[$k]['imagepath']='/wall/themes/meepo/assets/images/noimage200x200.png';
	}
	$cnt_sort_arr[]=$data[$k]['cnt'];
	$vote_total_num+=$data[$k]['cnt'];
	$vote_total_max=$vote_total_max>$data[$k]['cnt']?$vote_total_max:$data[$k]['cnt'];
}
//排序
array_multisort($cnt_sort_arr,SORT_DESC,$data);

// echo var_export($data);
//横向
if($vote_config['showtype']==1){
	$returndata=array('code'=>1,'type'=>1,'total'=>$vote_total_max,'data'=>$data);
 	echo json_encode($returndata);
 	return;
}
//纵向
if($vote_config['showtype']==2){
	$returndata=array('code'=>1,'type'=>2,'total'=>$vote_total_num,'data'=>$data);
 	echo json_encode($returndata);
 	return;
}
//图片
if($vote_config['showtype']==3){
 	$returndata=array('code'=>1,'type'=>3,'data'=>$data);
 	echo json_encode($returndata);
 	return;
}
