<?php
require_once('Page.php');
require_once("../common/biaoqing.php");
require_once("../library/emoji/emoji.php");
class WallSettings extends Page{
	function show(){
		$wall_m=new M('wall');
		$pendinglist=$wall_m->select('ret=0 order by id desc');
		$acceptedlist=$wall_m->select('ret=1 order by id desc');
		$refusedlist=$wall_m->select('ret=2 order by id desc');
		$newpendinglist=array();
		foreach($pendinglist as $item){
			$newpendinglist[]=$this->processmessage($item);
		}
		$newacceptedlist=array();
		foreach($acceptedlist as $item){
			$newacceptedlist[]=$this->processmessage($item);
		}
		$newrefusedlist=array();
		foreach($refusedlist as $item){
			$newrefusedlist[]=$this->processmessage($item);
		}
		$lastpendingmessageid=count($newpendinglist)>0?$newpendinglist[0]['id']:0;
		$lastacceptedmessageid=count($newacceptedlist)>0?$newacceptedlist[0]['id']:0;
		$lastrefusedmessageid=count($newrefusedlist)>0?$newrefusedlist[0]['id']:0;
		
		$this->assign('lastpendingmessageid',$lastpendingmessageid);
		$this->assign('lastacceptedmessageid',$lastacceptedmessageid);
		$this->assign('lastrefusedmessageid',$lastrefusedmessageid);
		
		$this->assign('pendinglist',$newpendinglist);
		$this->assign('acceptedlist',$newacceptedlist);
		$this->assign('refusedlist',$newrefusedlist);
		$this->display('templates/wallmessage.html');
	}
	function processmessage($message){
		
		$message['nickname']=pack('H*', $message['nickname']);
		$message['content']=pack('H*', $message['content']);
		$message = emoji_unified_to_html(emoji_softbank_to_unified($message));
		$message['content']=biaoqing($message['content']);
		$message['type']=1;
		if(!empty($message['image'])){
			$message['type']=2;
			$message['content']=$message['image'];
		}
		$newmessage=array();
		$newmessage['id']=$message['id'];
		$newmessage['content']=$message['content'];
		$newmessage['type']=$message['type'];
		$newmessage['nickname']=$message['nickname'];
		$newmessage['avatar']=$message['avatar'];
		$newmessage['openid']=$message['openid'];
		return $newmessage;
	}
}
$blank=new WallSettings();
$blank->setTitle('消息列表');
$blank->show();