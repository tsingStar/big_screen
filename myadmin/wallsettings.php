<?php
require_once('Page.php');
class WallSettings extends Page{
	function show(){

		$this->_load->model('System_Config_model');
 		$data=$this->_load->system_config_model->get("wallnameshowstyle");

		$wallnameshowstyle=intval($data['configvalue']);
		//上墙名称显示方式
		$this->assign('wallnameshowstyle',$wallnameshowstyle);
		$this->display('templates/wallsettings.html');
	}
}
$blank=new WallSettings();
$blank->setTitle('上墙设置');
$blank->show();
