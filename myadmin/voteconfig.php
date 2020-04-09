<?php
require_once('Page.php');
class Voteconfig extends Page{
	function show(){
		$vote_config=new M('vote_config');
		$votes=$vote_config->select("1 order by id asc");
		$statustext_arr=array('',"进行中","结束");
		$showtypetext_arr=array('',"横向","纵向","图片");
		$editabletext_arr=array('',"不可修改","可改投");
		foreach($votes as $k=>$v){
			$votes[$k]['statustext']=$statustext_arr[$v['status']];
			$votes[$k]['showtypetext']=$showtypetext_arr[$v['showtype']];
			$votes[$k]['editabletext']=$editabletext_arr[$v['editable']];
		}
		$this->assign('votes',$votes);
		$this->display('templates/voteconfig.html');
	}
}
$page=new Voteconfig();
$page->setTitle('投票设置');
$page->show();
