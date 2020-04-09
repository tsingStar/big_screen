<?php
require_once('Page.php');
class Xingyunhaoma extends Page{
	function show(){
		$xingyunhaoma_m=new M('xingyunhaoma');
		$data=$xingyunhaoma_m->select('designated>=2 order by ordernum asc');
		$designatedtext=array('','','必中','不会中');
		$statustext=array('','未中','中奖');
		foreach($data as $k=>$v){
			$data[$k]['statustext']=$statustext[$v['status']];
			$data[$k]['designatedtext']=$designatedtext[$v['designated']];
		}
		$this->assign('xingyunhaoma', $data);
		$this->display('templates/xingyunhaomadesignated.html');
	}
}
$page=new Xingyunhaoma();
$page->setTitle('幸运号码内定');
$page->show();
