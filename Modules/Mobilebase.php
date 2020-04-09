<?php
/**
 * 模块后台页面基础类
 * PHP version 5.4+
 * 
 * @category Modules
 * 
 * @package Mobilebase
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
require_once BASEPATH .DIRECTORY_SEPARATOR. 'common'.DIRECTORY_SEPARATOR.'url_helper.php';
require_once BASEPATH .DIRECTORY_SEPARATOR. 'common'.DIRECTORY_SEPARATOR.'CacheFactory.php';
require_once BASEPATH .DIRECTORY_SEPARATOR. 'common'.DIRECTORY_SEPARATOR.'http_helper.php';
require_once BASEPATH .DIRECTORY_SEPARATOR. 'common'.DIRECTORY_SEPARATOR.'weixin_helper.php';
/**
 * 模块后台页面基础类
 * PHP version 5.4+
 * 
 * @category Modules
 * 
 * @package Mobilebase
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
class Mobilebase
{
    var $_smarty=null;
    var $_current_module_mobile_template_path='';

    var $_assets_basepath='';
    var $_common_templates_basepath='';
    var $_page_data=null;
    var $_load=null;
    var $_current_module_assets_path='';
    var $_current_module_mobile_path='';
    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        
        $this->_load=Loader::getInstance();
        $m=ucfirst($_GET['m']);
        $this->_load->setModuleName($m);
        
        if(!$this->_isAjax()){
            $this->_assets_basepath='/mobile/template/app';
            $this->_current_module_mobile_template_path=MODULE_PATH.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'mobile';
            $this->_current_module_assets_path='/Modules/'.$m.'/templates/assets';
            $this->_current_module_mobile_path='/Modules/'.$m.'/templates/mobile';
            $this->_smarty=new Smarty;
            $this->_smarty->caching = false;
            $this->_smarty->compile_dir = BASEPATH.DIRECTORY_SEPARATOR.'data'.
                DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
            $this->_setassetspath();
            $this->_checkPrivilege();
        }
    }
    /**
     * 检查是否为ajax请求
     * 
     * @return bool true表示是ajax请求 false表示不是ajax请求
     */
    private function _isAjax(){

        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=='xmlhttprequest'){
            return true;
        }else{
            return false;
        }

        // $action=$_GET['a'];
        // if(strpos(strtolower($action),'ajax_')===false){
        //     return false;
        // }
        // return true;
    }
    /**
     * 检查是否有权限访问，并记录下用户的信息,没有权限的直接跳转到签到界面
     * 
     * @return void
     */
    private function _checkPrivilege(){
        $currenturl=request_scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].
            ($_SERVER['QUERY_STRING']==''?'':'?'.$_SERVER['QUERY_STRING']);
        $this->_load->model('Wall_model');
        $wall_config=$this->_load->wall_model->getConfig();
        $this->_load->model('Weixin_model');
        $weixin_config=$this->_load->weixin_model->getConfig();
        if ($wall_config['rentweixin']==1) {//使用用户自己的公众号
            if (!isset($_GET['rentopenid'])) {//如果还没有获取到openid
                if (empty($_GET['vcode']) || $_GET['vcode']!=$wall_config['verifycode']) {
                    echo '找不到活动';
                    exit();
                }
                if (empty($_GET['code'])) {//还没有获取到code
                    $fromurl=$currenturl;
                    $url=getauthorizeurl($fromurl, 'snsapi_userinfo', $weixin_config['appid']);
                    header('location:' . $url);
                    exit();
                } else {//获取到code之后获取用户信息
                    $tokeninfo = getaccess_token($_GET['code'], $weixin_config['appid'], $weixin_config['appsecret']);
                    $tokeninfo = json_decode($tokeninfo, true);
                    $userinfo = getsnsuserinfo($tokeninfo['access_token'], $tokeninfo['openid']);
                    $userinfo = json_decode($userinfo);
                    if (is_string($userinfo)) {
                        $userinfo = json_decode($userinfo, true);
                    }
                    $userinfo['nickname']=bin2hex($userinfo['nickname']);
                    $userinfo['openid']=$tokeninfo['openid'];
                    $userinfo['rentopenid']=$tokeninfo['openid'];
                    $this->_load->model('Flag_model');
                    $this->_load->flag_model->saveRemoteUserinfo($userinfo);
                    $url_arr=parse_url($currenturl);
                    // $baseurl=$url_arr['scheme'].'://'.$url_arr['host'].$url_arr['path'];
                    // //刚获取到用户信息还没有签到
                    // header('location:'.$baseurl.'?rentopenid='.$userinfo['openid']);
                    header('location:'.$currenturl.'&rentopenid='.$userinfo['openid']);
                    exit();
                }
            } else {//获取到用户信息之后
                $openid=$_GET['rentopenid'];
                $this->_load->model('Flag_model');
                $userinfo=$this->_load->flag_model->getUserinfo($openid);
                $this->setUserinfo($userinfo);
                if ($userinfo['flag']==1) {//如果检查用户还没有签到
                    if (strpos($currenturl, 'qiandao.php')===false) {
                        header(
                            'location:/mobile/qiandao.php?rentopenid='.
                            $userinfo['rentopenid'].'&fromurl='.urlencode($currenturl)
                        );
                        exit();
                    }
                }
            }
        } else {
            //使用默认公众号授权
            if (!isset($_GET['rentopenid'])) {
                if (empty($_GET['vcode']) || $_GET['vcode']!=$wall_config['verifycode']) {
                    echo '找不到活动';
                    exit();
                }
                //先去获取用户信息
                $url='http://api.vdcom.cn/wxgate/index?url='.urlencode($currenturl);
                header('location:'.$url);
                exit();
            } else {
                $openid=$_GET['rentopenid'];
                $this->_load->model('Flag_model');
                $userinfo=$this->_load->flag_model->getUserinfo($openid);
                if (!$userinfo) {
                    $url='http://api.vdcom.cn/wxgate/getuserinfobyrentopenid?rentopenid='.$_GET['rentopenid'];
                    $json=http_get($url);
                    $userinfo_arr=json_decode($json, true);
                    if ($userinfo_arr['error']>0) {
                        $userinfo=array();
                        $userinfo['openid']=$userinfo_arr['userinfo']['openid'];
                        $userinfo['rentopenid']=$userinfo_arr['userinfo']['openid'];
                        $userinfo['nickname']=$userinfo_arr['userinfo']['nickname'];
                        $userinfo['headimgurl']=$userinfo_arr['userinfo']['headimgurl'];
                        $userinfo['sex']=$userinfo_arr['userinfo']['sex'];
                        // $this->_load->model('Flag_model');
                        $return=$this->_load->flag_model->saveRemoteUserinfo($userinfo);
                    }
                    if (strpos($currenturl, 'qiandao.php')===false) {
                        header('location:/mobile/qiandao.php?rentopenid='.$userinfo['rentopenid'].'&fromurl='.urlencode($currenturl));
                        exit();
                    }
                    
                } else {
                    $this->setUserinfo($userinfo);
                    if ($userinfo['flag']==1 || $userinfo['status']==2) {
                        if (strpos($currenturl,'qiandao.php')===false) {
                            if ($userinfo['status']==2) {
                                header('location:/mobile/qiandao.php?rentopenid='.$userinfo['rentopenid']);
                                exit();
                            } else {
                                header('location:/mobile/qiandao.php?rentopenid='.$userinfo['rentopenid'].'&fromurl='.urlencode($currenturl));
                                exit();
                            }
                        }
                    }
                }
                
            }
        }
    }
    var $_userinfo=null;
    public function setUserinfo($userinfo)
    {
        $this->_userinfo=$userinfo;
    }
    /**
     * 获取用户信息
     * 
     * @return array 用户信息
     */
    public function getUserinfo()
    {
        return $this->_userinfo;
    }

    /**
     * 显示页面
     * 
     * @param string $path 模板路径,不需要
     * @param bool $custom 是否自定义文件头部和底部
     * 
     * @return void
     */
    protected function show($path,$custom=false)
    {
        if($custom==false){
            $this->_htmlheader();
            $this->_htmlfooter();
        }
        $userinfo=$this->getUserinfo();
        $this->_smarty->assign('openid',$userinfo['openid']);
        $this->_smarty->display($this->_current_module_mobile_template_path.DIRECTORY_SEPARATOR.$path);
    }
    /**
     * 设置页面引入的资源的路径
     * 
     * @param string $varname 映射到模板的变量名
     * @param string $varval  映射到模板的变量值
     * 
     * @return void
     */
    protected function assign($varname,$varval)
    {
        $this->_smarty->assign($varname, $varval);
    }
    /**
     * 页面header内容
     * 
     * @return void
     */
    private function _htmlheader()
    {
        $headerpath=$this->_current_module_mobile_template_path.DIRECTORY_SEPARATOR.'header.html';
        if(file_exists($headerpath))
        $this->_smarty->assign(
            'html_header', $this->_smarty->fetch(
                $headerpath
            )
        );
    }
    /**
     * 页面footer内容
     * 
     * @return void
     */
    private function _htmlfooter()
    {
        
        $footerpath=$this->_current_module_mobile_template_path.DIRECTORY_SEPARATOR.'footer.html';
        $this->_load->model('Plugs_model');
        $plugs=$this->_load->plugs_model->getPlugs(1);
        $this->_smarty->assign('plugs',$plugs);
        if(file_exists($footerpath)){
            $this->_smarty->assign(
                'html_footer', $this->_smarty->fetch(
                    $footerpath
                )
            );
        }
        
    }
    /**
     * 设置页面引入的资源的路径
     * 
     * @return void
     */
    private function _setassetspath()
    {
        $this->_smarty->assign('module_mobile_path', $this->_current_module_mobile_path);
        $this->_smarty->assign('module_assets', $this->_current_module_assets_path);
        $this->_smarty->assign('assets', $this->_assets_basepath);
    }
}