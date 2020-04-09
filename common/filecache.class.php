<?php
//$fzz = new file_cache;
//$fzz->kk = $_SERVER; //写入缓存
//$fzz->set("kk",$_SERVER,10000); //此方法不与类属性想冲突，可以用任意缓存名；
//print_r($fzz->kk);  //读取缓存
//print_r($fzz->get("kk"));
//unset($fzz->kk); //删除缓存
//$fzz->_unset("kk");
//var_dump(isset($fzz->kk)); //判断缓存是否存在
//$fzz->_isset("kk");
//$fzz->clear(); //清理过期缓存
//$fzz->clear_all(); //清理所有缓存文件
if(!class_exists('file_cache'))
{
	class file_cache
	{
		public $limit_time = 20000; //缓存过期时间
		public $cache_dir = "data"; //缓存文件保存目录
		public function __construct(){
			$apppath=str_replace(DIRECTORY_SEPARATOR.'common', '', dirname(__FILE__));
			$this->cache_dir=$apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'filecache'.DIRECTORY_SEPARATOR.trim(CACHEPREFIX,'_');
		}
		//写入缓存
		function __set($key, $val)
		{
			$this->set($key, $val);
		}
		
		//第三个参数为过期时间
		function set($key, $val, $limit_time = null)
		{
			$this->local_mkdirs($this->cache_dir);
			$limit_time = $limit_time ? $limit_time : $this->limit_time;
			$file = $this->cache_dir . "/" . $key . ".cache";
			$val = serialize($val);
			@file_put_contents($file, $val) or $this->error(__line__, "fail to write in file");
			@chmod($file, 0777);
			@touch($file, time() + $limit_time) or $this->error(__line__, "fail to change time");
		}
		
		//读取缓存
		function __get($key)
		{
			return $this->get($key);
		}
		
		function get($key)
		{
			$file = $this->cache_dir . "/" . $key . ".cache";
			// echo $file;
			if(!file_exists($file)){
				return false;
			}
			if (@filemtime($file)+$this->limit_time >= time()) {
				return unserialize(file_get_contents($file));
			} else {
				@unlink($file) or $this->error(__line__, "fail to unlink");
				return false;
			}
		}
		
		//删除缓存文件
		function __unset($key)
		{
			return $this->delete($key);
		}
		
		function delete($key)
		{
			$path=$this->cache_dir . "/" . $key . ".cache";
			if(!file_exists($path)){
				return true;
			}
			if (@unlink($path)) {
				return true;
			} else {
				return false;
			}
		}
		
		//检查缓存是否存在，过期则认为不存在
		function __isset($key)
		{
			return $this->_isset($key);
		}
		
		function _isset($key)
		{
			$file = $this->cache_dir . "/" . $key . ".cache";
			if (@filemtime($file)+$this->limit_time >= time()) {
				return true;
			} else {
				@unlink($file);
				return false;
			}
		}
		
		//清除过期缓存文件
		function clear()
		{
			$files = scandir($this->cache_dir);
			foreach ($files as $val) {
				if (filemtime($this->cache_dir . "/" . $val)+$this->limit_time < time()) {
					@unlink($this->cache_dir . "/" . $val);
				}
			}
		}
		
		//清除所有缓存文件
		function clear_all()
		{
			$files = scandir($this->cache_dir);
			foreach ($files as $val) {
				@unlink($this->cache_dir . "/" . $val);
			}
		}
		
		function error($msg, $debug = false)
		{
			$err = new Exception($msg);
			$str = "<pre>
<span style='color:red'>error:</span>
" . print_r($err->getTrace(), 1) . "
</pre>";
			if ($debug == true) {
				return $str;
			} else {
				die($str);
			}
		}
		function local_mkdirs($path)
		{
			if (!is_dir($path)) {
				$this->local_mkdirs(dirname($path));
				mkdir($path);
			}
			return is_dir($path);
		}
		function quit(){
			return true;
		}
	}
}
