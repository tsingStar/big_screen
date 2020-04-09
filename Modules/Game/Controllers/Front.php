<?php
/**
 *
 * 检查是否有正在进行中的游戏,如果有自动切换到运行中的轮次
 * 如果没有则自动跳转到最前面的一轮未开始的游戏,这个时候 可以切换上一轮 下一轮
 *
 */
require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Frontbase.php';
require_once BASEPATH . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "function.php";
require_once BASEPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'flag_model.php';

use Modules\Game\Models\GameConfig_model;
use Modules\Game\Models\Game_model;

class Front extends Frontbase
{
    public function index()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $gameconfig_m = new GameConfig_model();
        $currentconfig = null;
        if ($id <= 0) {
            //刷新当前的轮次界面
            $id = $gameconfig_m->getCurrentConfigId();
        }else{
            //切换轮次
            //需要清空一下参与的数据
            $game_model=new Game_model();
            $game_model->clearJoinData($id);
        }
        $currentconfig = $gameconfig_m->getById($id,true,true);
        $gameconfig_m->setCurrentConfigId($currentconfig['id']);
        if (empty($currentconfig)) {
            echo '';
            return;
        }
        $configs=$gameconfig_m->getAll('id,themeid');
        $this->assign('configs',json_encode($configs));
        //清空一下加入的人的数据
        $this->assign('config', $currentconfig);

        $this->show(strtolower($currentconfig['themepath']) . DIRECTORY_SEPARATOR . 'index.html', true);
    }
    /**
     * 获取当前活动的数据
     */
    public function ajaxGetData()
    {
        $gameconfig_m = new GameConfig_model();
        $id = $gameconfig_m->getCurrentConfigId();
        $currentconfig = $gameconfig_m->getCurrentConfig($id);
        $returndata = ['code' => 1];
        //游戏未开始,返回加入游戏的人员名单数据
        if ($currentconfig['status'] == 1) {
            $returndata['config'] = [];
            $returndata['config']['id'] = $id;
            $returndata['config']['status'] = 1;
            $returndata['users'] = $this->getJoinUsers();
            echo json_encode($returndata);
            return;
        }
        //游戏已经开始,返回前10名的数据
        if ($currentconfig['status'] == 2) {
            $returndata['config'] = [];
            $returndata['config']['id'] = $id;
            $returndata['config']['status'] = 2;
            $returndata['users'] = $this->getTopTen();
            echo json_encode($returndata);
            return;
        }
        //游戏已经结束,返回游戏状态
        if ($currentconfig['status'] == 3) {
            
            $returndata['config'] = [];
            $returndata['config']['id'] = $id;
            $returndata['config']['status'] = 3;
            $returndata['users'] = $this->getResults();
            echo json_encode($returndata);
            return;
        }
        $returndata = ['code' => -1];
        echo json_encode($returndata);
        return;
    }

    public function ajaxPostData()
    {
        $action = isset($_POST['action']) ? strval($_POST['action']) : '';
        if (!in_array($action, ['start', 'stop', 'reset'])) {
            $returndata = ['code' => -1, 'message' => '格式错误'];
            echo json_encode($returndata);
            return;
        }
        switch ($action) {
            case 'start':
                $this->startGame();
                break;
            case 'stop':
                $this->stopGame();
                break;
            case 'reset':
                $this->resetGame();
                break;
        }
    }
    /**
     * 开始游戏
     *
     * @return void
     */
    private function startGame()
    {
        $gameconfig_m = new GameConfig_model();
        $id = $gameconfig_m->getCurrentConfigId();
        $currentconfig = $gameconfig_m->getCurrentConfig($id);
        if($currentconfig['status']!=1){
            return;
        }
        //先检查一下签到人数
        $joinusers = $this->getJoinUsers();
        if ($joinusers['num'] <= 0) {
            $returndata = ['code' => -2, 'message' => "当前活动无人参与，请等待嘉宾加入后再开始游戏！"];
            echo json_encode($returndata);
            return;
        }
        $return = $gameconfig_m->save(['id' => $id, 'status' => 2]);
        if ($return) {
            $returndata = ['code' => 1, 'data' => $currentconfig];
            echo json_encode($returndata);
            return;
        }
        $returndata = ['code' => -1, 'message' => "操作失败，可能网络不稳定，请检查您的网络！"];
        echo json_encode($returndata);
        return;
    }
    public function gameResult()
    {
        $gameconfig_m = new GameConfig_model();
        $game_m = new Game_model();
        $id = $gameconfig_m->getCurrentConfigId();
        $currentconfig = $gameconfig_m->getCurrentConfig($id);
        $users = $game_m->getWinners($id, $currentconfig['showtype']);
        $this->assign('users', $users);
        $this->show(strtolower($currentconfig['themepath']) . DIRECTORY_SEPARATOR . 'result.html', true);
    }
    /**
     * 停止游戏（游戏结束用）
     *
     * @return void
     */
    private function stopGame()
    {
        $gameconfig_m = new GameConfig_model();
        $id = $gameconfig_m->getCurrentConfigId();
        $currentconfig = $gameconfig_m->getCurrentConfig($id);
        if($currentconfig['status']==3){
            return;
        }
        //前几名的数据写入到数据库中永久保存
        $game_m = new Game_model();
        $game_m->saveWinners($currentconfig['toprank'], $id);
        $return = $gameconfig_m->save(['id' => $id, 'status' => 3]);
        if ($return) {
            $returndata = ['code' => 1, 'message' => "游戏结束"];
            echo json_encode($returndata);
            return;
        }
        $returndata = ['code' => -1, 'message' => "操作失败，可能网络不稳定，请检查您的网络！"];
        echo json_encode($returndata);
        return;
    }
    /**
     * 重置游戏（重玩本轮）
     *
     * @return void
     */
    private function resetGame()
    {
        $gameconfig_m = new GameConfig_model();
        $game_m = new Game_model();
        $id = $gameconfig_m->getCurrentConfigId();
        $currentconfig = $gameconfig_m->getCurrentConfig($id);
        if($currentconfig['status']==1){
            return;
        }
        //清空缓存 清空中奖记录 重置游戏状态
        $game_m->clearData($id);

        $return = $gameconfig_m->save(['id' => $id, 'status' => 1]);
        if ($return) {
            $returndata = ['code' => 1, 'message' => "游戏已经重置，3秒后会自动刷新页面"];
            echo json_encode($returndata);
            return;
        }
        $returndata = ['code' => -1, 'message' => "操作失败，可能网络不稳定，请检查您的网络！"];
        echo json_encode($returndata);
        return;
    }
    /**
     * 获取加入游戏的人员名单
     *
     * @return void
     */
    private function getJoinUsers()
    {
        $gameconfig_m = new GameConfig_model();
        $game_m = new Game_model();
        $id = $gameconfig_m->getCurrentConfigId();
        $num=$game_m->getJoinUserNum($id);
        $users=$game_m->getRecentJoinUsers($id);
        $users=$users?$users:[];
        
        return ['num' => $num, 'userlist' => $users];
    }

    private function getTopTen()
    {
        $gameconfig_m = new GameConfig_model();
        $game_m = new Game_model();
        $id = $gameconfig_m->getCurrentConfigId();
        $topusers=$game_m->getTopUsers(10,$id);
        $currentconfig = $gameconfig_m->getCurrentConfig($id);
        $data=[];
        for($i=0,$l=count($topusers);$i<$l;$i++){
            //todo:nickname 需要根据设置改变
            $data[]=['avatar'=>$topusers[$i]['avatar'],'nickname'=>$topusers[$i][$currentconfig['showtype']],'point'=>$topusers[$i]['point']];
        }
        return $data;
    }
    
    private function getResults()
    {
        $gameconfig_m = new GameConfig_model();
        $game_m = new Game_model();
        $id = $gameconfig_m->getCurrentConfigId();
        $currentconfig = $gameconfig_m->getCurrentConfig($id);
        if($currentconfig['toprank']>10){
            return ['num'=>$currentconfig['toprank']];
        }else{
            $winners=$game_m->getWinners($id,$currentconfig['showtype']);
            return ['num'=>$currentconfig['toprank'],'users'=>$winners];
        }
    }
}
