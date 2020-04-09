<?php
require_once('Page.php');
class Music extends Page{
	function show(){
		$music_m=new M('music');
		$bgmusics=$music_m->select('1');
		$this->_load->model('Attachment_model');
		foreach($bgmusics as $k=>$v){
			$fileinfo=$this->_load->attachment_model->getById($v['bgmusic']);
			$bgmusics[$k]['bgmusicpath']=$fileinfo['filepath'];
		}
		$this->assign('maxfilesize',ini_get('upload_max_filesize'));
		$this->assign('bgmusics',$bgmusics);
		$this->display('templates/music.html');
	}
}
$page=new Music();
$page->setTitle('系统配乐');
$page->show();
