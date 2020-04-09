<?php
/**
 * 文件上传
 * PHP version 5.4+
 * 
 * @category Common
 * 
 * @package FileUpload
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
require_once 'File_helper.php';
/**
 * 文件上传
 * PHP version 5.4+
 * 
 * @category Common
 * 
 * @package FileUpload
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
class FileUpload
{
    var $savepath;
    var $_allow_mimetypes=array("image/pjpeg", "image/jpeg", "image/png",'audio/mp3','application/vnd.ms-excel');
    var $hostpath;
        //保存远程图片到本地服务器
    function __construct() 
    {
        $basepath=str_replace(DIRECTORY_SEPARATOR.'common', DIRECTORY_SEPARATOR, dirname(__FILE__));
        $host=strtolower($_SERVER['HTTP_HOST']);
        $this->hostpath =str_replace('.','_',$host);
        // echo $this->hostpath;
        $this->savepath=$basepath.'data'.DIRECTORY_SEPARATOR.'pic'.DIRECTORY_SEPARATOR.$this->hostpath.DIRECTORY_SEPARATOR;
    }

    function setAllowMimeTypes($mimetypes=array())
    {
        if (!empty($mimetypes)) {
            $this->_allow_mimetypes=$mimetypes;
        }
    }

    function getAllowMimeTypes()
    {
        return $this->_allow_mimetypes;
    }

    function SaveRemotePic($picurl='')
    {
        $extension=GetFileExtension($picurl);
        $imagedata=$this->getRemoteImageData($picurl);
        $returnfileurl=$this->SaveFile($imagedata,$extension);
        return $returnfileurl;
    }
        
    function SaveFormFile($formfiledata,$filename='',$path)
    {

        $tp = $this->getAllowMimeTypes();

        //检查上传文件是否在允许上传的类型
        if (!in_array($formfiledata["type"], $tp)) {
            return false;
        }
        $extension=GetFileExtension($formfiledata['name']);
        if($this->local_mkdirs($this->savepath)){
            $destnationfilename="pic_" . time() .'.'.$extension;
            $destnation=$this->savepath.$destnationfilename;
            $result=move_uploaded_file($formfiledata['tmp_name'], $destnation);
            return "/data/pic/".$this->hostpath.'/'.$destnationfilename;
        }
        return false;
    }
    function getRemoteImageData($picurl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $picurl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1521.3 Safari/537.36");
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    function local_mkdirs($path)
    {
        if (!is_dir($path)) {
            $this->local_mkdirs(dirname($path));
            mkdir($path);
        }
        return is_dir($path);
    }


    function SaveFile($write,$extension)
    {   
        if($this->local_mkdirs($this->savepath)){
            $filename = "pic_" . time().rand(10000,99999) .'.'.$extension;
            $file = fopen($this->savepath . $filename, "w");
            fwrite($file, $write);//写入
            fclose($file);//关闭
            $imgurl = "/data/pic/".$this->hostpath.'/' . $filename;
            return $imgurl;
        }
        return false;    
    }
}