<?php
require_once('Page.php');
class Kaimu extends Page{
	function show(){
		$kaimu_config_m=new M('kaimu_config');
		$kaimu_config=$kaimu_config_m->find('1');
		$kaimuimage='';
		$this->_load->model('Attachment_model');
		if(isset($kaimu_config) && !empty($kaimu_config['imagepath'])){
			$img=$this->_load->attachment_model->getById(intval($kaimu_config['imagepath']));
			$kaimuimage=$img['filepath'];
		}
		$kaimu_config['image']=$kaimuimage;
		$this->assign('kaimu_config',$kaimu_config);
		$this->display('templates/kaimu.html');
	}
}
$page=new Kaimu();
$page->setTitle('开幕墙设置');
$page->show();
