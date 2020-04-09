<?php
require_once('Page.php');
class Xingyunhaoma extends Page{
	function show(){
		$xingyunhaoma_m=new M('xingyunhaoma');
		$data=$xingyunhaoma_m->select('status=2 order by ordernum asc');
		$designatedtext=array('','','必中','不会中');
		foreach($data as $k=>$v){
			$data[$k]['designatedtext']=$designatedtext[$v['designated']];
		}
		$this->assign('xingyunhaoma', $data);
		$this->display('templates/xingyunhaoma.html');
	}
}
$page=new Xingyunhaoma();
$page->setTitle('幸运号码中奖');
$page->show();
