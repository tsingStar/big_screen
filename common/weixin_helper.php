<?php
require_once('CacheFactory.php');
function sendmessage($openid,$message,$token){
    $url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
    $data='{
    "touser": "'.$openid.'", 
    "msgtype": "text", 
    "text": {
        "content": "'.$message.'"
    }
}';
    $return=http_post($url,$data);
}

//获取验证oauth2 token
function getaccess_token($code, $appid, $scretekey)
{
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $scretekey . '&code=' . $code . '&grant_type=authorization_code';
    return https_get($url);
}

//第三方接口access token
function getbaseaccess_token2($appid,$secret){
    $fzz = new Cachefactory();
    $key=$appid.'_access_token';
    $access_token=$fzz->get($key);
    if(!$access_token){
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
        $access_token=https_get($url);
        $fzz->set($key,$access_token,3600); 
    }
    return $access_token;
}
//第三方接口access token
function getbaseaccess_token($appid,$secret){
    $fzz = new Cachefactory();
    $key=$appid.'_access_token';
    $access_token=$fzz->get($key);
    // echo $appid.'<br/>';
    // echo $secret.'<br/>';
    if(!$access_token){
        // echo '1'.'<br/>';
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
        // echo $url;
        $access_token=https_get($url);
        // echo $access_token;exit();
        $access_token=json_decode($access_token,true);
        // echo var_export($access_token);
        // exit();
        if(isset($access_token['errcode'])){
            return false;
        }
        $fzz->set($key,$access_token,3600); 
    }
    // echo 2;
    return $access_token;
}


function getauthorizeurl($url, $scope = 'snsapi_base', $appid)
{
    $appurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($url) . '&response_type=code&scope=' . $scope . '#wechat_redirect';
    return $appurl;
}

//oauth2 获取用户信息 sns
function getsnsuserinfo($access_token, $openid)
{
    $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
    $userinfo = https_get($url);
    return json_encode($userinfo);
}

function getuserinfo($access_token, $openid){
    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
    $userinfo = https_get($url);
    return json_decode($userinfo);
}