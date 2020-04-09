<?php
namespace library\deejasdk;

require_once dirname(__FILE__) . '/Data.php';
require_once dirname(__FILE__) . '/../../common/http_helper.php';

class DeejaSDK
{
    protected $appid = null;
    protected $appsecret = null;
    private $baseurl = 'https://manage.woyaohudong.com/api/reset';
    // private $baseurl = 'http://cms.fangying.com/api/reset';
    public function __construct($appid, $appsecret)
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
    }
    /**
     * 支付用的二维码地址
     *
     * @param integer $money 充值金额
     * @param string $remark 备注信息
     *
     * @return string 二维码的url
     */
    public function PayQrcodeUrl($orderno,$money, $remark)
    {
        $params = ['orderno'=>$orderno,'money' => $money, 'remark' => $remark];
        $api=$this->baseurl . '/payqrcodeurl';
        $data=$this->GetData($params,$api);
        return $data;
    }

    /**
     * 获取余额信息
     *
     * @return void
     */
    public function Balance()
    {   
        $api=$this->baseurl . '/balance';
        $data=$this->GetData([],$api);
        return $data;
    }
    /**
     * 红包发放
     *
     * @param array $redpackettable 红包发放表 [["openid":"","moneny":0],["openid":"","moneny":0]];
     * @return array 返回处理结果
     */
    public function Redpacket($redpackettable,$sendname,$wishing,$notifyurl){
        $params=['redpackets'=>json_encode($redpackettable),'send_name'=>$sendname,'wishing'=>$wishing,'notifyurl'=>urlencode($notifyurl)];
        $api=$this->baseurl . '/redpacket';
        $data=$this->PostData($params,$api);
        return $data;
    }

    public function CheckRedpacket($orderno){
        $params=['orderno'=>$orderno];
        $api=$this->baseurl . '/checkredpacket';
        $data=$this->PostData($params,$api);
        return $data;
    }

    private function GetData($params,$api){
        $data = new Data();
        $data->setAppId($this->appid);
        $data->setAppSecret($this->appsecret);
        $data->setParams($params);
        $data->setSign();
        $url = $api. '?' . $data->toUrlParams() . '&sign=' . $data->getSign();
        $data=https_get($url);
        $data=json_decode($data,true);
        return $data;
    }

    private function PostData($params,$api){
        $data = new Data();
        $data->setAppId($this->appid);
        $data->setAppSecret($this->appsecret);
        $data->setParams($params);
        $data->setSign();
        $params_str=$data->toUrlParams() . '&sign=' . $data->getSign();
        $data=http_post($api,$params_str);
        $data=json_decode($data,true);
        return $data;
    }
}
