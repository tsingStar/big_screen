<?php
require_once('common.php');
require_once('../Modules/Menu/Controllers/Api.php');
require_once('../Modules/Menu/Models/Menu_model.php');
use Modules\Menu\Controllers\Api;
$load->model('Plugs_model');
$plugs=$load->plugs_model->getPlugs(1);

$openid=$_GET['rentopenid'];

$isopen=false;
foreach($plugs as $item){
	if($item[name]=='wall'){
		$isopen=true;
	}
}
if(!$isopen){
	header('location:qiandao.php?rentopenid='.$openid);
}
$load->model('Flag_model');
$myinfo=$load->flag_model->getUserinfo($openid);
//模版页面相关内容
$smarty = new Smarty;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->compile_dir =COMPILEPATH;

$smarty->assign('title','微信上墙');
$smarty->assign('openid',$openid);
$smarty->assign('user',$myinfo);
$menu_api=new Api();
$custommenu=$menu_api->getAll(array('rentopenid'=>$openid));
$smarty->assign('custommenu',$custommenu);
$smarty->assign('plugs',$plugs);
$smarty->assign('wallconfig',$wall_config);
$smarty->assign('erweima',$weixin_config['erweima']);
$smarty->display('template/app_header.html');
$smarty->display('template/app_wall.html');
$smarty->display('template/app_footer.html');