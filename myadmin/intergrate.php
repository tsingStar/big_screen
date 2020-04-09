<?php
require_once('Page.php');
class Intergrate extends Page{
	function show(){

		$this->_load->model('Weixin_model');
		$weixin_config=$this->_load->weixin_model->getConfig();

		$this->_load->model("System_Config_model");
		$deeja_appid=$this->_load->system_config_model->get('deeja_appid');
		// echo $deeja_appid['configvalue'];
		$this->assign('deeja_appid',$deeja_appid['configvalue']);
		$this->assign('weixin_config',$weixin_config);

		$this->assign('domain',$_SERVER['HTTP_HOST']);
		$this->display('templates/intergrate.html');
	}
}
$page=new Intergrate();
$page->setTitle('对接设置');
$page->show();
