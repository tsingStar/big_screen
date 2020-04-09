<?php
require_once('Page.php');
class Xingyunhaomaconfig extends Page{
	function show(){
		$xingyunhaomaconfig_m=new M('xingyunhaoma_config');
		$data=$xingyunhaomaconfig_m->find('1=1 limit 1');
		$this->assign('xingyunhaomaconfig', $data);
		$this->display('templates/xingyunhaomaconfig.html');
	}
}
$page=new Xingyunhaomaconfig();
$page->setTitle('幸运号码配置');
$page->show();
