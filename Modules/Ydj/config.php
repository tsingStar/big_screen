<?php
/**
 * 摇大奖模块配置
 * PHP version 5.4+
 * 
 * @category Ydj
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

$config=array(
    "admin"=>array('menu'=>array(
        "name"=>"摇大奖设置","link"=>'/Modules/module.php?m=ydj&c=admin&a=index'
    )),
    "front"=>array("menu"=>array(
        "name"=>"摇大奖","link"=>"/Modules/module.php?m=ydj&c=front&a=index","icon"=>"/wall/themes/meepo/assets/images/icon/ico002-.png","shortcut"=>"ctrl+y"
    )),
    "mobile"=>array("menu"=>array("name"=>"摇大奖","link"=>"/Modules/module.php?m=ydj&c=mobile&a=index","icon"=>""))
);