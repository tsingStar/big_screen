<?php
require_once('Page.php');
class Functionswitch extends Page{
	function show(){
		$this->_load->model('Plugs_model');
		$plugs=$this->_load->plugs_model->getPlugs();
		// echo var_export($plugs);
		foreach($plugs as $k=>$v){
			$plugs[$k]['key']=str_replace('ctrl+', '', $v['hotkey']);
		}
		
		$this->assign('plugsswitch',$plugs);
		$this->display('templates/functionswitch.html');
	}
}
$page=new Functionswitch();
$page->setTitle('功能开关');
$page->show();
