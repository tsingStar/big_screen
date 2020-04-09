<?php
/**
 * 现场活动大屏幕系统图片代理
 * PHP version 5.4+
 * 
 * @category Image
 * 
 * @package Image
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * */
require_once dirname(__FILE__) . '/common/db.class.php';
require_once dirname(__FILE__) . '/common/File_helper.php';
use OSS\Core\MimeTypes;
use OSS\OssClient;
use OSS\Core\OssException;

$imageid=$_GET['id'];
$load->model('Attachment_model');
$fileinfo=$load->attachment_model->getById($imageid);

switch ($fileinfo['type']){
case 1:
    showlocalfile($fileinfo['filepath']);
    break;
case 2:
    showremotefile($fileinfo['filepath']);
    break;
default:
    break;
}
/**
 * 显示本地图片
 * 
 * @param text $path 图片路径
 * 
 * @return image 图片内容
 */
function showlocalfile($path)
{
    $imagepath=dirname(__FILE__).$path;
    $mime=MimeTypes::getMimetype(strtolower($imagepath));
    $image = file_get_contents($imagepath);
    header('Content-type: '.$mime);
    echo $image;
}
/**
 * 显示远程图片
 * 
 * @param text $path 图片路径
 * 
 * @return image 图片内容
 */
function showremotefile($path)
{
    if (SAVEFILEMODE=='aliyunoss') {
        $oss_sdk_service = new OssClient(OSS_ACCESS_ID,OSS_ACCESS_KEY,ENDPOINT);
        $path_arr = explode(OBJECT_PATH, $path);
        $object_obj=$oss_sdk_service->getObject(
            BUCKET_NAME, OBJECT_PATH.$path_arr[1]
        );
        $mime=MimeTypes::getMimetype(strtolower($path));
        header('Content-type: '.$mime);
        echo $object_obj;
        return;
    }
    echo '';
    return;
}