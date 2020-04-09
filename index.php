<?php
/**
 * 现场活动大屏幕入口页面
 * PHP version 5.4+
 * 
 * @category Index
 * 
 * @package Index
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * */
define('IA_ROOT', str_replace("\\", '/', dirname(__FILE__)));
if (!file_exists(IA_ROOT . '/data/install.lock')) {
    header('location: ./install.php');
    exit;
}
header('location:/frame.php');
