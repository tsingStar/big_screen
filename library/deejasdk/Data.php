<?php
namespace library\deejasdk;

class Data
{
    protected $params = null;
    protected $appsecret=null;
    protected $appid=null;
    public function setAppId($appid){
        $this->appid=$appid;
    }
    public function setAppSecret($appsecret){
        $this->appsecret=$appsecret;
    }
    public function getAppSecret(){
        return $this->appsecret;
    }
    public function getSign()
    {
        return $this->params['sign'];
    }
    public function SetSign(){
        $sign=$this->makeSign();
        $this->params['sign']=$sign;
        return $sign;
    }
    public function setParams($params){
        $prepared=['time'=>time(),'nonce_str'=>$this->getNonceStr(),'appid'=>$this->appid];
        $this->params=array_merge($prepared,$params);
    }

    public function toUrlParams()
    {
        $str = '';
        foreach ($this->params as $k => $val) {
            if($k != "sign" && $val != "" && !is_array($val)){
                $str .= $k . '=' . $val . '&';
            }
        }
        $str = rtrim($str, '&');
        return $str;
    }

    public function makeSign()
    {   
        ksort($this->params);
        $urlparams=$this->toUrlParams();
        $urlparams=$this->getAppSecret().$urlparams.$this->getAppSecret();
        $encrypedstr=md5($urlparams);
        return $encrypedstr;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getNonceStr($length = 32) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}
}
