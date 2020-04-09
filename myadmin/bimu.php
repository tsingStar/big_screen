<?php
require_once('Page.php');
class Bimu extends Page{
	function show(){
		$bimu_config_m=new M('bimu_config');
		$bimu_config=$bimu_config_m->find('1');
		$bimuimage='';
		$this->_load->model('Attachment_model');
		if(isset($bimu_config) && !empty($bimu_config['imagepath'])){
			$img=$this->_load->attachment_model->getById(intval($bimu_config['imagepath']));
			$bimuimage=$img['filepath'];
		}
		$bimu_config['image']=$bimuimage;
		$this->assign('bimu_config',$bimu_config);
		//$this->assign('bimuimage',$bimuimage);
		$this->display('templates/bimu.html');
	}
}
$page=new Bimu();
$page->setTitle('闭幕墙');
$page->show();
