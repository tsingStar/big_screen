<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'CacheFactory.php');
class System_Config_model{
	var $_systemconfig_m=null;
	var $_configcachename='system_config';
	function __construct(){
		$this->_systemconfig_m=new M('system_config');
	}
	//设置配置信息
	function set($key,$val,$name='',$comment=''){
		$data=array('configvalue'=>$val);
		if($name!=''){
			$data['configname']=$name;
		}
		if($comment!=''){
			$data['configcomment']=$comment;
		}
		$system_config_m = new M('system_config');
		$result=$system_config_m->update('configkey="'.$key.'"', $data);
		// echo var_export($result);
		if($result){
			$cache=new CacheFactory(CACHEMODE);
			$cache->delete($this->_configcachename);
		}
		return $result;
	}
	//按照key获得配置数组
	function get($key){
		$system_config=$this->getAll();
		return $system_config[$key];
	}
	//获取所有配置信息
	private function getAll(){
		$cache=new CacheFactory(CACHEMODE);
		$system_config=$cache->get($this->_configcachename);
		if(empty($system_config)){
			$data=$this->_systemconfig_m->select(' 1 ');
			$newdata=array();
			foreach($data as $k=>$v){
				$newdata[$v['configkey']]=$v;
			}
			$system_config=$newdata;
			$cache->set($this->_configcachename,$system_config);
		}
		return $system_config;
	}
}