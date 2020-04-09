<?php
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'basemodel.php');
//微信墙
class Wall_model extends Basemodel{
	var $_wall_m=null;
	var $_wall_config_m=null;
	var $_wall_config_cache_name='wall_config';
	var $_wall_config_cache=null;
	
	public function __construct(){
        parent::__construct();
		$this->_wall_m=new M('wall');
		$this->_wall_config_m=new M('wall_config');
		$this->_wall_config_cache=new CacheFactory(CACHEMODE);
	}

    //添加一条消息
    public function addMessage($message){
        return $this->_wall_m->add($message);
    }
    
    //取最后一条消息
    public function getLastMessage($openid=''){
        $where=' 1 ';
        if($openid!=''){
            $where.=' and openid="'.$openid.'"';
        }
        $where.=' order by datetime desc limit 1';
        $message=$this->_wall_m->find($where);
        return $message;
    }
    //获取openid对应的历史记录
    public function getHistoryByOpenid($openid){
        return $this->_wall_m->select ( 'openid="' . $openid . '" order by datetime desc' );
    }
    //获取最新的大屏幕显示信息
    public function getWallMessage($shenhetime,$limit=100){
        $data=$this->_wall_m->select('shenhetime > '.$shenhetime.' and ret=1 order by shenhetime desc limit '.$limit,'weixin_wall.nickname,weixin_wall.avatar,weixin_wall.content,weixin_wall.image,weixin_flag.phone,weixin_flag.signname,weixin_wall.shenhetime','','','left join weixin_flag on weixin_flag.openid=weixin_wall.openid');
        return $data;
    }
    //设置wall_config
    public function setConfig($data){

        $result=$this->_wall_config_m->update('1',$data);
        if($result){
            $this->_wall_config_cache->delete($this->_wall_config_cache_name);
        }
        return $result;
    }
	//获取wall_config
	public function getConfig(){
		$wall_config=$this->_wall_config_cache->get($this->_wall_config_cache_name);
        if(!$wall_config){
            $this->_wall_config_m= new M('wall_config');
            $wall_config = $this->_wall_config_m->find();
            $this->_wall_config_cache->set($this->_wall_config_cache_name,$wall_config,600);
        }
        
        $this->_load->model('Attachment_model');
        // if($wall_config['bgimg']>0){
        // 	$bgimg=$this->_load->attachment_model->getById($wall_config['bgimg']);
        // 	$wall_config['bgimg']=$bgimg['type']==1?$bgimg['filepath']:'/imageproxy.php?id='.$bgimg['id'];
        // }else{
        // 	$wall_config['bgimg']='';
        // }
        
        if($wall_config['logoimg']>0){
        	$bgimg=$this->_load->attachment_model->getById($wall_config['logoimg']);
        	$wall_config['logoimg']=$bgimg['type']==1?$bgimg['filepath']:'/imageproxy.php?id='.$bgimg['id'];
        }else{
        	$wall_config['logoimg']='';
        }
        if($wall_config['bottom_logoimg']>0){
        	$bgimg=$this->_load->attachment_model->getById($wall_config['bottom_logoimg']);
        	$wall_config['bottom_logoimg']=$bgimg['type']==1?$bgimg['filepath']:'/imageproxy.php?id='.$bgimg['id'];
        }else{
        	$wall_config['bottom_logoimg']='';
        }
        return $wall_config;
	}

	//根据openid删除上墙数据
	public function deleteWallMessageByOpenid($openid){
		return $this->_wall_m->delete('`openid`="'.$openid.'"');
	}
	
	//清空上墙数据
	public function clearWallMessage(){
		return $this->_wall_m->query('truncate table weixin_wall');
	}

}