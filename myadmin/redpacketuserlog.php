<?php
require_once('Page.php');
class Redpacketuserlog extends Page{
	function show(){
		//用户id
		$userid=isset($_GET['userid'])?intval($_GET['userid']):0;
		if($userid==0){
			header('location:redpacket.php');
		}
		$this->_load->model('Flag_model');
		
		//获取用户信息
		$userinfo=$this->_load->flag_model->getUserinfoById($userid);

		$this->_load->model('Redpacket_model');
		//获取用户所的红包的信息
		$redpacket_users=$this->_load->redpacket_model->getRedpacketsByUserid($userid);
		//统计
		//总红包数
		$redpacket_count=count($redpacket_users);
		//总金额
		$total_amount=0;
		
		foreach($redpacket_users as $v){
			$total_amount+=($v['amount']/100);
			
		}

		$redpacket_users=$this->processuserlist($redpacket_users);


		$this->_load->model('Weixin_model');
		$weixin_config=$this->_load->weixin_model->getConfig();


		// $total_amount=($total_amount/100);
		//获取该用户的红包发放记录
		$redpacketsendlog= $this->_load->redpacket_model->getRedpacketSendingLog($userinfo['openid']);

		$total_sendedamount=0;
		foreach($redpacketsendlog as $v){
			if(trim($v['err_code_des'])=='发放成功'){
				$total_sendedamount=$total_sendedamount+($v['total_amount']/100);
			}
		}

		$redpacketsendlog=$this->processlog($redpacketsendlog);
		//发放结果
		$this->assign('weixin_config',$weixin_config);
		$this->assign('redpacket_count',$redpacket_count);
		$this->assign('total_amount',$total_amount);
		$this->assign('total_sendedamount',$total_sendedamount);

		$this->assign('redpacketusers',$redpacket_users);
		$this->assign('redpacketsendlog',$redpacketsendlog);
		$this->display('templates/redpacketuserlog.html');
	}
	function processlog($log){
		foreach($log as $k=>$v){
			$log[$k]['amount']=($v['total_amount']/100).'元';
		}
		return $log;
	}
	//处理人员名单
	function processuserlist($redpacket_users){
		$newredpacket_users=array();
		$statustext_arr=array('','未发','发放中','已发','发放失败','等待发放中');
		foreach($redpacket_users as $k=>$v){
			$row=array(
					// 'id'=>$v['id'],
					'userid'=>$v['userid'],
					'openid'=>$v['openid'],
					'roundid'=>$v['roundid'],
					'amount'=>($v['amount']/100),
					'nickname'=>pack('H*', $v['nickname']),
					'avatar'=>$v['avatar'],
					'status'=>$v['status']
			);
			$row['statustext']=$statustext_arr[$v['status']];
			$row['updated_at']=empty($v['updated_at'])?'':date('Y-m-d H:i:s',$v['updated_at']);
			$newredpacket_users[]=$row;
		}
		return $newredpacket_users;
	}
}
$page=new Redpacketuserlog();
$page->setTitle('用户的红包发放记录');
$page->show();