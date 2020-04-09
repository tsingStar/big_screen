<?php
/**
 * 摇大奖手机端页面
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
require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Mobilebase.php';
/**
 * 摇大奖手机端页面
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
use \Modules\Choujiang\Models\Choujiang_model;
use \Modules\Menu\Controllers\Api;
use \Modules\Prize\Controllers\Api as PrizeApi;

class Mobile extends Mobilebase
{
    /**
     * 手机端抽奖界面
     *
     * @return void
     */
    public function index()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            return false;
        }
        $cj_model = new Choujiang_model();
        $config = $cj_model->getById($id);
        $theme = $cj_model->getThemeById($config['themeid']);

        $prizeapi = new PrizeApi();
        $prizes = $prizeapi->getprizes('choujiang', $id);

        $userinfo = $this->getUserinfo();
        $joininfo = $cj_model->joinGame($config, $userinfo['id']);

        $menu_api = new Api();
        $custommenu = $menu_api->getAll(array('rentopenid' => $userinfo['openid']));
        $this->assign('custommenu', $custommenu);

        $this->assign('joininfo', json_encode($joininfo));
        $this->assign('prizes', $prizes['data']);
        $this->assign('userinfo', json_encode($userinfo));
        $this->assign('openid', $userinfo['openid']);
        $this->assign('themepath', $theme['themepath']);
        $this->assign('config', $config);
        $this->show($theme['themepath'] . '/index.html');

    }
    /**
     * 获取抽奖
     */
    public function ajax_act_get_prize()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;
        if ($id <= 0 || $userid <= 0) {
            $returndata = ['code' => -1, 'message' => "信息错误"];
            echo json_encode($returndata);
            return;
        }
        $cj_model = new Choujiang_model();
        $config = $cj_model->getById($id);
        $now = time();
        if ($config['started_at'] > $now || $config['ended_at'] < $now) {
            $returndata = ['code' => -3, 'message' => "抽奖时间不正确"];
            echo json_encode($returndata);
            return;
        }
        $theme = $cj_model->getThemeById($config['themeid']);

        $return = $cj_model->incrCjtimes($id, $userid);
        if ($return == false) {
            $returndata = ['code' => -2, 'message' => "您的抽奖次数已经用完了"];
            echo json_encode($returndata);
            return;
        }
        $prizeapi = new PrizeApi();
        $result = $prizeapi->winprize('choujiang', $id, $userid, $theme['themename'] . ':' . $config['title']);
        if ($result['code'] < 0) {
            $returndata = ['code' => -1, 'message' => "信息错误"];
            echo json_encode($returndata);
            return;
        }
        $returndata = ['code' => 1, 'message' => '', 'data' => $result['data']['prize']];
        echo json_encode($returndata);
        return;

    }
}
