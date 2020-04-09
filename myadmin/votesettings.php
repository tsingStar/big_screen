<?php
require_once('Page.php');
class Blank extends Page{
	function show(){
		$this->display('templates/votesettings.html');
	}
}
$page=new Blank();
$page->setTitle('投票设置');
$page->show();
