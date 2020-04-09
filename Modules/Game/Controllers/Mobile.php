<?php
require_once MODULE_PATH.DIRECTORY_SEPARATOR.'Mobilebase.php';
use Modules\Game\Models\GameConfig_model;
use Modules\Game\Models\GameThemes_model;
use Modules\Game\Models\Game_model;
use Modules\Game\Models\ThemeFactory;
use Modules\Menu\Controllers\Api;
class Mobile extends Mobilebase
{
    /**
     * 手机端游戏界面
     */
    public function index(){
        $userinfo=$this->getUserinfo();
        $gameconfig_m=new GameConfig_model();
        $game_model=new Game_model();
        $id=$gameconfig_m->getCurrentConfigId();
        $currentconfig=$gameconfig_m->getCurrentConfig($id);
        
        $this->assign('title','');
        $menu_api=new Api();
        $custommenu=$menu_api->getAll(array('rentopenid'=>$userinfo['openid']));
        $game_model->joinGame($userinfo,$currentconfig['id'],$currentconfig['winagain']);
        $joinstatus=$game_model->getJoinStatus($userinfo['id'],$currentconfig['id']);
        $this->assign('custommenu',$custommenu);
        $this->assign('joinstatus',$joinstatus);
        $this->assign('config',$currentconfig);
        $this->assign('userinfo',json_encode($userinfo));
        $this->show(strtolower($currentconfig['themepath']) . DIRECTORY_SEPARATOR .'\index.html');
    }
    //获取数据
    public function ajaxGetData(){
        $gameconfig_m=new GameConfig_model();
        $id=$gameconfig_m->getCurrentConfigId();
        $currentconfig=$gameconfig_m->getCurrentConfig($id);
        $returndata=['code'=>1,'data'=>['id'=>$currentconfig['id'],'status'=>$currentconfig['status']]];
        echo json_encode($returndata);
        return;
    }

    public function ajaxPostData(){
        $userid=isset($_POST['userid'])?intval($_POST['userid']):0;
        if($userid<=0){
            $returndata=['code'=> -1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $points=isset($_POST['points'])?intval($_POST['points']):1;
        $gameconfig_m=new GameConfig_model();
        $id=$gameconfig_m->getCurrentConfigId();
        
        $currentconfig = $gameconfig_m->getCurrentConfig($id);
        if($currentconfig['status']==3){
            $returndata=['code'=>2,'message'=>'游戏结束'];
            echo json_encode($returndata);
            return;
        }
        $game_model=new Game_model();
        $game_model->addScore($id,$userid,$points);
        $returndata=['code'=>1,'message'=>'分数已经记录完成'];
        echo json_encode($returndata);
        return;
    }
}