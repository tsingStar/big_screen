<?php
require_once('common.php');
require_once('../Modules/Menu/Controllers/Api.php');
require_once('../Modules/Menu/Models/Menu_model.php');
require_once('../Modules/Prize/Controllers/Api.php');
use Modules\Menu\Controllers\Api as Menu_Api;
use \Modules\Prize\Controllers\Api;
defined('BASEPATH') or define('BASEPATH',str_replace(DIRECTORY_SEPARATOR.'mobile', '', dirname(__FILE__)));
function modules_api_autoloader($className){
    $class=explode('\\', $className);
    $str=implode(DIRECTORY_SEPARATOR, $class);
    $filepath=BASEPATH.DIRECTORY_SEPARATOR.$str.'.php';
    if(file_exists($filepath)){
        require $filepath;
    }else{

    }
}
spl_autoload_register('modules_api_autoloader');
$load->model('Plugs_model');
$plugs=$load->plugs_model->getPlugs(1);
$openid=$_GET['rentopenid'];
$isopen=false;
$cjtext=[];
foreach($plugs as $item){
	if($item['choujiang']==1){
		$isopen=true;
		$cjtext[$item['name']]=$item['title'];
	}
}
if(!$isopen){
	header('location:qiandao.php?rentopenid='.$openid);
}

$load->model('Flag_model');
$myinfo=$load->flag_model->getUserinfo($openid);

$newzjlist=array();
$statetext=array('未中','','中奖','已发');
$prize_api = new \Modules\Prize\Controllers\Api();
$prizes = $prize_api->getmyprizes($myinfo['id']);
if(is_array($prizes)){
	foreach($prizes as $v){
		if($v['plugname']=='importlottery'){
			continue;
		}
		$item['fromplug']=empty($v['title'])?$cjtext[$v['plugname']].':第'.$v['activityid'].'轮':$v['title'];
		$item['awardname']=$v['prizename'];
		$item['zjdatetime']=date('m月d日 H:i:s',$v['wintime']);
		$item['status']=$statetext[$v['status']];
		$item['prizeimg']=$v['prizedata']['text'];
		$newzjlist[]=$item;
	}
}

//模版页面相关内容
$smarty = new Smarty;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->compile_dir =COMPILEPATH;

$menu_api=new Menu_Api();
$custommenu=$menu_api->getAll(array('rentopenid'=>$openid));
$smarty->assign('custommenu',$custommenu);

$smarty->assign('title','中奖结果');
$smarty->assign('openid',$openid);
$smarty->assign('user',$myinfo);
$smarty->assign('zjlist',$newzjlist);
$smarty->assign('plugs',$plugs);
$smarty->assign('erweima',$weixin_config['erweima']);
$smarty->display('template/app_header.html');
$smarty->display('template/app_zjlist.html');
$smarty->display('template/app_footer.html');