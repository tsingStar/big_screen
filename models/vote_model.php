<?php
class Vote_model{
	var $_vote_config_m=null;
	var $_vote_items_m=null;
	var $_vote_record_m=null;
	function __construct(){
		$this->_vote_config_m=new M('vote_config');
		$this->_vote_items_m=new M('vote_items');
		$this->_vote_record_m=new M('vote_record');
	}

	//获取当前投票的信息
	function getCurrentVoteConfig($id){
		$vote_config=null;
		if($id==0){
			$vote_config=$this->_vote_config_m->find('1 order by status asc,currentshow asc,id asc limit 1');
		}else{
			$vote_config=$this->getVoteConfigById($id);
		}
		//设置为当前的投票主题
		$this->setCurrentVoteConfig($vote_config['id']);
		$previd=$this->_vote_config_m->find('id<'.$vote_config['id'].' order by id desc','id');
		$nextid=$this->_vote_config_m->find('id>'.$vote_config['id'].' order by id asc','id');
		$vote_config['previd']=isset($previd)?$previd['id']:null;
		$vote_config['nextid']=isset($nextid)?$nextid['id']:null;
		return $vote_config;
	}
	//把指定id的配置设置为当前投票主题
	public function setCurrentVoteConfig($id){
		$this->_vote_config_m->update('id!='.$id,array('currentshow'=>2));
		$this->_vote_config_m->update('id='.$id,array('currentshow'=>1));
	}

	//获取当前的投票主题配置
	public function getCurrentVoteConfig2(){
		$vote_config=$this->_vote_config_m->find('currentshow=1 limit 1');
		return $vote_config;
	}
	//按照id获取voteconfig
	function getVoteConfigById($id){
		return $this->_vote_config_m->find('id='.$id);
	}

	//按照voteconfigid获取对应的所有选项
	function getVoteItemsByVoteConfigId($id){
		return $this->_vote_items_m->select('voteconfigid='.$id.' order by votecount desc,id asc');
	}

	function deleteRecordByOpenid($openid){
		$records=$this->_vote_record_m->select(' openid="'.$openid.'" group by voteitemid','voteitemid,count(0) as cnt');
		foreach($records as $k=>$v){
			$iteminfo=$this->_vote_items_m->find('id='.$v['voteitemid']);
			$iteminfo['votecount']=intval($iteminfo['votecount'])-intval($v['cnt']);
			$iteminfo['votecount']=$iteminfo['votecount']>0?$iteminfo['votecount']:0;
			$id=$iteminfo['id'];
			unset($iteminfo['id']);
			$this->_vote_items_m->update('id='.$id,$iteminfo);
		}
		$this->_vote_record_m->delete('openid="'.$openid.'"');
	}
	//清空投票数据
	function clearVoteData(){
		//删除投票记录
		$this->_vote_record_m->query('truncate table weixin_vote_record');
		//重置投票统计数据
		$this->_vote_items_m->update('1',array('votecount'=>0));
	}
}