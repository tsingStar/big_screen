<?php
/**
 * 模块前台页面
 * PHP version 5.4+
 *
 * @category Modules
 *
 * @package Ydj
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Frontbase.php';
require_once BASEPATH . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "function.php";
/**
 *  模块前台页面
 *  PHP version 5.4+
 *
 * @category Modules
 *
 * @package Ydj
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
require_once BASEPATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'function.php';
use \Modules\Prize\Controllers\Api;
use \Modules\Ydj\Models\Ydj_model;

class Front extends Frontbase
{
    public $_ydj_model = null;
    public function __construct()
    {
        parent::__construct();
        $this->_ydj_model = new Ydj_model();
    }
    /**
     * 摇大奖界面
     *
     * @return void
     */
    public function index()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $config = $this->_ydj_model->getCurrentConfig($id);
        $configs = $this->_ydj_model->getAllConfig();
        $prize_api = new Api();
        $resultdata = $prize_api->getprizes('ydj', $config['id']);
        $prizesjson = '{}';
        if ($resultdata['code'] > 0) {
            $prizesjson = json_encode($resultdata['data']);
        }
        $winners=[];
        if($config['status']==3){
            $data=$prize_api->getwinners('ydj',$config['id'],true);
            if($data['code']>0){
                $winners=$data['data'];
                foreach($winners as $k=>$v){
                $userinfo=$this->_formatuserinfo($v['userid'],$config['showstyle']);
                    $winners[$k]['nickname']=$userinfo['nickname'];
                }
            }
        }
        $this->assign('title','摇大奖');
        $this->assign('winners', $winners);
        $this->assign('prizesjson', $prizesjson);
        $this->assign('configs', $configs);
        $this->assign('configjson', json_encode($config));
        $this->show('index.html');
    }

    private function _formatuserinfo($userid, $showstyle)
    {
        $userinfo = $this->_ydj_model->getJoinUser($userid, true);
        $userinfo['nickname'] = processNickname($userinfo, $showstyle);
        return $userinfo;
    }

    public function ajaxGetData()
    {
        $config = $this->_ydj_model->getCurrentConfig();
        //未开始的状态
        if ($config['status'] == 1) {
            $data = $this->_ydj_model->recentJoinUsers();
            $returndata = ['code' => 1, 'message' => '游戏未开始', 'data' => ['config' => $config, 'data' => $data]];
            echo json_encode($returndata);
            return;
        }
        if ($config['status'] == 2) {
            $lefttime = intval($config['duration']) - (time() - intval($config['started_at']));
            $lefttime = $lefttime < 0 ? 0 : $lefttime;
            $config['lefttime'] = $lefttime;
            if($lefttime<=0){
                $winners=$this->stopgame();
                $config['status']=3;
                $returndata = ['code' => 1, 'message' => '游戏结束', 'data' => ['config' => $config,'data'=>$winners]];
                echo json_encode($returndata);
                return;
            }
            $this->_ydj_model->setMaxPrizeIndex();
            $winners = $this->_ydj_model->getRecentWinners();
            foreach ($winners as $k => $v) {
                $prizeid = $this->_ydj_model->getPrizeid($k);
                $data['winners'][] = ['userinfo' => $this->_formatuserinfo($v, $config['showstyle']), 'prizeid' => $prizeid,'k'=>$k];
            }
            $returndata = ['code' => 1, 'message' => '游戏进行中', 'data' => ['config' => $config, 'data' => $data['winners']]];
            echo json_encode($returndata);
            return;
        }
        if($config['status']==3){
            $this->_ydj_model->savewinners();
            $returndata = ['code' => 1, 'message' => '游戏已经结束', 'data' => ['config' => $config]];
            echo json_encode($returndata);
            return;
        }
    }
    public function stopgame(){
        $config = $this->_ydj_model->getCurrentConfig();
        $result = $this->_ydj_model->endGame(0);
        $data=$this->_ydj_model->savewinners();
        $prize_api = new Api();
        foreach($data as $k=>$v){
            $prize_api->winprizebatch('ydj',$config['id'],$v,$k,'摇大奖');
        }

        $winners=$prize_api->getwinners('ydj',$config['id'],true);
        if($winners['code']>0){
            foreach($winners['data'] as $k=>$v){

                // $winners=$data['data'];
                foreach($winners['data'] as $k=>$v){
                    $userinfo=$this->_formatuserinfo($v['userid'],$config['showstyle']);
                    $winners['data'][$k]['nickname']=$userinfo['nickname'];
                }

                // $winners['data'][$k]=$this->_formatuserinfo($v['userid'],$config['showstyle']);
            }
            return $winners['data'];
        }
        return null;
    }
    public function ajaxPostData()
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors','On');
        $action = isset($_POST['action']) ? strval($_POST['action']) : '';
        if (!in_array($action, ['start', 'reset'])) {
            $returndata = ['code' => -1, 'message' => '格式错误'];
            echo json_encode($returndata);
            return;
        }
        switch ($action) {
            case 'start':
                $this->start();
                break;
            case 'reset':
                $this->reset();
                break;
        }
    }
    public function start()
    {
        $config = $this->_ydj_model->getCurrentConfig();
        $return=$this->_ydj_model->genPrizeList($config['id']);
        if(!$return){
            $returndata = array('code' => -2, 'message' => "请检查奖品是否已经设置了。");
            echo json_encode($returndata);
            return;
        }
        $result = $this->_ydj_model->startGame($config['id']);
        $returndata = array('code' => -1, 'message' => "游戏状态不对");
        if ($result > 0) {
            $returndata = array('code' => 1, 'message' => "游戏开始");
        }
        echo json_encode($returndata);
        return;
    }
    public function reset()
    {
        $result = $this->_ydj_model->resetGame(0);
        //保存缓存中的数据到数据库中
        
        $returndata = array('code' => -1, 'message' => "游戏状态不对");
        if ($result > 0) {
            $returndata = array('code' => 1, 'message' => "游戏重置完成");
        }
        echo json_encode($returndata);
        return;
    }

}
