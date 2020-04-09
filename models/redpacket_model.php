<?php
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'basemodel.php');
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'wxpay'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'WxPay.Api.php');
//红包雨model
class Redpacket_model extends Basemodel{
	var $_redpacket_config_m=null;
	var $_redpacket_order_return_m=null;
	var $_redpacket_orders_m=null;
	var $_redpacket_round_m=null;
	var $_redpacket_users_m=null;
	var $_redpacket_deeja_orders_m=null;
	var $_cache=null;
	var $_roundinfo_cachename='redpacket_roundinfo_';
	//获取当前活动的配置信息
	public function __construct(){
		parent::__construct();
		$this->_redpacket_config_m=new M('redpacket_config');
		$this->_redpacket_order_return_m=new M('redpacket_order_return');
		$this->_redpacket_orders_m=new M('redpacket_orders');
		$this->_redpacket_users_m=new M('redpacket_users');
		$this->_redpacket_round_m=new M('redpacket_round');
		$this->_redpacket_deeja_orders_m=new M('redpacket_deeja_orders');
		$this->_cache=new CacheFactory(CACHEMODE);

	}

	public function getUserOrder($roundid,$userid){
		$data=$this->_redpacket_users_m->find('roundid='.$roundid.' and userid='.$userid,'orderno');
		if(!$data)return false;
		$orderno=$data['orderno'];
		$order=$this->_redpacket_deeja_orders_m->find('orderno="'.$orderno.'"');
		return $order;
	}
	//添加一个deeja接口的订单号
	public function addDeejaOrder($order){
		$order['created_at']=time();
		$order['updated_at']=time();
		$order['status']=1;
		$this->_redpacket_deeja_orders_m->add($order);
	}
	public function updateDeejaOrder($order){
		$where='orderno="'.$order['orderno'].'"';
		$this->_redpacket_deeja_orders_m->update($where,['status'=>$order['status'],'remark'=>$order['remark'],'updated_at'=>time()]);
	}

	public function getDeejaOrder($orderno){
		return $this->_redpacket_deeja_orders_m->find('orderno="'.$orderno.'"');
	}
	//红包活动的配置信息
	public function getRedpacketConfig(){
		$data=$this->_redpacket_config_m->find('1=1');
		$data['sendname']=empty($data['sendname'])?'迪加科技':$data['sendname'];
		$data['wishing']=empty($data['wishing'])?'恭喜发财':$data['wishing'];
		return $data;
	}
	public function updateRedacketConfig($data){
		return $this->_redpacket_config_m->update('1=1',$data);
	}
	//按照round id获取活动轮次信息
	public function getRoundById($id){
		$data=$this->_cache->get($this->_roundinfo_cachename.$id);
		if(!$data){
			$data=$this->_redpacket_round_m->find('id='.$id);
			$this->_cache->set($this->_roundinfo_cachename.$id,serialize($data));
		}else{
			$data=unserialize($data);
		}
		return $data;
	}

	//更新轮次信息
	public function updateRound($roundinfo){
		$id=$roundinfo['id'];
		unset($roundinfo['id']);
		$this->_cache->delete($this->_roundinfo_cachename.$id);
		return $this->_redpacket_round_m->update('id='.$id,$roundinfo);
	}
	//添加一轮红包雨
	public function addRound($roundinfo){
		return $this->_redpacket_round_m->add($roundinfo);
	}
	//删除红包轮次
	public function deleteRound($id){
		$this->deleteRedpacketUsersByRoundid($id);
		$this->_cache->delete($this->_roundinfo_cachename.$id);
		return $this->_redpacket_round_m->delete('id='.$id);
	}
	public function deleteRedpacketUsersByRoundid($id){
		return $this->_redpacket_users_m->delete('roundid='.$id);
	}
	//获取当前轮次的活动信息
	public function getCurrentRound(){
		return $this->_redpacket_round_m->find('status<3 order by id asc limit 1');
	}

	//获取红包轮次列表
	public function getRoundList(){
		$data=$this->_redpacket_round_m->select('1 order by id asc');
		return $data;
	}
	//统计红包数量
	public function getRedpackettimes($roundid=0,$userid=0){
		$where=' 1 ';
		if($roundid>0){
			$where.=' and roundid='.$roundid;
		}
		if($userid>0){
			$where.=' and userid='.$userid;
		}
		return $this->_redpacket_users_m->find($where,'*','count');
	}

	//统计被人领取红包数
	public function getSendRedpackettimes($roundid=0){
		$where=' 1 and userid>0 ';
		if($roundid>0){
			$where.=' and roundid='.$roundid;
		}
		return $this->_redpacket_users_m->find($where,'*','count');
	}
	//获取用户的红包记录
	public function getRedpacketsByUserid($userid,$roundid=0){
		$where='userid='.$userid;
		if($roundid>0){
			$where.=' and roundid='.$roundid;
		}
		$data=$this->_redpacket_users_m->select($where);
		return $data;
	}
	//获取指定轮次，某个人获得所有红包的发放状态
	public function getRedpacketSendingStatusByUserid($userid,$roundid=0){
		$where='userid='.$userid;
		if($roundid>0){
			$where.=' and roundid='.$roundid;
		}
		$where.= ' limit 0,1';
		$data =$this->_redpacket_users_m->find($where);
		return $data['status'];
	}

	//修改指定轮次某个人获得所有红包的发放状态 1表示未发2表示发放中3已发4发放失败
	public function setRedpacketSendingStatusByUserid($userid,$status,$roundid=0){
		$where='userid='.$userid;
		if($roundid>0){
			$where.=' and roundid='.$roundid;
		}
		$data=array('status'=>$status);
		if($status==3){//如果是发放完成的，记录一下完成时间
			$data['updated_at']=time();
		}
		$result=$this->_redpacket_users_m->update($where,$data);
		return $result;
	}

	public function setRedpacketSendingStatusByOrderno($orderno,$status){
		$data=array('status'=>$status);
		$where='orderno = "'.$orderno.'"';
		if($status==3){//如果是发放完成的，记录一下完成时间
			$data['updated_at']=time();
		}
		$result=$this->_redpacket_users_m->update($where,$data);
		return $result;
	}
	public function setRedpacketSendingStatus($status,$roundid){
		$where.=' 1=1 and roundid='.$roundid;
		$data=array('status'=>$status);
		if($status==3){
			$data['updated_at']=time();
		}
		$result=$this->_redpacket_users_m->update($where,$data);
		return $result;
	}

	//记录红包发放订单提交后微信返回的信息
	public function addRedpacketSendingOrderReturn($returndata){
		$this->_redpacket_order_return_m->add($returndata);
	}
	//获取红包发放日志
	public function getRedpacketSendingLog($openid){
		$where ='re_openid="'.$openid.'" order by id desc';
		$data=$this->_redpacket_order_return_m->select($where,'orderno,total_amount,err_code_des');
		return $data;
	}
	//发放红包
	public function sendingRedpacket($openid,$amount,$sendname='',$wishing='恭喜发财'){
		$redpacketdata=new WxPayRedPack();
		//创建订单号
		$orderno=$this->_genMch_billno();
		$redpacketdata->SetOrderno($orderno);
		$redpacketdata->SetAct_name('红包雨');
		$redpacketdata->SetRe_openid($openid);
		$redpacketdata->SetRemark('红包雨');
		$redpacketdata->SetSend_name($sendname);
		$redpacketdata->SetTotal_amount($amount);
		$redpacketdata->SetTotal_num(1);
		$redpacketdata->SetWishing($wishing);
		$result=WxPayApi::sendredpack($redpacketdata,6);
		return $result;
	}

	private function _genMch_billno(){
		$this->_load->model('Weixin_model');
		$weixin_config= $this->_load->weixin_model->getConfig();
		$mch_billno=$weixin_config['mch_id'].date('YmdHis',time()).rand(1000000000,9999999999);
		return $mch_billno;
	}
	
	//统计某个状态值下所有人的所获得红包金额 
	//状态值1表示未发2表示发放中3已发
	public function getRedpacketUserinfoByStatus($status=1,$roundid=0){
		$where=' `status`='.$status;
		if($roundid>0){
			$where.=' and roundid='.$roundid;
		}
		$sql='select weixin_flag.openid,b.userid,b.totalmoney from weixin_flag left join (select userid ,sum(amount) as totalmoney from weixin_redpacket_users where userid>0 and '.$where.' group by userid) b on b.userid= weixin_flag.id where weixin_flag.flag=2 and weixin_flag.status=1 and b.totalmoney>0';
		$query=$this->_redpacket_users_m->query($sql);
		$data=$this->_redpacket_users_m->fetch_array($query);
		return $data;
	}
	//中奖
	public function setWinner($roundid,$userid){
		$noownershipredpacket=$this->_getNoOwnershipRedpacket($roundid);
		if(empty($noownershipredpacket)){
			return false;
		}
		$noownershipredpacket['userid']=$userid;
		//未发状态 1表示未发2表示发放中3已发4发放失败
		$noownershipredpacket['status']=1;
		unset($noownershipredpacket['updated_at']);
		$result=$this->updateRedpacketUser($noownershipredpacket);
		if($result){
			return $noownershipredpacket;
		}
		return false;
	}
	//更新一条redpacketusers数据
	public function updateRedpacketUser($data){
		$id=$data['id'];
		unset($data['id']);
		return $this->_redpacket_users_m->update('id='.$id,$data);
	}

	public function updateRedpacketUserMchBillno($roundid,$userid,$orderno,$status){
		$where='roundid='.$roundid.' and userid="'.$userid.'"';
		return $this->_redpacket_users_m->update($where,['orderno'=>$orderno,'status'=>$status,'updated_at'=>time()]);
	}

	public function updateRedpacketUserMchBillnoById($id,$orderno,$status){
		$where='id='.$id;
		return $this->_redpacket_users_m->update($where,['orderno'=>$orderno,'status'=>$status,'updated_at'=>time()]);
	}
	
	//获取一个没有被人抢到的红包
	private function _getNoOwnershipRedpacket($roundid){
		$where=' userid is NULL and roundid='.$roundid.' order by id asc limit 1 ';
		return $this->_redpacket_users_m->find($where);
	}
	//获取最新的目前还没有显示的中奖人信息
	public function getWinner($roundid,$lastwinnerid){
		$where='weixin_redpacket_users.roundid='.$roundid.' and weixin_redpacket_users.userid>0 and weixin_redpacket_users.id>'.$lastwinnerid;
		return $this->_redpacket_users_m->select($where,'weixin_redpacket_users.id,weixin_flag.nickname,weixin_flag.avatar,weixin_redpacket_users.amount','','assoc','left join weixin_flag on weixin_redpacket_users.userid= weixin_flag.id');
	}
	//获取活动的中奖名单,如果roundid为0，获取所有轮次的中奖名单
	public function getWinners($roundid=0){
		$where=' userid > 0';
		if($roundid>0){
			$where.=' and roundid='.$roundid;
		}
		$where.=' order by roundid asc';
		return $this->_redpacket_users_m->select($where,'weixin_flag.id,weixin_flag.openid,weixin_flag.nickname,weixin_flag.avatar,weixin_redpacket_users.amount,weixin_redpacket_users.status,weixin_redpacket_users.updated_at','','assoc','left join weixin_flag on weixin_redpacket_users.userid= weixin_flag.id');
	}

	//添加一个没有被抢的红包
	public function _addRedpacketUser($data){
		$result=$this->_redpacket_users_m->add($data);
		return $result;
	}
	//初始化红包记录
	public function initRedpacketUsers($amount,$num,$roundid,$minamount=0,$maxmount=0){
		$created_at=time();
		$data=array();
		//如果是随机红包，需要计算每个红包的金额
		if($minamount>0 && $maxmount>0){
			//随机红包
			$left=$amount;
			for($i=0;$i<$num;$i++){
				$data[$i]=$minamount;
				$left=$left-$minamount;
			}
			//金额可变的范围
			$delta=$maxmount-$minamount;
			//剩余金额大于0时分配金额
			while($left>0){
				for($i=0;$i<$num;$i++){
					if($left>0){
						//当次分配的金额
						$deltaamount=rand(0,$delta);
						if($left-$deltaamount<0){//分配的金额不能超过剩余的金额
							$deltaamount=$left;
							$left=0;
						}else{
							$left=$left-$deltaamount;
						}
						if($data[$i]+$deltaamount>$maxmount){//当次的金额加上当次增加的金额不能超出单次红包最大金额
							$left=$left+($data[$i]+$deltaamount-$maxmount);
							$data[$i]=$maxmount;
						}else{
							$data[$i]=$data[$i]+$deltaamount;
						}
					}
				}
			}
		}
		for($i=0;$i<$num;$i++){
			$redpacketuser=array('roundid'=>$roundid,'created_at'=>$created_at);
			$redpacketuser['amount']=empty($data)?$amount:$data[$i];
			$this->_addRedpacketUser($redpacketuser);
		}
	}
}