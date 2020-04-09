<?php
require_once('Page.php');
require_once('../common/url_helper.php');
class Index extends Page{
	function show(){
		$this->_load->model('Weixin_model');
		$weixin_config=$this->_load->weixin_model->getConfig();
		$this->assign('scheme',request_scheme());
		// echo var_export($weixin_config);
		$this->assign('weixin_config',$weixin_config);
		$this->display('templates/index.html');
	}
}
$page=new Index();
$page->setTitle('首页');
$page->show();
