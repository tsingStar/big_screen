<?php
require_once dirname(__FILE__) . '/../common/db.class.php';
require_once dirname(__FILE__) . '/../common/url_helper.php';
require_once dirname(__FILE__) . '/../common/CacheFactory.php';

$load->model('Wall_model');
$wall_config= $load->wall_model->getConfig();

$from=isset($_GET['from'])?strval($_GET['from']):'';
$action=isset($_GET['action'])?strval($_GET['action']):'view';
if ($action!='download') {
    $action='view';
}
//二维码颗粒大小
$s=isset($_GET['s'])?intval($_GET['s']):10;

$s=$s<10?10:$s;
$baseurl=request_scheme().'://'.$_SERVER['HTTP_HOST'];
switch ($from) {
case 'wall':
    $mobileurl=$baseurl.'/mobile/index.php?vcode='.$wall_config['verifycode'];
    break;
case 'shake':
    $mobileurl=$baseurl.'/mobile/shake.php?vcode='.$wall_config['verifycode'];
    break;
case 'shuqian':
    $mobileurl=$baseurl.'/mobile/shuqian.php?vcode='.$wall_config['verifycode'];
    break;
case 'pashu':
    $mobileurl=$baseurl.'/mobile/pashu.php?vcode='.$wall_config['verifycode'];
    break;
case 'vote':
    $mobileurl=$baseurl.'/mobile/vote.php?vcode='.$wall_config['verifycode'];
    break;
case 'redpacket':
    $mobileurl=$baseurl.'/mobile/redpacket.php?vcode='.$wall_config['verifycode'];
    break;
case 'cjresult':
    $mobileurl=$baseurl.'/mobile/cjresult.php?vcode='.$wall_config['verifycode'];
    break;
default:
    $mobileurl=genurl($from, $baseurl, $wall_config);
    break;
}

function genurl($from,$baseurl,$wall_config)
{
    $load=Loader::getInstance();
    $load->model('Plugs_model');
    $plug=$load->plugs_model->getPlugByName($from);
    $mobileurl=!empty($plug['mobile']['menu']['link'])?$baseurl.$plug['mobile']['menu']['link'].'&vcode='.$wall_config['verifycode']:$baseurl.'/mobile/qiandao.php?vcode='.$wall_config['verifycode'];
    return $mobileurl;
}

QRcode::png($mobileurl, false, QR_ECLEVEL_Q, $s, 2);
if ($action=='download') {
    header("Content-Disposition: attachment; filename=defaultqrcode.png"); 
}