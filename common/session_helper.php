<?php
require 'db.class.php';
require_once 'utilities.php';
// require_once 'CacheFactory.php';
if (interface_exists('SessionHandlerInterface')) {
    class MySessionHandler implements SessionHandlerInterface
    {
        var $session_m;
        function open($save_path,$sessionName)
        {
            
            $this->ttl = ini_get('session.gc_maxlifetime');
            $this->session_m=new M('sessions');
            return true;
        }

        function close()
        {
            return true;
        }

        function read($id)
        {
            $data=$this->session_m->find('session_id="'.$id.'"', 'user_data');
            if ($data) {
                return $data['user_data'];
            }
            return '';
        }
        /**
         * 写入缓存
         * 
         * @param int   $id   id
         * @param array $data 缓存数据
         * 
         * @return bool true成功 false失败
         */
        function write($id,$data)
        {
            $old_session_data=$this->session_m->find('session_id="'.$id.'"', 'last_activity,user_data');
            if (!$old_session_data) {
                $session_data=array(
                    'session_id'=>$id,
                    'ip_address'=>GetIp(),
                    'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
                    'last_activity'=>time(),
                    'user_data'=>$data
                );
                $return=$this->session_m->add($session_data);
                return $return>0?true:false;
            } else {
                if ($old_session_data['user_data']==$data) {
                    //如果数据没有变化
                    $last_activity=$old_session_data['last_activity'];
                    $now=time();
                    //如果数据接近过期（小于过期的20%时间）更新，否则直接使用
                    if ($now-$last_activity>$this->ttl*0.8) {
                        $session_data=array(
                            'ip_address'=>GetIp(),
                            'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
                            'last_activity'=>$now
                        );
                        return $this->session_m->update('session_id="'.$id.'"', $session_data);
                    }
                } else {
                    //数据有变化就更新
                    $session_data=array(
                        'ip_address'=>GetIp(),
                        'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
                        'last_activity'=>time(),
                        'user_data'=>$data
                    );
                    // $this->log('update 2');
                    return $this->session_m->update('session_id="'.$id.'"', $session_data);
                }
                
            }
        }
        function destroy($id)
        {
            return $this->session_m->delete('session_id="'.$id.'"');
        }
        function gc($maxlifetime)
        {
            return $this->session_m->delete('last_activity<'.(time()-$maxlifetime));
        }


        
    }
    // ini_set('session.save_handler', 'user');
    $dbsessionhandler=new MySessionHandler();
    //传入实例的方式，可能在一些环境中无法正常运行，
    //session_set_save_handler($dbsessionhandler,true);  
    //还是使用保守的session_set_save_handler调用方式
    // session_set_save_handler(
    //     array($dbsessionhandler,'open'),
    //     array($dbsessionhandler,'close'),
    //     array($dbsessionhandler,'read'),
    //     array($dbsessionhandler,'write'),
    //     array($dbsessionhandler,'destroy'),
    //     array($dbsessionhandler,'gc')
    // );  
    session_set_save_handler($dbsessionhandler,true);
}
session_start();
/*
CREATE TABLE `tb_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '0',
  `user_agent` varchar(200) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
*/