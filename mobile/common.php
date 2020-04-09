<?php
/**
 * 手机端页面公共调用的文件
 * PHP version 5.4+
 * 
 * @category Mobile
 * 
 * @package Common
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
define(
    'COMPILEPATH', 
    str_replace(DIRECTORY_SEPARATOR.'mobile', '', dirname(__FILE__)).
    DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR
);
require_once dirname(__FILE__) . '/../common/db.class.php';
require_once dirname(__FILE__) . '/../common/http_helper.php';
require_once dirname(__FILE__) . '/../common/weixin_helper.php';
require_once dirname(__FILE__) . '/../common/url_helper.php';
$currenturl = request_scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].
    ($_SERVER['QUERY_STRING']==''?'':'?'.$_SERVER['QUERY_STRING']);
if(strpos(strtolower($_SERVER['SERVER_SOFTWARE']),'apache')===false){
    $currenturl
        =request_scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

$load->model('Wall_model');
$wall_config=$load->wall_model->getConfig();
$load->model('Weixin_model');
$weixin_config=$load->weixin_model->getConfig();
$load->model('Flag_model');
// $wall_config['rentweixin']1借用其他微信服务号获取用户信息2表示使用微赢的现场活动公众号授权，默认值为2，选2可以不要对接任何东西直接使用
if ($wall_config['rentweixin']==1 && $weixin_config['appid']!='') {//使用用户自己的公众号
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
            $load->flag_model->saveRemoteUserinfo($userinfo);

            $url_arr=parse_url($currenturl);
            $baseurl=$url_arr['scheme'].'://'.$url_arr['host'].$url_arr['path'];

            //刚获取到用户信息还没有签到
            header('location:'.$baseurl.'?rentopenid='.$userinfo['openid']);
            exit();
        }
    } else {//获取到用户信息之后
        $openid=$_GET['rentopenid'];
        $userinfo=$load->flag_model->getUserinfo($openid);

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
        $userinfo=$load->flag_model->getUserinfo($openid);
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
                $return=$load->flag_model->saveRemoteUserinfo($userinfo);
            }
            if (strpos($currenturl, 'qiandao.php')===false) {
                header('location:/mobile/qiandao.php?rentopenid='.$userinfo['rentopenid'].'&fromurl='.urlencode($currenturl));
                exit();
            }
            
        } else {
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
