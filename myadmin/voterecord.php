<?php
require_once('Page.php');
class VoteRecord extends Page{
	function show(){
		$page=isset($_GET['page'])?intval($_GET['page']):1;
		$page=$page<1?1:$page;
		$pagesize=20;

		$voteconfigid=isset($_GET['id'])?intval($_GET['id']):0;
		if($voteconfigid<=0){
			header('location:voteconfig.php');
			return;
		}
		$vote_config_m=new M('vote_config');
		$vote_config=$vote_config_m->find('id='.$voteconfigid);

		
		$vote_record_m=new M('vote_record');
		$sql=<<<SQL
		SELECT
		weixin_flag.nickname,
		weixin_flag.avatar,
		weixin_flag.signname,
		weixin_flag.phone,
		weixin_vote_items.voteitem,
		weixin_vote_items.imageid,
		weixin_vote_record.voteconfigid,
		weixin_vote_record.openid,
		weixin_vote_record.id,
		weixin_vote_record.updated_at,
		weixin_vote_record.voteitemid
		FROM
		weixin_vote_record
		inner join weixin_flag on weixin_flag.openid = weixin_vote_record.openid
		inner join weixin_vote_items on weixin_vote_record.voteitemid = weixin_vote_items.id
		WHERE
		weixin_vote_record.voteconfigid = {{voteconfigid}}
		ORDER BY
		weixin_vote_record.updated_at ASC limit {{start}},{{num}};
SQL;
$sql=str_replace('{{voteconfigid}}',$voteconfigid,$sql);
$sql=str_replace('{{start}}',($page-1)*$pagesize,$sql);
$sql=str_replace('{{num}}',$pagesize,$sql);
		$vote_records=$vote_record_m->query($sql);
		$vote_records=$vote_record_m->fetch_array($vote_records);
		$vote_records_count=$vote_record_m->find('voteconfigid='.$voteconfigid,'*','count');
		$this->_load->model('Attachment_model');
		foreach($vote_records as $k=>$v){
			$vote_records[$k]['nickname']=pack('H*', $v['nickname']);
			$vote_records[$k]['phone']=$v['phone'];
			$vote_records[$k]['nickname']=pack('H*', $v['nickname']);
			$vote_records[$k]['updated_at']=date('Y-m-d H:i:s',$v['updated_at']);
			if(intval($v['imageid'])>0){
				$img=$this->_load->attachment_model->getById($v['imageid']);
				$vote_records[$k]['imagepath']=$img['filepath'];
			}else{
				$vote_records[$k]['imagepath']='/wall/themes/meepo/assets/images/noimage200x200.png';
			}
		}
		$baseurl='voterecord.php'.'?id='.$voteconfigid;
		$this->assign('vote_config',$vote_config);
		$this->assign('vote_records',$vote_records);
		$this->assign('pagehtml',$this->pagerhtml($page,$pagesize,$vote_records_count,$baseurl));
		$this->display('templates/voterecord.html');
	}
}
$page=new VoteRecord();
$page->setTitle('投票结果管理');
$page->show();
