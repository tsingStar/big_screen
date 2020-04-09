<?php
require_once('Page.php');
class DanmuSettings extends Page{
	function show(){
		$danmu_config_m=new M('danmu_config');
		$danmu_config=$danmu_config_m->find('1');
		$this->assign('danmu_config',$danmu_config);
		$this->display('templates/danmusettings.html');
	}
}
$blank=new DanmuSettings();
$blank->setTitle('弹幕设置');
$blank->show();
