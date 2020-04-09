<?php

namespace Common\helpers;

use Exception;

/**
 * Description of RTMPHelper
 *
 * @author Administrator
 * @date 2016-10-18 0:16:16
 * @email mk9007@163.com
 * @qq 1223441896
 */
class ResponseHelper
{

    const SUCCESS_STATUS = 100000;
    const ERROR_STATUS = -100000;

    public static $message = array(
        'status' => self::ERROR_STATUS,
        'msg' => '',
        'data' => array(),
    );

    public static function setStatus($msg = '', $status = self::SUCCESS_STATUS)
    {
        self::$message['status'] = $status;
        self::$message['msg'] = $msg;
    }

    public static function setValue($key, $value)
    {
        self::$message[$key] = $value;
    }

    public static function getAjaxReturn()
    {
        return self::$message;
    }


    public static function ajaxReturn()
    {
        echo ResponseHelper::responseAjaxReturn();
        exit;
    }


    public static function responseAjaxReturn()
    {
        self::setChart();
        if (!defined("JSON_UNESCAPED_UNICODE")) {
            define('JSON_UNESCAPED_UNICODE', 256);
        }
        return json_encode(self::getAjaxReturn(), JSON_UNESCAPED_UNICODE);
    }

    public static function setChart($charset = 'utf-8')
    {
        header("Content-type: text/html; charset=" . $charset);
    }

    public static function setMessage($msg = '', $data = '')
    {
        self::$message['msg'] = $msg;
        self::$message['data'] = $data;
    }

    public static function accessAllowOrigin()
    {
        header("Access-Control-Allow-Origin: *");
    }

    public static function setException(Exception $ex)
    {
        self::setMessage($ex->getMessage(), $ex->getCode());
    }


}
