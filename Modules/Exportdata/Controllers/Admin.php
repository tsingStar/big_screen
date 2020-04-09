<?php
/**
 * 模块后台页面
 * PHP version 5.4+
 * 
 * @category Modules
 * 
 * @package Ydj
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */

require_once MODULE_PATH.DIRECTORY_SEPARATOR.'Adminbase.php';
// use \Modules\Ydj\Models\Ydj_model;
// echo dirname(__FILE__);
/**
 * 模块后台页面
 * PHP version 5.4+
 * 
 * @category Modules
 * 
 * @package Ydj
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
class Admin extends Adminbase
{
    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 游戏轮次设置
     * 
     * @return void
     */
    public function index()
    {
        $this->setTitle('数据导出');
        $this->setDescription('这个页面只是用于导出活动的数据的，如果需要看数据表，请到左边菜单找到对应的功能。');
        $this->show("index.html");
    }
}