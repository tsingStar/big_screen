<?php
/**
 * 加载类
 * PHP version 5.4+
 * 
 * @category Common
 * 
 * @package Loader
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */

/**
 * 加载类
 * PHP version 5.4+
 * 
 * @category Common
 * 
 * @package Loader
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
class Loader
{
    static private $_instance=null;
    var $_models=[];
    var $_modulename='';
    static private $_modulebasepath='';
    /**
     * 构造函数
     */
    function __construct()
    {

    }
    /**
     * 获取实例
     * 
     * @return 获取已经实例化的实例
     */
    static public function getInstance()
    {
        if (!self::$_instance instanceof self) {
             self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * 获取实例
     * 
     * @param text $modelname model的名称
     * 
     * @return 获取已经实例化的实例
     */
    function model($modelname,$args=array())
    {
        
        //已经加载过的
        if (in_array($modelname, $this->_models)) {
            
            return $this;
        }
        $lowmodelname=strtolower($modelname);
        $filename=$lowmodelname.'.php';
        
        //如果设置过模块名，优先调用模块内的model
        $path=self::getModuleName();
        $modelpath=$path.DIRECTORY_SEPARATOR.$filename;
        
        if (file_exists($modelpath)) {
            include_once $modelpath;
            $this->$lowmodelname=new $modelname();
            array_push($this->_models, $modelname);
            return $this;
        }
        
        //找不到模块中的models目录中的model， 就公共的models目录中的model
        $defaultpath=dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.$filename;
 
        if (file_exists($defaultpath)) {
            include_once $defaultpath;
            if (!empty($args)) {
                $this->$lowmodelname=new $modelname($args);
                array_push($this->_models, $modelname.'_'.md5(serialize($args)));
            } else {
                $this->$lowmodelname=new $modelname();
                // echo var_export($this->$lowmodelname);
                array_push($this->_models, $modelname);
            }
            
        } else {
            throw new RuntimeException("{$modelname}不存在");
        }
        return $this;
    }
    /**
     * 设置当前的模块名称
     * 
     * @param text $modulename 模块名称
     * 
     * @return string 模块的路径
     */
    static public function setModuleName($modulename)
    {
        // \modules\ydj\models
        $path=dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.
            DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.
            $modulename.DIRECTORY_SEPARATOR.'Models';
        // echo $path;
        self::$_modulebasepath = $path;
    }
    /**
     * 获取当前的模块名称
     * 
     * @return string 模块的路径
     */
    static public function getModuleName()
    {
        return self::$_modulebasepath;
    }
}