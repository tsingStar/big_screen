<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'CacheFactory.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR.'..'. DIRECTORY_SEPARATOR.'vendor'. DIRECTORY_SEPARATOR.'autoload.php';
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

if(!function_exists('NewOrderNo')){
    function NewOrderNo(){
        $uuid4 = Uuid::uuid4();
        $uuid=$uuid4->toString();
        $uuid=str_replace('-','',$uuid);
        return $uuid;
    }
}
if (!function_exists('processNickname')) {
    function processNickname($userinfo,$showtype,$maskmobile=true){
        $nickname=$userinfo['nickname'];
        // $nickname=pack('H*', $userinfo['nickname']);
        if($showtype==2 && !empty($userinfo['signname'])){
            //显示姓名
            $nickname=$userinfo['signname'];
        }
        if($showtype==3 && !empty($userinfo['phone'])){
            //显示电话
            if($maskmobile){
                $nickname=substr_replace($userinfo['phone'],'****',3,4);
            }else{
                $nickname=$userinfo['phone'];
            }
        }
        return $nickname;
    }
}


if (!function_exists('writecert')) {
	function writecert($url,$certname){
        if(empty($url))return '';
        $host=strtolower($_SERVER['HTTP_HOST']);
        $hostpath =str_replace('.','_',$host);
		$path=str_replace('common'.DIRECTORY_SEPARATOR.'function.php', 'data'.DIRECTORY_SEPARATOR.'pic'.DIRECTORY_SEPARATOR.$hostpath.DIRECTORY_SEPARATOR, __FILE__);
		$filepath=$path.$certname;
		if(!file_exists($filepath)){
			$myfile = fopen($filepath, "w") or die("Unable to open file!");
            //如果文件存在阿里云上
            if(SAVEFILEMODE=='aliyunoss'){
                //从阿里云oss中获得pem文件内容
                require_once ('../library/aliyunosssdk/sdk.class.php');
                $oss_sdk_service = new ALIOSS ();
                $path_arr=explode(OBJECT_PATH,$url);
                $oss_sdk_service->set_host_name(defined('ENDPOINT')?ENDPOINT:'oss-cn-hangzhou-internal.aliyuncs.com');
                $object_obj=$oss_sdk_service->get_object(BUCKET_NAME,OBJECT_PATH.$path_arr[1]);
                $certcontent=$object_obj->body;
                fwrite($myfile, $certcontent);
                fclose($myfile);
            }
            
		}
		return $filepath;
	}
}

//取指定长度的随机数字字符串
if (!function_exists('randStr')) {
    function randStr($len = 10)
    {
        $rand='';
        for ($i = 0; $i < $len; $i++) {
            $rand .= mt_rand(0, 9);
        }
        return $rand;
    }
}
if (!function_exists('returnmsg')) {
    function returnmsg($code,$msg='',$data=array(),$type='json'){
        $returndata=array('code'=>$code,'message'=>$msg);
        if(!empty($data)){
            $returndata['data']=$data;
        }
        if($type=='json'){
            return json_encode($returndata);
        }
        return $returndata;
    }
}

if (!function_exists('blackword')) {
    //屏蔽字
    function blackword($content, $blackword) {
        if (! empty ( $blackword )) {
            $blackword=str_replace('，',',',$blackword);
            $blackarr = explode ( ",", $blackword);
            foreach ( $blackarr as $v ) {
                if (strstr( $content, $v )) {
                    return 1;
                }
            }
            return 0;
        }
    }
}