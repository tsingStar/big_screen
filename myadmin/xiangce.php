<?php
require_once('Page.php');
class Blank extends Page{
	function show(){
		$page=isset($_GET['page'])?intval($_GET['page']):1;
		$page=$page<1?1:$page;
		$pagesize=20;
		$xiangce_m=new M('xiangce');
		$xiangce=$xiangce_m->select('1 order by id desc limit '.(($page-1)*$pagesize).','.$pagesize);
		$this->_load->model('Attachment_model');
		foreach($xiangce as $k=>$v){
			$image=$this->_load->attachment_model->getById($v['imagepath']);
			$xiangce[$k]['imagepath']=$image['filepath'];
		}
		$xiangcecount=$xiangce_m->select(' 1 ','*','count');
		$baseurl='xiangce.php';
		$this->assign('xiangce',$xiangce);
		$this->assign('pagehtml',$this->pagerhtml($page,$pagesize,$xiangcecount,$baseurl));
		$this->display('templates/xiangce.html');
	}
}
$page=new Blank();
$page->setTitle('相册管理');
$page->show();
