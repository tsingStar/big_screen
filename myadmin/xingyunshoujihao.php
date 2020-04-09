<?php
//幸运手机号中奖列表
require_once('Page.php');
class Xingyunshoujihao extends Page{
	function show(){
		$xingyunshoujihao_m=new M('xingyunshoujihao');
		//中奖列表
		
		$xingyunshoujihao=$xingyunshoujihao_m->select('weixin_xingyunshoujihao.status=2 order by weixin_xingyunshoujihao.ordernum desc','weixin_xingyunshoujihao.id,weixin_xingyunshoujihao.status,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.phone','','assoc','left join weixin_flag on weixin_flag.openid=weixin_xingyunshoujihao.openid');
		$statustext=array();
		$statustext=array('未中','未中','已中奖');
		foreach($xingyunshoujihao as $k=>$v){
			$xingyunshoujihao[$k]['statustext']=$statustext[intval($v['status'])];
			$xingyunshoujihao[$k]['nickname']=pack('H*',$v['nickname']);
		}
		
		$this->assign('xingyunshoujihao',$xingyunshoujihao);
		$this->display('templates/xingyunshoujihao.html');
	}
}
$page=new Xingyunshoujihao();
$page->setTitle('幸运手机号中奖记录');
$page->show();
