<?php
require_once('Page.php');
class VoteItems extends Page{
	function show(){
		$voteconfigid=isset($_GET['id'])?intval($_GET['id']):0;
		if($voteconfigid<=0){
			header('location:voteconfig.php');
			return;
		}
		$vote_config_m=new M('vote_config');
		$vote_config=$vote_config_m->find('id='.$voteconfigid);

		$vote_items_m=new M('vote_items');
		$vote_items=$vote_items_m->select('voteconfigid='.$voteconfigid.' order by id asc');

		$this->_load->model('Attachment_model');

		foreach($vote_items as $k=>$v){
			if(intval($v['imageid'])>0){
				$img=$this->_load->attachment_model->getById($v['imageid']);
				$vote_items[$k]['imagepath']=$img['filepath'];
			}else{
				$vote_items[$k]['imagepath']='/wall/themes/meepo/assets/images/noimage200x200.png';
			}
		}

		$this->assign('vote_config',$vote_config);
		$this->assign('vote_items',$vote_items);
		$this->display('templates/voteitems.html');
	}
}
$page=new VoteItems();
$page->setTitle('投票选项管理');
$page->show();
