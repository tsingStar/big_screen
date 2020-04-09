<?php
/**
 * 附件
 * PHP version 5.4+
 * 
 * @category Models
 * 
 * @package Attachment
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'CacheFactory.php';
/**
 * 附件
 * PHP version 5.4+
 * 
 * @category Models
 * 
 * @package Attachment
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
class Attachment_model
{
    var $_attachments_m=null;
    var $_cache=null;
    var $_attachmentcache='attachment_';
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->_attachments_m=new M('attachments');
        $this->_cache=new CacheFactory('file');
    }

    /**
     * 按照id获取附件信息
     * 
     * @param int $id 附件id
     * 
     * @return array 获取所有主题数据的数组
     */
    public function getById($id)
    {
        if(empty($id))return null;
        $cachename=$this->_attachmentcache.$id;
        $data=$this->_cache->get($cachename);
        if (!$data) {
            $data=$this->_attachments_m->find('id='.$id);
            $this->_cache->set($cachename, $data, 24*3600);
        }
        return $data;
    }
    /**
     * 保存文件信息到附件表
     * 
     * @param text $filepath  文件路径
     * @param text $extension 扩展名
     * @param int  $type      存储的类型1本地文件2阿里云3sae
     * @param text $filemd5   文件名和文件大小成的md5值
     * 
     * @return array 返回附件信息
     */
    public function saveAttachment($filepath, $extension, $type=1, $filemd5='')
    {
        $attachments_m=new M('attachments');
        $attachement_data=array(
                'filepath'=>$filepath,
                'extension'=>$extension,
                'type'=>$type,
                'filemd5'=>$filemd5
        );
        $attachment_id=$attachments_m->add($attachement_data);
        $attachement_data['id']=$attachment_id;
        return $attachement_data;
    }
    
    /**
     * 按照md5获取附件信息
     * 
     * @param text $md5 附件md5
     * 
     * @return array 获取所有主题数据的数组
     */
    public function getByMd5($md5)
    {
        $attachmentinfo=$this->_attachments_m->find('filemd5="'.$md5.'"');
        return $attachmentinfo;
    }
    /**
     * 上传文件
     * 
     * @param file $file       上传的文件
     * @param text $allowtypes 可以上传的文件的mimetype类型
     * 
     * @return mixed 失败返回false，成功返回id
     */
    public function saveFormFile($file,$allowtypes='image/jpg,image/jpeg,image/png,image/gif')
    {
        $md5=md5($file['name'].'|'.$file['size']);
        $attachmentinfo=$this->getByMd5($md5);
        if ($attachmentinfo) {
            return $attachmentinfo;
        }
        include_once '../common/FileUploadFactory.php';
        $fuf=new FileUploadFactory(SAVEFILEMODE);
        $fuf->setAllowMimeTypes(explode(',', $allowtypes));
        $savedfilepath=$fuf->SaveFormFile($file);
        if ($savedfilepath) {
            include_once '../common/File_helper.php';
            $extension=GetFileExtension($file['name']);
            $uploadtype=$fuf->getUploadType();
            $attachmentinfo=$this->saveAttachment($savedfilepath, $extension, $uploadtype, $md5);
            return $attachmentinfo;
        }
        return false;
    }
}