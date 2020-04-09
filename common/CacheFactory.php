<?php
/**
 * 缓存
 * PHP version 5.4+
 * 
 * @category Common
 * 
 * @package Cache
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'config.php');
class CacheFactory{
	var $mem;
	var $cachetype;
	function __construct($type='file'){
		$this->cachetype=$type;
		if($type=='file'){
			//文件式缓存
			require_once('filecache.class.php');
			$this->mem= new file_cache;
		}else if($type=='memcache'){
			//默认使用阿里云的memcached 缓存
			require_once('memcached.class.php');
			//memcached缓存
			$this->mem= new memcached_cache;
		}else{
			// echo 1;exit();
			require_once('rediscache.class.php');
			$this->mem= new RedisCache;
		}
	}
	public function set($key,$val,$limittime=3600){
		return $this->mem->set($key,$val,$limittime);
	}
	public function get($key){
		return $this->mem->get($key);
	}
	public function clear_all(){
		return $this->mem->clear_all();
	}
	public function quit(){
		if($this->cachetype!='file'){
			return $this->mem->quit();
		}
		return true;
	}
	public function delete($key){
		return $this->mem->delete($key);
	}
}