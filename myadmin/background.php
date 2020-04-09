<?php
require_once('Page.php');
class Background extends Page{
	function show(){
		$this->_load->model('Background_model');
		$images=$this->_load->background_model->getAll();
		// echo var_export($images);
		$this->assign('images',$images);
		$this->display('templates/background.html');
	}
}
$page=new Background();
$page->setTitle('背景设置');
$page->show();
