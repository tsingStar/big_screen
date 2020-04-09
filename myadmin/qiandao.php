<?php
require_once('Page.php');
require_once '..' . DIRECTORY_SEPARATOR .'library'.DIRECTORY_SEPARATOR.'emoji'.DIRECTORY_SEPARATOR.'emoji.php';
class Qiandao extends Page{
	function show(){
		$page=isset($_GET['page'])?intval($_GET['page']):1;
		$search=isset($_GET['search'])?strval($_GET['search']):'';
		$page=$page<1?1:$page;
		$pagesize=20;
		require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'flag_model.php');
		$flag_model=new Flag_model();

		$searchwhere='';
		if(!empty($search)){
			$searchwhere='(nickname like "%'.bin2hex($search).'%" or signname like "%'.$search.'%" or phone like "%'.$search.'%") and ';
		}
		$where=$searchwhere.'1 and flag=2 order by id desc';
		$returndata=$flag_model->getPagedData($where,$page,$pagesize,true);
		
		$baseurl='qiandao.php'.(empty($search)?'':'?search='.$search);
		
		// $award_m=new M("award");
		// $awardlist=$award_m->select(' isdel=1 ');

		$this->_load->model('Plugs_model');
		$cjplugs=$this->_load->plugs_model->getChoujiangPlug();
		
		// echo var_export($returndata['data']);
		// $this->assign('cjplugs', $cjplugs);
		$this->assign('pagehtml',$this->pagerhtml($page,$pagesize,$returndata['count'],$baseurl));
		$this->assign('searchtext',$search);
		// $this->assign('awardlist',json_encode($awardlist));
		$this->assign('flaglist',$this->processflag($returndata['data']));
		$this->display('templates/qiandao.html');
	}

	function processflag($flaglist){
		$newflaglist=array();
		foreach($flaglist as $item){
			$newitem['openid']=$item['openid'];
			$newitem['nickname']=emoji_unified_to_html(emoji_softbank_to_unified(pack('H*', $item['nickname'])));
			$newitem['avatar']=$item['avatar'];
			$newitem['phone']=$item['phone'];
			$newitem['signname']=$item['signname'];
			$newitem['status']=$item['status'];
			$newitem['extentions']=$item['extentions'];
			$newflaglist[]=$newitem;
		}
		return $newflaglist;
	}
}

$page=new Qiandao();
$page->setTitle('签到管理');
$page->show();
