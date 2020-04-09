<?php
/**
 * 文件上传
 * PHP version 5.4+
 * 
 * @category Common
 * 
 * @package FileUploadFactory
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
require_once '../data/config.php';
require_once '../common/File_helper.php';
/**
 * 文件上传
 * PHP version 5.4+
 * 
 * @category Common
 * 
 * @package FileUploadFactory
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
class FileUploadFactory
{
    // 参数有file,aliyunoss,sae
    var $fileuploader;
    var $_type=null;
    function __construct($type = 'file') 
    {
        switch ($type) {
            case 'aliyunoss' :
                $this->_type=2;
                require_once ('AliyunossFileUpoad.php');
                $this->fileuploader = new AliyunossFileUpoad ();
                // code...
                break;
            case 'sae' :
                $this->_type=3;
                $this->fileuploader = new SAEFileUpload ();
                break;
            default :
                $this->_type=1;
                require_once ('FileUpload.php');
                $this->fileuploader = new FileUpload ();
                break;
        }
    }
    function SaveRemotePic($picurl) 
    {
        return $this->fileuploader->SaveRemotePic ( $picurl);
    }

    function getUploadType(){
        return $this->_type;
    }
    //保存表单中的文件
    function SaveFormFile($formfiledata, $filename = '', $path='') 
    {
        $filepath=$this->fileuploader->SaveFormFile($formfiledata, $filename, $path);
        
        return $filepath;
    }
    //保存文件
    function SaveFile($filecontent, $extension) 
    {
        $filepath=$this->fileuploader->SaveFile ( $filecontent, $extension );
        return $this->saveAttachment($filepath,$extension);
    }

    private function saveAttachment($filepath,$extension)
    {
        $attachments_m=new M('attachments');
        $attachement_data=array(
                'filepath'=>$filepath,
                'extension'=>$extension,
                'type'=>$this->_type
        );
        $attachment_id=$attachments_m->add($attachement_data);
        $attachement_data['id']=$attachment_id;
        return $attachement_data;
    }

    //设置可以上传的文件类型
    function setAllowMimeTypes($mimetypes=array())
    {
        $this->fileuploader->setAllowMimeTypes($mimetypes);
    }
}