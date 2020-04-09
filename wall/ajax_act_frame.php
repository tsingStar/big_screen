<?php
/**
 * 获取框架页中的一些数据
 * PHP version 5.4+
 * 
 * @category Frame
 * 
 * @package Frame
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
require_once '../common/session_helper.php';
require_once dirname(__FILE__) . '/../common/db.class.php';
require_once dirname(__FILE__) . '/../common/CacheFactory.php';
// if (!isset($_SESSION['views']) || $_SESSION['views'] != true) {
//     return false;
// }

$action=isset($_GET['action'])?strval($_GET['action']):'';
switch($action){
case "countperson":
    countperson();
    break;
default:
    break;
}
/**
 * 统计签到的人数
 * 
 * @return json 返回人数的json字符串
 */
function countperson()
{
    $load=Loader::getInstance();
    $load->model('Flag_model');
    $count=$load->flag_model->getSignedCount();
    $returndata=array('code'=>-1,"data"=>0);
    if ($count!==false) {
        $returndata=array('code'=>1,"data"=>$count);
    }
    echo json_encode($returndata);
    return;
}