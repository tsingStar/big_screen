<?php

namespace Common\helpers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HttpCURLClass
 *
 * @author Administrator
 * @date 2016-10-17 0:39:19
 * @email mk9007@163.com
 * @qq 1223441896
 */
class LogUnit
{

    public static function writeLog(\Exception $ex)
    {
        $msg = date('y/m/d H:i:s', time()) . "\t\t" . $ex->getFile() . "\t\t" . $ex->getLine() . "\t\t" . $ex->getCode() . "\t\t" . $ex->getMessage() . "\t\t" . $ex->getTrace() . "\t\t" . $ex->getTraceAsString();
        Log::write($msg, 'EMERG');
    }

    public static function writeContent($contnet)
    {
        LogUnit::writeCore($contnet, LogUnit::INFO_TYPE);
    }

    public static function writeException($ex)
    {
        $contnet = date('y/m/d H:i:s', time()) . "\t\t" . $ex->getFile() . "\t\t" . $ex->getLine() . "\t\t" . $ex->getCode() . "\t\t" . $ex->getMessage() . "\t\t" . $ex->getTrace() . "\t\t" . $ex->getTraceAsString();
        LogUnit::writeCore($contnet, LogUnit::ERROR_TYPE);
    }


    public static function warnLog($contnet)
    {

        LogUnit::writeCore($contnet, LogUnit::WARN_TYPE);
    }


    public static function log($content)
    {
        LogUnit::writeCore($content, LogUnit::LOG_TYPE);
    }

    /**
     * 错误日志输出
     */
    const  ERROR_TYPE = 0;
    /**
     * 信息输出
     */
    const INFO_TYPE = 1;
    /**
     * 日志输出
     */
    const LOG_TYPE = 2;
    /**
     * 警告输出
     */
    const WARN_TYPE = 4;

    protected static function writeCore($message, $type = LogUnit::ERROR_TYPE)
    {
        $dir = __DIR__ . '/../../data/Logs/' . date('ym/d', time()) . '/';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $ext = 'info';
        $ext = $type == LogUnit::ERROR_TYPE ? "error" : $ext;
        $ext = $type == LogUnit::LOG_TYPE ? "log" : $ext;
        $ext = $type == LogUnit::WARN_TYPE ? "warn" : $ext;
        $file = $dir . $ext . "_message.log";
        file_put_contents($file, date('y/m/d H:i:s', time()) . "\t\t" . $message . "\t\t\r\n", FILE_APPEND);
    }

}
