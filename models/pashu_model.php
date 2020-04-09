<?php
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'basemodel.php');
//猴子爬树model
class Pashu_model extends Basemodel{
	var $_configcachename='pashu_config';
	var $_recentjoinuser='pashu_joinuser_';
	var $_currentconfig=null;
	var $_pashu_config_m=null;
	var $_pashu_record_m=null;
	var $_cache=null;
	
	public function __construct(){
		parent::__construct();
		$this->_pashu_config_m=new M('pashu_config');
		$this->_pashu_record_m=new M('pashu_record');
		$this->_cache=new CacheFactory(CACHEMODE);
	}
	//获取当前活动的配置信息
	public function getCurrentConfig(){
		//先检查是不是多次调用的
		if(!empty($this->_currentconfig)){
			return $this->_currentconfig;
		}
		$pashu_config=$this->_cache->get($this->_configcachename);
		//检查缓存是否存在
		if(empty($pashu_config)){
			$pashu_config=$this->_pashu_config_m->find('currentshow=2 order by id asc limit 1');
			$previdandnextid=$this->getPrevidAndNextid($pashu_config['id']);
			$pashu_config['nextid']=$previdandnextid['nextid'];
			$pashu_config['previd']=$previdandnextid['previd'];
			if(empty($pashu_config)){
				return false;
			}
			$this->_cache->set($this->_configcachename,$pashu_config);
		}
		$this->_currentconfig=$pashu_config;
		return $this->_currentconfig;
	}
	//获取上一轮活动的id和下一轮活动的id
	public function getPrevidAndNextid($id){
		$previd=$this->_pashu_config_m->find('id<'.$id.' order by id desc limit 0,1','id');
		$nextid=$this->_pashu_config_m->find('id>'.$id.' order by id asc limit 0,1','id');
		$data['previd']=empty($previd)?null: $previd['id'];
		$data['nextid']=empty($nextid)?null: $nextid['id'];
		return $data;
	}
	//保存一个配置信息
	public function setConfig($data){
		$columns=array('id','times','toprank','winningagain','status','maxplayers','showstyle','currentshow');
		foreach($data as $k=>$v){
			if(!in_array($k,$columns)){
				unset($data[$k]);
			}
		}
		$id=isset($data['id'])?intval($data['id']):0;
		unset($data['id']);
		if($id>0){
			$result=$this->_pashu_config_m->update('id='.$id,$data);
		}else{
			$result=$this->_pashu_config_m->add($data);
		}
		return $result;
	}
	//获取当前参与人数
	public function getCurrentPlayers($id){
		$data=$this->_pashu_record_m->find('configid='.$id,'*','count');
		return $data;
	}
	//获取最新加入的人
	public function getRecentPlayers(){
		$pashu_config=$this->getCurrentConfig();
		$data=$this->_cache->get($this->_recentjoinuser.$pashu_config['id']);
		if(empty($data)){
			return array('players'=>array(),'openidlist'=>array(),'count'=>0);
		}
		return $data;
	}
	//取出一些数据
	public function shiftRecentPlayers($num=0){
		$pashu_config=$this->getCurrentConfig();
		$data=$this->getRecentPlayers();
		$len=count($data['players']);
		if($num==0 || $len<=$num){
			$returndata=$data['players'];
			$data['players']=array();
		}else{
			$returndata=array_slice($data['players'],0,$num);
			$leftdata=array_slice($data['players'],$num,$len);
			$data['players']=$leftdata;
		}
		$this->_cache->set($this->_recentjoinuser.$pashu_config['id'],$data);
		return $returndata;
	}
	//添加最近加入的人
	public function setRecentPlayers($player){
		$pashu_config=$this->getCurrentConfig();
		$data=$this->getRecentPlayers();
		if(!isset($data['openidlist'][$player['openid']])){
			$data['openidlist'][$player['openid']]=$pashu_config['maxplayers']>$data['count']?1:2;
			$data['count']++;
			array_push($data['players'],array('avatar'=>$player['avatar']));
			$this->_cache->set($this->_recentjoinuser.$pashu_config['id'],$data);
		}
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

		//id发生变化
		if($options['id'] && $options['id']!=$oldid){
			$data=$this->getPrevidAndNextid($options['id']);
			$this->_currentconfig['nextid']=$data['nextid'];
			$this->_currentconfig['previd']=$data['previd'];
			
			$players=$this->getRecentPlayers();
			$this->_currentconfig['currentplayers']=$players['count'];
			//$this->getCurrentPlayers($options['id']);
			// $this->_cache->delete($this->_recentjoinuser);
		}
		// $ischanged=false;
		
		if($ischanged){
			$this->_cache->set($this->_configcachename,$this->_currentconfig);
		}
	}
	//获取指定轮次的抽奖结果
	public function getRecord($id){
		$pashu_record=$this->_pashu_record_m->select("configid=".$id." order by point desc","weixin_pashu_record.*,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone",'','assoc','left join weixin_flag on weixin_flag.openid=weixin_pashu_record.openid');
		foreach($pashu_record as $k=>$v){
			$pashu_record[$k]['nickname']=pack('H*',$v['nickname']);
		}
		return $pashu_record;
	}
	//重置游戏
	public function resetPashu($id){
		$this->deletePashuRecord($id);
		$pashu_config=$this->_pashu_config_m->find('id='.$id);
		$pashu_config['status']=1;
		unset($pashu_config['id']);
		$this->_pashu_config_m->update('id='.$id,$pashu_config);
		$current_pashu_config=$this->getCurrentConfig();
		if($current_pashu_config['id']==$id){
			$this->_cache->delete($this->_recentjoinuser.$id);
			$this->_cache->delete($this->_configcachename);
		}
		return;
	}
	//删除一轮爬树游戏
	public function deletePashu($id){
		$this->deletePashuRecord($id);
		$this->_pashu_config_m->delete("id=".$id);
		$current_pashu_config=$this->getCurrentConfig();
		if($current_pashu_config['id']==$id){
			$this->_cache->delete($this->_recentjoinuser.$id);
			$this->_cache->delete($this->_configcachename);
		}
		return;
	}
	//删除游戏记录
	public function deletePashuRecord($id){
		$this->_pashu_record_m->delete("configid=".$id);
		return;
	}
	//获取指定活动的配置信息
	public function getConfig($id){
		$pashu_config=$this->_pashu_config_m->find('id='.$id);
		return $pashu_config;
	}
	//获取所有爬树活动配置
	public function getAllConfig(){
		$pashu_config=$this->_pashu_config_m->select("1 order by id asc");
		return $pashu_config;
	}
	//把id为n的活动配置，设置当前的配置信息
	public function setCurrentConfig($id=0){
		$pashu_config=$this->getCurrentConfig();
		// echo var_export($pashu_config);exit();
		if($id==0){
			$pashu_config_new=$this->_pashu_config_m->find('currentshow=2');
			if(empty($pashu_config_new)){
				$pashu_config_new=$this->_pashu_config_m->find('1=1 order by status asc,id asc limit 1');
				$this->_pashu_config_m->update('1=1',array('currentshow'=>1));
				$this->_pashu_config_m->update('id='.$pashu_config_new['id'],array('currentshow'=>2));
				$pashu_config_new['currentshow']=2;
				$pashu_config=$pashu_config_new;
			}else{
				if($pashu_config_new['id']!=$pashu_config['id']){
					$pashu_config=$pashu_config_new;
				}else{
					foreach($pashu_config_new as $k=>$v){
						$pashu_config[$k]=$v;
					}
				}
			}
		}else{
			$this->_pashu_config_m->update('1=1',array('currentshow'=>1));
			$this->_pashu_config_m->update('id='.$id,array('currentshow'=>2));
			$pashu_config=$this->_pashu_config_m->find('id='.$id);
		}
		$this->updateCurrentConfig($pashu_config);
		return $this->getCurrentConfig();
	}
	//游戏开始
	public function startgame(){
		$pashu_config=$this->getCurrentConfig();
		$pashu_config['status']=2;
		$this->_pashu_config_m->update('id='.$pashu_config['id'],array('status'=>$pashu_config['status']));
		$this->updateCurrentConfig($pashu_config);
		return $this->getCurrentConfig();
	}
	//结束游戏
	public function stopGame(){
		$pashu_config=$this->getCurrentConfig();
		$pashu_config['status']=3;
		$this->_pashu_config_m->update('id='.$pashu_config['id'],array('status'=>$pashu_config['status']));
		$this->updateCurrentConfig($pashu_config);
		return $this->getCurrentConfig();
	}
	//加入游戏
	public function joingame($openid){
		$pashu_config=$this->getCurrentConfig();

		if(!$pashu_config){
			return array('code'=>-1,'msg'=>'当前活动不存在');
		}else{
			//如果活动是不能重复中奖的，那么检查是否是已经中奖的用户
			if($pashu_config['winningagain']==1){
				$pashu_winner_record=$this->_pashu_record_m->find('openid="'.$openid.'" and iswinner=2');
				if($pashu_winner_record){
					return array('code'=>-3,'msg'=>'您已经中过奖了，无法再次参与活动');
				}
			}

			//把最新加入的人的头像插入到最新加入人员头像列表中
			$this->_load->model('Flag_model');
			$player=$this->_load->flag_model->getUserinfo($openid);
			$joindata=$this->setRecentPlayers($player);
			$pashu_config['currentplayers']=$joindata['count'];
			$this->updateCurrentConfig($pashu_config);
			// echo var_export($pashu_config);
			if($pashu_config['currentplayers']<=intval($pashu_config['maxplayers'])){
				$pashu_record=$this->_pashu_record_m->find('openid="'.$openid.'" and configid='.$pashu_config['id']);
				if(!$pashu_record){
					$pashu_record=array('point'=>0,'openid'=>$openid,'configid'=>$pashu_config['id']);
					$this->_pashu_record_m->add($pashu_record);
					// $pashu_config['currentplayers']++;
					// $this->updateCurrentConfig($pashu_config);
				}
				return array('code'=>1,'msg'=>'参与成功');
			}else{
				if($joindata['openidlist'][$openid]==1){
					return array('code'=>1,'msg'=>'参与成功');
				}
				return array('code'=>-2,'msg'=>'互动人数满了');
			}
		}
	}
	//获取得奖名单
	public function getWinner(){
		$pashu_config=$this->getCurrentConfig();
		if($pashu_config['toprank']>0){
			$sql="update weixin_pashu_record set iswinner=2 where id in (select idtable.id from (select id from weixin_pashu_record where  configid=".$pashu_config['id']." order by point desc limit ".$pashu_config['toprank'].") as idtable)";
			$this->_pashu_record_m->query($sql);
		}
		return $this->getTopN($pashu_config['toprank']>0?$pashu_config['toprank']:10);
	}
	//取前N名的数据
	public function getTopN($n=10){
		$pashu_config=$this->getCurrentConfig();
		$where='configid='.$pashu_config['id'].' order by point desc limit '.$n;
		$data=$this->_pashu_record_m->select($where,'weixin_pashu_record.*,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone','','assoc',' left join weixin_flag on weixin_flag.openid=weixin_pashu_record.openid');
		foreach($data as $k=>$v){
			if($pashu_config['showstyle']==1){
				$data[$k]['nickname']=pack('H*',$v['nickname']);
			}
			if($pashu_config['showstyle']==2){
				$data[$k]['nickname']=$v['signname'];
			}
			if($pashu_config['showstyle']==3){
				$data[$k]['nickname']=$v['phone'];
			}
		}
		return $data;

	}
	
	//爬树用户上分函数
	public function addscore($openid,$score){
		$pashu_config=$this->getCurrentConfig();
		$where='openid="'.$openid.'" and configid='.$pashu_config['id'];
		$pashu_record=$this->_pashu_record_m->find($where);
		if(!$pashu_record){
			$returndata=array('code'=>-1,'msg'=>'无法参与活动');
			return $returndata;
		}
		if($score>0){
			$point=intval($pashu_record['point'])+$score;
			if($point>$pashu_config['times']){
				$point=$pashu_config['times'];
				//游戏结束
				$pashu_config['status']=3;
				$this->setConfig($pashu_config);
				$this->updateCurrentConfig($pashu_config);
			}
			$pashu_record['point']=$point;
			//删除不必要的字段
			unset($pashu_record['id']);
			unset($pashu_record['openid']);
			unset($pashu_record['configid']);
			unset($pashu_record['iswinner']);

			$this->_pashu_record_m->update($where,$pashu_record);
		}
		$returndata=array('code'=>1,'msg'=>'','data'=>array('point'=>$pashu_record['point'],'status'=>$pashu_config['status']));
		return $returndata;
	}
	
	//按照openid删除爬树游戏结果
	public function deletePashuRecordByOpenid($openid){
		return $this->_pashu_record_m->delete('openid="'.$openid.'"');
	}
	//用于清空上墙数据,清空爬树记录
	public function clearpashu(){
		//清空爬树记录
		$this->_pashu_config_m->query('truncate table weixin_pashu_record');
		$this->_pashu_config_m->update('1',array('status'=>1,'currentshow'=>1));
		$configs=$this->getAllConfig();
		foreach($configs as $v){
			$this->_cache->delete($this->_recentjoinuser.$v['id']);
		}
		$this->_cache->delete($this->_configcachename);
	}

}