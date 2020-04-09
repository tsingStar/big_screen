<?php
require_once('Page.php');
class Redpacketconfig extends Page{
	function show(){
		$this->_load->model('Redpacket_model');
		$redpacket_config=$this->_load->redpacket_model->getRedpacketConfig();
		$this->assign('redpacket_config', $redpacket_config);
		$this->display('templates/redpacketconfig.html');
	}
}
$page=new Redpacketconfig();
$page->setTitle('红包配置页面');
$page->show();
