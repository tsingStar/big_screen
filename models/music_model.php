<?php 
class Music_model{

	var $_music_m=null;

	function __construct(){
		$this->_music_m=new M('music');
	}

	//获取所有音乐数据
	public function getAll(){
		$musics=$this->_music_m->select('1');
		foreach($musics as $key=>$val){
			$musics[$key]['bgmusicpath']=$this->_setmusicpath($val['bgmusic']);
		}
		return $musics;
	}

	public function getMusicJson(){
		$music_arr=array();
		$data=$this->getAll();
		foreach($data as $val){
			$newitem=array(
				'bgmusicstatus'=>$val['bgmusicstatus'],
				'bgmusicpath'=>$val['bgmusicpath'],
			);
			$music_arr[$val['plugname']]=$newitem;
		}
		return json_encode($music_arr);
	}
	//设置音乐路径
	private function _setmusicpath($attachmentid=0){
		if(empty($attachmentid)){
			return '/wall/themes/meepo/assets/music/Radetzky_Marsch.mp3';
		}
		return '/imageproxy.php?id='.$attachmentid;
	}
}