<?php
/**
 * 幸运号码
 * PHP version 5.4+
 * 
 * @category Wall
 * 
 * @package Xyh
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
@header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__) . '/../common/db.class.php';
require_once dirname(__FILE__) . '/../common/session_helper.php';
require_once dirname(__FILE__) . '/../common/function.php';

$style='meepo';
$load->model('Wall_model');
$wall_config=$load->wall_model->getConfig();
$load->model('Weixin_model');
$weixin_config=$load->weixin_model->getConfig();

//幸运号码 配置信息
$xingyunhaomaconfig_m=new M('xingyunhaoma_config');
$xingyunhaomaconfig=$xingyunhaomaconfig_m->find('1 limit 1');
$smarty = new Smarty;
$smarty->caching = false;
$apppath=str_replace(DIRECTORY_SEPARATOR.'wall', '', dirname(__FILE__));
$smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR
    .'templates_c'.DIRECTORY_SEPARATOR;
$smarty->assign('from', 'qiandao');
$smarty->assign('wall_config', $wall_config);
$smarty->assign('erweima', $weixin_config['erweima']);
$smarty->assign('xingyunhaomaconfig', $xingyunhaomaconfig);
$smarty->display('themes/'.$style.'/header.html');
$smarty->display('themes/'.$style.'/xyh.html');
$smarty->display('themes/'.$style.'/footer.html');