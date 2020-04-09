<?php
require_once MODULE_PATH.DIRECTORY_SEPARATOR.'Mobilebase.php';
use Modules\Page\Models\Pages_model;
use Modules\Menu\Controllers\Api;
class Mobile extends Mobilebase
{
    /**
     * 手机端游戏界面
     */
    public function index(){
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $pages_model=new Pages_model();
        $currentdata = null;
        if ($id == 0) {
            $currentdata = $pages_model->getFirstMobilePage();
        } else {
            $currentdata = $pages_model->getById($id);
        }
        if($currentdata['type']==1){
            $currentdata = $pages_model->getFirstMobilePage();
        }
        $userinfo=$this->getUserinfo();
        $menu_api=new Api();
        $custommenu=$menu_api->getAll(array('rentopenid'=>$userinfo['openid']));
        $this->assign('custommenu',$custommenu);
        $this->assign('currentdata',$currentdata);
        $this->show('index.html');
    }
}