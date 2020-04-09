<?php
require_once('Page.php');
class MobileQiandao extends Page{
	function show(){
		$this->_load->model('System_Config_model');
		$data=$this->_load->system_config_model->get("mobileqiandaobg");
		$mobilemenufontcolor=$this->_load->system_config_model->get("mobilemenufontcolor");
		$this->_load->model('Attachment_model');
		$mobileqiandaobg=$this->_load->attachment_model->getById(intval($data['configvalue']));
		$this->assign('mobilemenufontcolor',isset($mobilemenufontcolor['configvalue'])?$mobilemenufontcolor['configvalue']:'');
		$this->assign('mobileqiandaobg',$mobileqiandaobg['filepath']);
		$this->display('templates/mobileqiandao.html');
	}
}
$page=new MobileQiandao();
$page->setTitle('手机签到页面设置');
$page->show();
