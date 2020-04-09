<?php
require_once('Page.php');
class WallNotice extends Page{
	function show(){
		$this->display('templates/wallnotice.html');
	}
}
$page=new WallNotice();
$page->setTitle('发布公告');
$page->show();
