<?php

/**
 * redis操作类
 * 说明，任何为false的串，存在redis中都是空串。
 * 只有在key不存在时，才会返回false。
 * 这点可用于防止缓存穿透
 *
 */
// echo dirname(__FILE__);
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'RedisCluster.class.php';
use common\RedisCluster;
class RedisCache
{
    var $_redis;//=new RedisCluster();
    public function __construct(){
        $this->_redis=new RedisCluster();
        $this->_redis->connect(array('host'=>REDIS_HOST,'port'=>REDIS_PORT,'pwd'=>REDIS_PASSWORD,'persist'=>false));
    }

    //写入缓存
    function __set($key, $val)
    {
        $this->set($key, $val);
    }

    //第三个参数为过期时间
    function set($key, $val, $limit_time = null)
    {
        
        $this->_redis->set(CACHEPREFIX.$key,serialize($val),$limit_time);
    }

    //读取缓存
    function __get($key)
    {
        return $this->get($key);
    }

    function get($key)
    {
        return  unserialize($this->_redis->get(CACHEPREFIX.$key));
    }

    //删除缓存文件
    function __unset($key)
    {
        return $this->delete($key);
    }

    function delete($key)
    {
        return $this->_redis->remove(CACHEPREFIX.$key);
    }

    //检查缓存是否存在，过期则认为不存在
    // function __isset($key)
    // {
    //     return $this->_isset(CACHEPREFIX.$key);
    // }

    // function _isset($key)
    // {
    //     return;
    // }

    //清除过期缓存文件
    function clear()
    {
        return $this->_redis->clear();
    }

    //清除所有缓存文件
    public function clear_all()
    {
        return $this->clear();
    }

//     function error($msg, $debug = false)
//     {
//         $err = new Exception($msg);
//         $str = "<pre>
// <span style='color:red'>error:</span>
// " . print_r($err->getTrace(), 1) . "
// </pre>";
//         if ($debug == true) {
//             file_put_contents(date('Y-m-d H_i_s') . ".log", $str);
//             return $str;
//         } else {
//             die($str);
//         }
//     }
    
    function quit(){
        return $this->_redis->close(0);
    }
}