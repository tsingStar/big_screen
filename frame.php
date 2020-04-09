<?php
/**
 * 框架页
 * PHP version 5.4+
 * 
 * @category Index
 * 
 * @package Frame
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * */
@header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__) . '/common/db.class.php';
require_once dirname(__FILE__) . '/common/http_helper.php';
require_once dirname(__FILE__) . '/common/session_helper.php';
require_once dirname(__FILE__) . '/common/CacheFactory.php';
$load->model('Wall_model'); 
$wall_config= $load->wall_model->getConfig();

$load->model('Weixin_model');
$weixin_config= $load->weixin_model->getConfig();
$load->model('Danmu_model');
$danmu_config=$load->danmu_model->getConfig();

//开启的组件
$load->model('Plugs_model');
$plugs=$load->plugs_model->getPlugs(1);
$load->model('Music_model');
$musicjson=$load->music_model->getMusicJson();
$load->model('Background_model');
$backgroundimagejson=$load->background_model->getBackgroundJson();
$load->model('System_Config_model');
$menucolor=$load->system_config_model->get('menucolor');
$menucolor['configvalue']
    = empty($menucolor['configvalue'])?'#fff':$menucolor['configvalue'];
$showcountsign=$load->system_config_model->get('showcountsign');
$showcountsign['configvalue']
    = empty($showcountsign['configvalue'])?'1':$showcountsign['configvalue'];
$qrcodepos=$load->system_config_model->get('qrcodepos');
$qrcodepos['configvalue']  = empty($qrcodepos['configvalue']) ? null : unserialize($qrcodepos['configvalue']);
//smarty模板
$smarty = new Smarty;
$smarty->caching = false;
$apppath=dirname(__FILE__);

$smarty->compile_dir 
    = $apppath.DIRECTORY_SEPARATOR.'data'
    .DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
$smarty->assign('wall_config', $wall_config);
$smarty->assign('weixin_config', $weixin_config);
//组件
$plugsjson=formatplugsjson($plugs);
//组件数组
$smarty->assign('plugs', $plugs);
//组件json
$smarty->assign('plugsjson', $plugsjson);
$smarty->assign('musicjson', $musicjson);
$smarty->assign('backgroundimagejson', $backgroundimagejson);
$smarty->assign('danmuconfig', json_encode($danmu_config));
$smarty->assign('menucolor', $menucolor['configvalue']);
$smarty->assign('showcountsign', $showcountsign['configvalue']);
$smarty->assign('qrcodepos', json_encode($qrcodepos['configvalue']));
$smarty->display('frame.html');

/**
 * 把组件数据从array转为json
 * 
 * @param array $plugs 组件数组
 * 
 * @return text  组件json数据
 */
function formatplugsjson($plugs)
{
    $formartdata = array();
    foreach ($plugs as $item) {
        $formartdata[$item['name']]=$item;
    }
    return json_encode($formartdata);
}