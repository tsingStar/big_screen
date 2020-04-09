<?php
require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'basemodel.php');
class Weixin_model extends Basemodel{
	var $_weixin_config_m=null;
	var $_weixin_config_cache_name='weixin_config';
	var $_weixin_config_cache=null;
	public function __construct(){
		parent::__construct();
		$this->_weixin_config_m=new M('weixin_config');
		$this->_weixin_config_cache=new CacheFactory(CACHEMODE);
	}

    public function setConfig($config){
        $result=$this->_weixin_config_m->update('1',$config);
        if($result){
            $this->_weixin_config_cache->delete($this->_weixin_config_cache_name);
        }
        return $result;
    }

	public function getConfig(){
        $weixin_config=$this->_weixin_config_cache->get($this->_weixin_config_cache_name);
        if(!$weixin_config){
            $weixin_config = $this->_weixin_config_m->find('1');
            $this->_weixin_config_cache->set($this->_weixin_config_cache_name,$weixin_config,600);
        }
        $this->_load->model('Attachment_model');
        if($weixin_config['erweima']>0){
        	$erweima=$this->_load->attachment_model->getById($weixin_config['erweima']);
        	$weixin_config['erweima']=$erweima['type']==1?$erweima['filepath']:'/imageproxy.php?id='.$erweima['id'];
        }else{
        	$weixin_config['erweima']='';
        }

        $path=dirname(__FILE__) .DIRECTORY_SEPARATOR.'..';//.DIRECTORY_SEPARATOR.'data';
        require_once(dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'function.php');
        $apiclient_cert=$this->_load->attachment_model->getById($weixin_config['apiclient_cert']);
        $weixin_config['apiclient_cert']=$apiclient_cert['type']==1?($path.$apiclient_cert['filepath']):writecert($apiclient_cert['filepath'],'cert.pem');
        
        $apiclient_key=$this->_load->attachment_model->getById($weixin_config['apiclient_key']);
        $weixin_config['apiclient_key']=$apiclient_key['type']==1?($path.$apiclient_key['filepath']):writecert($apiclient_key['filepath'],'key.pem');
        return $weixin_config;
	}
}