<?php
/**
 * 检查请求的是http还是https
 * 
 * @return string 返回https或者http
 */
if (!function_exists('request_scheme')) {
    function request_scheme()
    {
        $server_request_scheme='http';
        if(defined('SCHEME')){
            return SCHEME;
        }
        if ((! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') || (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) {
            $server_request_scheme = 'https';
        }
        return $server_request_scheme;
    }
}

if (!function_exists('check_url')) {
    function check_url($url){
        if(!preg_match('/https?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
            return false;
        }
        return true;
    }
}