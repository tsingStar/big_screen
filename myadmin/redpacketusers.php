<?php
require_once('Page.php');
class Redpacketusers extends Page{
	function show(){
		$roundid=isset($_GET['roundid'])?intval($_GET['roundid']):0;
		$this->_load->model('Redpacket_model');
		$redpacket_users=$this->_load->redpacket_model->getWinners($roundid);
		$redpacket_users=$this->processuserlist($redpacket_users);
		$this->assign('roundid',$roundid);
		$this->assign('redpacketusers',$redpacket_users);
		$this->display('templates/redpacketusers.html');
	}
	//处理人员名单
	function processuserlist($redpacket_users){
		$newredpacket_users=array();
		$statustext_arr=array('','未发','发放中','已发','发放失败');
		foreach($redpacket_users as $k=>$v){
			$row=array(
					// 'id'=>$v['id'],
					'userid'=>$v['id'],
					'openid'=>$v['openid'],
					// 'roundid'=>$v['roundid'],
					'amount'=>($v['amount']/100).'元',
					'nickname'=>pack('H*', $v['nickname']),
					'avatar'=>$v['avatar'],
			);
			$row['statustext']=$statustext_arr[$v['status']];
			$row['updated_at']=empty($v['updated_at'])?'':date('Y-m-d H:i:s',$v['updated_at']);
			$newredpacket_users[]=$row;
		}
		return $newredpacket_users;
	}
}
$page=new Redpacketusers();
$page->setTitle('红包用户列表');
$page->show();