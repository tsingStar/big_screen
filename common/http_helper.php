<?php
if (!function_exists('http_post')) {
    function http_post($url, $data)
    {
        $post_str = '';
        if(is_array($data)){
            foreach ($data as $k => $v) {
                $post_str .= '&' . $k . '=' . $v;
            }
        }else{
            $post_str=$data;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置要采集的URL
        curl_setopt($ch, CURLOPT_POST, 1);
        //设置形式为POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_str);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        //设置Post参数
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //用字符串打印出来。
        $output = curl_exec($ch);
        curl_close($ch);
        if ($output) {
            return $output;
        } else {
            return false;
        }
    }
}
if (!function_exists('http_get')) {
    function http_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //禁止直接显示获取的内容 重要
        curl_setopt($ch, CURLOPT_HEADER, 0); //不验证证书下同
        $data = curl_exec($ch); //获取
        curl_close($ch);
        return $data;
    }
}
if (!function_exists('https_get')) {
//获取https get请求
    function https_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //禁止直接显示获取的内容 重要
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //
        $data = curl_exec($ch); //获取
        curl_close($ch);
        return $data;
    }
}
if(!function_exists('getparams')){
    function getparams($key=null,$xss_clean=false){
        if($key===null AND !empty($_GET)){
            $get=array();
            foreach(array_keys($_GET) as $key){
                $get[$key]=_fetch_from_array($_GET,$key,$xss_clean);
            }
            return $get;
        }
        return _fetch_from_array($_GET,$key,$xss_clean);
    }
}
if(!function_exists('postparams')){
    function postparams($key=null,$xss_clean=false){
        if($key===null AND !empty($_POST)){
            $post=array();
            foreach(array_keys($_POST) as $key){
                $post[$key]=_fetch_from_array($_POST,$key,$xss_clean);
            }
            return $post;
        }
        return _fetch_from_array($_POST,$key,$xss_clean);
    }
}
if(!function_exists('_fetch_from_array')){
    function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE)
    {
        if ( ! isset($array[$index]))
        {
            return FALSE;
        }

        if ($xss_clean === TRUE)
        {
            require_once('./security.class.php');
            $security=new Security();
            return $security->xss_clean($array[$index]);//$this->security->xss_clean($array[$index]);
        }

        return $array[$index];
    }
}

// echo getparams('t');
// echo 1;