<?php
use OSS\Core\MimeTypes;
use OSS\OssClient;

class AliyunossFileUpoad
{
    public $_allow_mimetypes = array("image/pjpeg", "image/jpeg", "image/png", 'audio/mp3', 'application/vnd.ms-excel');
    public function SaveRemotePic($picurl = '')
    {
        $extension = $this->getFileExtension($picurl);
        $imagedata = $this->getRemoteImageData($picurl);
        $returnfileurl = $this->SaveFile($imagedata, $extension);
        return $returnfileurl;
    }
    public function setAllowMimeTypes($mimetypes = array())
    {
        if (!empty($mimetypes)) {
            $this->_allow_mimetypes = $mimetypes;
        }
        // return $this->_allow_mimetypes;
    }
    public function getAllowMimeTypes()
    {
        return $this->_allow_mimetypes;
    }
    public function SaveFormFile($formfiledata, $filename = '', $path)
    {

        $tp = $this->getAllowMimeTypes();
        // 检查上传文件是否在允许上传的类型
        if (!in_array($formfiledata["type"], $tp)) {
            return false;
        }

        $extension = $this->getFileExtension($formfiledata['name']);

        $oss_sdk_service = new OssClient(OSS_ACCESS_ID, OSS_ACCESS_KEY, ENDPOINT);
        $object = OBJECT_PATH . "/pic_" . time() . '.' . $extension;
        $options = array(
            'Content-Type' => MimeTypes::getMimetype(strtolower($extension)),
        );
        $response_upload_file_by_file = $oss_sdk_service->uploadFile(BUCKET_NAME, $object, $formfiledata['tmp_name'], $options);
        // echo var_export($response_upload_file_by_file);
        $weburl = 'https://' . BUCKET_NAME . '.oss-cn-hangzhou.aliyuncs.com/' . $object;
        return $weburl;
    }
    public function getRemoteImageData($picurl)
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
        // return array('data'=>$output,'extension'=>)
    }
    public function getFileExtension($picurl)
    {
        $pathinfo = pathinfo($picurl);
        $extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
        $extension = strtolower($extension);
        switch ($extension) {
            case 'jpg':
                // code...
                break;
            case 'jpeg':
                break;
            case 'png':
                break;
            case 'gif':
                break;
            case 'mp3':
                break;
            case 'mp4':
                break;
            case 'ogg':
                break;
            default:
                $extension = 'lj';
                // code...
                break;
        }
        return $extension;
    }
    public function SaveFile($output, $extension)
    {
        $oss_sdk_service = new OssClient(OSS_ACCESS_ID, OSS_ACCESS_KEY, ENDPOINT);
        $object = OBJECT_PATH . "/pic_" . time() . uniqid() . '.' . $extension;
        $extension = '.' . $extension;
        $options = array(
            'Content-Type' => MimeTypes::getMimetype(strtolower($extension)),
            'length' => strlen($output),
            'headers' => array(
                'Cache-control' => 'max-age=864000',
            ),
        );
        $content = $output;
        $response_upload_file_by_file = $oss_sdk_service->putObject(BUCKET_NAME, $object, $content, $options);
        $weburl = 'https://' . BUCKET_NAME . '.oss-cn-hangzhou.aliyuncs.com/' . $object;
        return $weburl;
    }
}
