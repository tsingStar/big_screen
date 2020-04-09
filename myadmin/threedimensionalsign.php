<?php
require_once('Page.php');
class ThreeDimensionalSign extends Page{
	function show(){
		$threedimensional_m=new M('threedimensional');
		$threedimensional=$threedimensional_m->find('1');
		$this->assign('threedimensional_config',$threedimensional);
		$this->display('templates/threedimensionalsign.html');
	}
}
$page=new ThreeDimensionalSign();
$page->setTitle('3D签到设置');
$page->show();
