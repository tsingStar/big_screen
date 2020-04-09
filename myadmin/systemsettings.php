<?php

require_once('Page.php');

class SystemSettings extends Page{
	function show(){
		$this->_load->model('Weixin_model');
		$weixin_config=$this->_load->weixin_model->getConfig();
		$this->_load->model("System_Config_model");
		$menucolor=$this->_load->system_config_model->get('menucolor');
		$showcountsign=$this->_load->system_config_model->get('showcountsign');
		$this->assign('menucolor',$menucolor['configvalue']);
		$this->assign('showcountsign',$showcountsign['configvalue']);
		$this->assign('weixin_config',$weixin_config);
		$this->display('templates/systemsettings.html');
	}
}
$page=new SystemSettings();
$page->setTitle('系统设置');
$page->show();
