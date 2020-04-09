<?php
require_once('Page.php');
class Blank extends Page{
	function show(){
		$this->display('templates/blank.html');
	}
}
$page=new Blank();
$page->setTitle('空页面');
$page->show();
