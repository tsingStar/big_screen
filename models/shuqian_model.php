<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'CacheFactory.php');
//数钱model
class Shuqian_model{
	var $_configcachename='shuqian_config';
	var $_recentplayerscachename='shuqian_joinuser_';
	var $_currentconfig=null;
	var $_shuqian_config_m=null;
	var $_shuqian_record_m=null;
	var $_cache=null;
	
	public function __construct(){
		$this->_shuqian_config_m=new M('shuqian_config');
		$this->_shuqian_record_m=new M('shuqian_record');
		$this->_cache=new CacheFactory(CACHEMODE);
	}
	//获取当前活动的配置信息
	public function getCurrentConfig(){
		//先检查是不是多次调用的
		if(!empty($this->_currentconfig)){
			return $this->_currentconfig;
		}
		$shuqian_config=$this->_cache->get($this->_configcachename);
		//检查缓存是否存在
		if(empty($shuqian_config)){
			$shuqian_config=$this->_shuqian_config_m->find('currentshow=2 order by id asc limit 1');
			$previdandnextid=$this->getPrevidAndNextid($shuqian_config['id']);
			$shuqian_config['nextid']=$previdandnextid['nextid'];
			$shuqian_config['previd']=$previdandnextid['previd'];
			if(empty($shuqian_config)){
				return false;
			}
			$this->_cache->set($this->_configcachename,$shuqian_config);
		}
		$this->_currentconfig=$shuqian_config;
		return $this->_currentconfig;
	}
	//获取上一轮活动的id和下一轮活动的id
	public function getPrevidAndNextid($id){
		$previd=$this->_shuqian_config_m->find('id<'.$id.' order by id desc limit 0,1','id');
		$nextid=$this->_shuqian_config_m->find('id>'.$id.' order by id asc limit 0,1','id');
		$data['previd']=empty($previd)?null:$previd['id'];
		$data['nextid']=empty($nextid)?null:$nextid['id'];
		return $data;
	}

	public function getCurrentPlayers($id){
		$data=$this->_shuqian_record_m->find('configid='.$id,'*','count');
		return $data;
	}
	//更新缓存内容
	public function updateCurrentConfig($options){
		$oldid=$this->_currentconfig['id'];
		$ischanged=false;
		foreach($options as $k=>$v){
			//缓存发生变更
			if($this->_currentconfig[$k]!=$v){
				$this->_currentconfig[$k]=$v;
				$ischanged=true;
			}
		}
		if($options['id'] && $options['id']!=$oldid){
			$data=$this->getPrevidAndNextid($options['id']);
			// $this->_currentconfig['id']=$options['id'];
			$this->_currentconfig['nextid']=$data['nextid'];
			$this->_currentconfig['previd']=$data['previd'];

			$players=$this->getRecentPlayers();
			$this->_currentconfig['currentplayers']=$players['count'];
		}
		
		if($ischanged){
			$this->_cache->set($this->_configcachename,$this->_currentconfig);
		}
	}
	//获取指定轮次的抽奖结果
	public function getRecord($id){
		$shuqian_record=$this->_shuqian_record_m->select("configid=".$id." order by point desc","weixin_shuqian_record.*,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone",'','assoc','left join weixin_flag on weixin_flag.openid=weixin_shuqian_record.openid');
		foreach($shuqian_record as $k=>$v){
			$shuqian_record[$k]['nickname']=pack('H*',$v['nickname']);
		}
		return $shuqian_record;
	}
	//重置游戏
	public function resetShuqian($id){
		$this->deleteShuqianRecord($id);
		$shuqian_config=$this->_shuqian_config_m->find('id='.$id);
		$shuqian_config['status']=1;
		unset($shuqian_config['id']);
		$this->_shuqian_config_m->update('id='.$id,$shuqian_config);
		$current_shuqian_config=$this->getCurrentConfig();
		if($current_shuqian_config['id']==$id){
			$this->_cache->delete($this->_recentplayerscachename.$id);
			$this->_cache->delete($this->_configcachename);
		}
		return;
	}
	//删除一轮数钱游戏
	public function deleteShuqian($id){
		$this->deleteShuqianRecord($id);
		$this->_shuqian_config_m->delete("id=".$id);
		$current_shuqian_config=$this->getCurrentConfig();
		if($current_shuqian_config['id']==$id){
			$this->_cache->delete($this->_recentplayerscachename.$id);
			$this->_cache->delete($this->_configcachename);
		}
		return;
	}
	//删除游戏记录
	public function deleteShuqianRecord($id){
		$this->_shuqian_record_m->delete("configid=".$id);
		return;
	}
	//获取指定活动的配置信息
	public function getConfig($id){
		$shuqian_config=$this->_shuqian_config_m->find('id='.$id);
		return $shuqian_config;
	}
	//获取所有数钱活动配置
	public function getAllConfig(){
		$shuqian_config=$this->_shuqian_config_m->select("1 order by id asc");
		return $shuqian_config;
	}
	//把id为n的活动配置，设置当前的配置信息
	public function setCurrentConfig($id=0){
		$shuqian_config=$this->getCurrentConfig();
		if($id==0){
			$shuqian_config_new=$this->_shuqian_config_m->find('currentshow=2');
			if(empty($shuqian_config_new)){
				$shuqian_config_new=$this->_shuqian_config_m->find('1=1 order by status asc,id asc limit 1');
				$this->_shuqian_config_m->update('1=1',array('currentshow'=>1));
				$this->_shuqian_config_m->update('id='.$shuqian_config_new['id'],array('currentshow'=>2));
				$shuqian_config_new['currentshow']=2;
				$shuqian_config=$shuqian_config_new;
			}else{
				if($shuqian_config_new['id']!=$shuqian_config['id']){
					$shuqian_config=$shuqian_config_new;
				}else{
					foreach($shuqian_config_new as $k=>$v){
						$shuqian_config[$k]=$v;
					}
				}
			}
		}else{
			$this->_shuqian_config_m->update('1=1',array('currentshow'=>1));
			$this->_shuqian_config_m->update('id='.$id,array('currentshow'=>2));
			$shuqian_config=$this->_shuqian_config_m->find('id='.$id);
		}
		$this->updateCurrentConfig($shuqian_config);
		return $this->getCurrentConfig();
	}
	//游戏开始
	public function startgame(){
		$shuqian_config=$this->getCurrentConfig();
		$shuqian_config['status']=2;
		$this->_shuqian_config_m->update('id='.$shuqian_config['id'],array('status'=>$shuqian_config['status']));
		$this->updateCurrentConfig($shuqian_config);
		return $this->getCurrentConfig();
	}
	//结束游戏
	public function stopgame(){
		$shuqian_config=$this->getCurrentConfig();
		$shuqian_config['status']=3;
		$this->_shuqian_config_m->update('id='.$shuqian_config['id'],array('status'=>$shuqian_config['status']));
		$this->updateCurrentConfig($shuqian_config);
		return $this->getCurrentConfig();
	}

	//获取当前参与的人员名单
	public function getRecentPlayers(){
		$shuqian_config=$this->getCurrentConfig();
		$data=$this->_cache->get($this->_recentplayerscachename.$shuqian_config['id']);
		if(empty($data)){
			return array('count'=>0,'players'=>array());
		}
		return $data;
	}
	//记录参与人数
	public function setRecentPlayers($openid){
		$shuqian_config=$this->getCurrentConfig();
		$data=$this->getRecentPlayers();
		if(!isset($data['players'][$openid])){
			//1加入成功2加入失败
			$data['players'][$openid]=$shuqian_config['maxplayers']>$data['count']?1:2;
			$data['count']++;
			$this->_cache->set($this->_recentplayerscachename.$shuqian_config['id'],$data);
		}
		
		return $data;
	}
	//加入游戏
	public function joingame($openid){
		$shuqian_config=$this->getCurrentConfig();
		if(!$shuqian_config){
			return array('code'=>-1,'msg'=>'当前活动不存在');
		}else{
			//如果活动是不能重复中奖的，那么检查是否是已经中奖的用户
			if($shuqian_config['winningagain']==1){
				$shuqian_winner_record=$this->_shuqian_record_m->find('openid="'.$openid.'" and iswinner=2');
				if($shuqian_winner_record){
					return array('code'=>-3,'msg'=>'您已经中过奖了，无法再次参与活动');
				}
			}

			$result=$this->setRecentPlayers($openid);
			$shuqian_config['currentplayers']=$result['count'];
			$this->updateCurrentConfig($shuqian_config);
			if($shuqian_config['currentplayers']<=intval($shuqian_config['maxplayers'])){
				$shuqian_record=$this->_shuqian_record_m->find('openid="'.$openid.'" and configid='.$shuqian_config['id']);
				if(!$shuqian_record){
					$shuqian_record=array('point'=>0,'openid'=>$openid,'configid'=>$shuqian_config['id']);
					$this->_shuqian_record_m->add($shuqian_record);
				}
				return array('code'=>1,'msg'=>'参与成功');
			}else{
				if ($result['players'][$openid]==1) {
					return array('code'=>1,'msg'=>'参与成功');
				}else{
					return array('code'=>-2,'msg'=>'互动人数满了');
				}

				// return array('code'=>-2,'msg'=>'互动人数满了');
			}
		}
	}
	//获取得奖名单
	public function getWinner(){
		$shuqian_config=$this->getCurrentConfig();
		if($shuqian_config['toprank']>0){
			$sql="update weixin_shuqian_record set iswinner=2 where id in (select idtable.id from (select id from weixin_shuqian_record where  configid=".$shuqian_config['id']." order by point desc limit ".$shuqian_config['toprank'].") as idtable)";
			$this->_shuqian_record_m->query($sql);
		}
		return $this->getTopN($shuqian_config['toprank']>0?$shuqian_config['toprank']:10);
	}
	//取前N名的数据
	public function getTopN($n=10){
		$shuqian_config=$this->getCurrentConfig();
		$where='configid='.$shuqian_config['id'].' order by point desc limit '.$n;
		$data=$this->_shuqian_record_m->select($where,'weixin_shuqian_record.*,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone','','assoc',' left join weixin_flag on weixin_flag.openid=weixin_shuqian_record.openid');
		foreach($data as $k=>$v){
			if($shuqian_config['showstyle']==1){
				$data[$k]['nickname']=pack('H*',$v['nickname']);
			}
			if($shuqian_config['showstyle']==2){
				$data[$k]['nickname']=$v['signname'];
			}
			if($shuqian_config['showstyle']==3){
				$data[$k]['nickname']=$v['phone'];
			}
		}
		return $data;

	}
	
	//数钱用户上分函数
	public function addScore($openid,$score){
		$shuqian_config=$this->getCurrentConfig();
		$where='openid="'.$openid.'" and configid='.$shuqian_config['id'];
		$shuqian_record=$this->_shuqian_record_m->find($where);

		if(!$shuqian_record){
			$returndata=array('code'=>-1,'message'=>'没有成功加入游戏,无法参与活动');
			return $returndata;
		}

		if($score>0){
			$shuqian_record['point']=intval($shuqian_record['point'])+$score;

			unset($shuqian_record['id']);
			unset($shuqian_record['openid']);
			unset($shuqian_record['configid']);
			unset($shuqian_record['iswinner']);
			
			$this->_shuqian_record_m->update($where,$shuqian_record);
		}
		$returndata=array('code'=>1,'message'=>'','data'=>array('point'=>$shuqian_record['point'],'status'=>$shuqian_config['status']));
		return $returndata;
	}
	
	//按照openid删除数钱游戏结果
	public function deleteShuqianRecordByOpenid($openid){
		return $this->_shuqian_record_m->delete('openid="'.$openid.'"');
	}
	//用于清空上墙数据,清空数钱记录
	public function clearshuqian(){
		//清空数钱记录
		$this->_shuqian_config_m->query('truncate table weixin_shuqian_record');
		$this->_shuqian_config_m->update('1',array('status'=>1,'currentshow'=>1));
		$configs=$this->getAllConfig();
		foreach($configs as $v){
			$this->_cache->delete($this->_recentplayerscachename.$v['id']);
		}
		$this->_cache->delete($this->_configcachename);
	}

}