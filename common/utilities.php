<?php
if (!function_exists('GetIp')) {
    function GetIp(){ 
        //php7.1.13和php7.2.10 getenv 函数在没有第2个参数的情况下可能会导致程序崩溃
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
            $ip = getenv("HTTP_CLIENT_IP"); 
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
            $ip = getenv("HTTP_X_FORWARDED_FOR"); 
        else if (getenv("REMOTE_ADDR",true) && strcasecmp(getenv("REMOTE_ADDR",true), "unknown")) 
            $ip = getenv("REMOTE_ADDR",true); 
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
            $ip = $_SERVER['REMOTE_ADDR'];
        else 
            $ip = "unknown"; 
        return($ip); 
    }
}

if (!function_exists('processlist')) {
    function processlist(){
        $flag_m=new M('flag');
        $result=$flag_m->query('show full processlist ');
        $data=$flag_m->fetch_array($result);
        foreach ($data as $row) {
            echo 'id:'.$row['Id'].' Host:'.$row['Host'].' Time:'.$row['Time'].' Command:'.$row['Command'].' info:'.$row['Info'].'<br/>';
        }
    }
}