<?php
//幸运手机号内定设置
require_once('Page.php');
class Xingyunshoujihaodesignated extends Page{
	function show(){
		$flag_m=new M('flag');
		$flag=$flag_m->select('phone !="" and !ISNULL(phone) and (isnull(weixin_xingyunshoujihao.status) or weixin_xingyunshoujihao.status=1) and (weixin_xingyunshoujihao.designated=3 or isnull(weixin_xingyunshoujihao.designated))',
				'weixin_xingyunshoujihao.status,weixin_flag.phone,weixin_flag.avatar,weixin_flag.nickname,weixin_flag.openid',
				'','assoc','left join weixin_xingyunshoujihao on weixin_xingyunshoujihao.openid=weixin_flag.openid');
		$statustext=array('未中','未中','已中奖');
		foreach($flag as $k=>$v){
			$flag[$k]['statustext']=$statustext[intval($v['status'])];
			$flag[$k]['nickname']=pack('H*',$v['nickname']);
		}
		$this->assign('flag',$flag);
		$this->display('templates/xingyunshoujihaodesignated.html');
	}
}
$page=new Xingyunshoujihaodesignated();
$page->setTitle('幸运手机号内定设置');
$page->show();
