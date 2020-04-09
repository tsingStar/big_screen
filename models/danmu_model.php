<?php 
//弹幕,主要是弹幕配置，弹幕内容和微信上墙是同一个功能的
class Danmu_model {

	var $_danmu_config_m=null;

	function __construct(){
		$this->_danmu_config_m=new M('danmu_config');
	}
	//获取配置信息
	function getConfig(){
		$danmu_config=$this->_danmu_config_m->find('1');
		unset($danmu_config['id']);
		if($danmu_config['looptime']<=0){
			$danmu_config['looptime']=3;
		}
		return $danmu_config;
	}
}