<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/http_helper.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
require_once(dirname(__FILE__) . '/../common/CacheFactory.php');
require_once(dirname(__FILE__) . '/../common/function.php');
$style=getparams('style');
$style = "meepo";
$load->model('Wall_model');
$wall_config= $load->wall_model->getConfig();
$load->model('Weixin_model');
$weixin_config= $load->weixin_model->getConfig();

$smarty = new Smarty;
$smarty->caching = false;
$apppath=str_replace(DIRECTORY_SEPARATOR.'wall', '', dirname(__FILE__));
$smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;

$smarty->assign('wall_config',$wall_config);

$smarty->assign('title','上墙');
$smarty->assign('erweima',$weixin_config['erweima']);
$qd_nums=0;


$load->model('System_Config_model');
$showtype = $load->system_config_model->get("signnameshowstyle");
$load->model('Flag_model');
$qiandaonum=$load->flag_model->getShenheCount();
$smarty->assign('from','qiandao');
$smarty->assign('qiandaonum',$qiandaonum);
$smarty->assign('style',$style);
$smarty->assign('lastid',getlastid());
$smarty->display('themes/'.$style.'/header.html');
$smarty->display('themes/'.$style.'/login.html');
$smarty->display('themes/'.$style.'/footer.html');
//获取最后一条信息的id
function getlastid(){
    $wall_m=new M('wall');
    $row=$wall_m->find(' ret=1 order by id desc limit 1','id');
    if(isset($row['id'])){
        return intval($row['id']);
    }else{
        return 0;
    }
}
?>
