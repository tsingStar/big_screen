<?php
/**
 * 模块前台页面
 * PHP version 5.5+
 *
 * @category Modules
 *
 * @package Lottery
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
require_once BASEPATH . DIRECTORY_SEPARATOR .'library'.DIRECTORY_SEPARATOR.'emoji'.DIRECTORY_SEPARATOR.'emoji.php';
require_once BASEPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'flag_model.php';

/**
 *  模块前台页面
 *  PHP version 5.5+
 *
 * @category Modules
 *
 * @package Lottery
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */

use Modules\Lottery\Models\LotteryConfig_model;
use Modules\Lottery\Models\LotteryThemes_model;
use Modules\Lottery\Models\Lottery_model;
use Modules\Prize\Controllers\Api;
use Common\helpers\ResponseHelper;
use Common\helpers\LogUnit;
use Common\Base\RequestException;
use Modules\Lottery\Models\Beans\meepoXianChangUserBean;
use Modules\Prize\Models\User_Prize_model;

class Front extends Frontbase
{
    // var $_ydj_model=null;
    public function __construct()
    {
        parent::__construct();
        $this->_flag_model = new Flag_model();
    }

    /**
     * 摇大奖界面
     *
     * @return void
     */
    public function index()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $type = isset($_GET['type']) ? intval($_GET['type']) : 0;
        $lottery_model = new Lottery_model();
        if ($id <= 0) {
            $id = $lottery_model->getCurrent();
        }
        $lotteryconfig_model = new LotteryConfig_model();
        $lotteryconfigs=$lotteryconfig_model->getAll('id,title,themeid');
        $lotteryconfig = $lotteryconfig_model->getById($id, true, true);
        $lottery_model->setCurrent($lotteryconfig['id']);
        $prize_api = new Api();
        $prizesdata = $prize_api->getprizes('lottery', $lotteryconfig['id']);
        $prizes = [];
        if ($prizesdata['code'] > 0) {
            foreach ($prizesdata['data'] as $item) {
                $prizes[] = $item;
            }
        }
        $lotteryconfig['themepath'] = $type ? '3dlottery' : $lotteryconfig['themepath'];
        $this->assign('prizes', json_encode($prizes));
        $this->assign('config', $lotteryconfig);
        $this->assign('configs',json_encode($lotteryconfigs));
        $this->show($lotteryconfig['themepath'] . DIRECTORY_SEPARATOR . 'index.html', true);
    }

    //已经中奖的用户列表
    public function ajaxGetWinners()
    {
        $activityid = isset($_POST['activityid']) ? intval($_POST['activityid']) : 0;
        $prizeid = isset($_POST['prizeid']) ? intval($_POST['prizeid']) : 0;
        if ($prizeid <= 0) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $lotteryconfig_model = new LotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($activityid, false);

        $prize_api = new Api();
        $data = $prize_api->getWinnersByPrizeId('lottery', $lotteryconfig['id'], $prizeid);
        if ($data['code'] <= 0) {
            $returndata = ['code' => -2, 'message' => '数据错误'];
            echo json_encode($returndata);
            return;
        }
        $winners = [];
        foreach ($data['data'] as $val) {
            $winner = $this->_getUserInfo($val['userid']);
            $winners[] = ['nick_name' => $this->_processNickname($winner, $lotteryconfig['showtype']), 'avatar' => $winner['avatar']];
        }
        $winagain = $lotteryconfig['winagain'] == 2;
        $lottery_model = new Lottery_model();
        $num = $lottery_model->getParticipantsNum($prizeid, $activityid, $winagain);
        $returndata = ['code' => 1, 'message' => '', 'data' => ['winners' => array_reverse($winners), 'participants' => $num]];
        echo json_encode($returndata);
        return;
    }

    //获取中奖结果
    public function ajaxGetLotteryResult()
    {
        $num = isset($_POST['num']) ? intval($_POST['num']) : 0;
        $activityid = isset($_POST['activityid']) ? intval($_POST['activityid']) : 0;
        $prizeid = isset($_POST['prizeid']) ? intval($_POST['prizeid']) : 0;
        if ($prizeid <= 0) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $lotteryconfig_model = new LotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($activityid, false);
        $lottery_model = new Lottery_model();
        $data = $lottery_model->getRandZjlist($num, $prizeid, $lotteryconfig['id'], $lotteryconfig['winagain']);
        if (empty($data)) {
            $returndata = ['code' => -2, 'message' => '没有人可以参与抽奖了'];
            echo json_encode($returndata);
            return;
        }
        $prize_api = new Api();
        $result = $prize_api->winprizebatch('lottery', $lotteryconfig['id'], $data, $prizeid, $lotteryconfig['title']);
        $returndata = ['code' => -1, 'message' => '失败'];

        if ($result['code'] > 0) {
            $winners = [];
            foreach ($data as $key => $item) {
                $item['nick_name'] = $this->_processNickname($item, $lotteryconfig['showtype']);
                $item['bd_data'] = [
                    'bd_mqwyk' => $item['signname'],
                    'mobile' => $item['phone']
                ];
                $winners[] = $item;
            }
            $returndata = ['code' => 1, 'message' => '', 'data' => $winners];
        }
        echo json_encode($returndata);
        return;
    }

    //获取一些头像 用于闪烁
    public function ajaxGetTempUsers()
    {
        $num=isset($_GET['num'])?intval($_GET['num']):30;
        $num=$num<30?30:$num;
        $num=$num>100?100:$num;
        $data = $this->_flag_model->getRandUsers($num, [], 'id,avatar');
        $returndata = ['code' => 1, 'message' => '', 'data' => $data];
        echo json_encode($returndata);
        return;
    }

    private function _getUserInfo($userid)
    {
        $userinfo = $this->_flag_model->getUserinfoById($userid);
        return $userinfo;
    }

    function _processNickname($userinfo, $showtype)
    {
        $userinfo['nick_name'] = $userinfo['nickname'];
        if ($showtype == 'signname' && !empty($userinfo['signname'])) {
            //显示姓名
            $userinfo['nick_name'] = $userinfo['signname'];
        }
        if ($showtype == 'phone' && !empty($userinfo['phone'])) {
            //显示电话
            $userinfo['nick_name'] = substr_replace($userinfo['phone'], '****', 3, 4);
        }
        if ($showtype == 'nickname' || empty($userinfo['nick_name'])) {
            $userinfo['nick_name'] = emoji_unified_to_html(emoji_softbank_to_unified($userinfo['nickname']));
        }
        return $userinfo['nick_name'];
    }

    /**
     * 获取礼物奖励列表
     */
    public function prize_ajax()
    {
        try {
            ResponseHelper::accessAllowOrigin();
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $lottery_model = new Lottery_model();
            if ($id <= 0) {
                $id = $lottery_model->getCurrent();
            }
            $lotteryconfig_model = new LotteryConfig_model();
            $lotteryconfig = $lotteryconfig_model->getById($id, true, true);
            $lottery_model->setCurrent($lotteryconfig['id']);
            $prize_api = new Api();
            $prizesdata = $prize_api->getprizes('lottery', $lotteryconfig['id']);
            $prizes = [];
            if ($prizesdata['code'] > 0) {
                foreach ($prizesdata['data'] as $item) {
                    $prizes[] = $item;
                }
            }
            $joinNum = $this->_flag_model->getShenheCount();
            if ($prizes) {
                $data['prizes'] = $prizes;
                $data['joinNum'] = $joinNum;
                ResponseHelper::setStatus();
                ResponseHelper::setValue('data', $data);
            }
        } catch (Exception $ex) {
            LogUnit::writeException($ex);
        }
        ResponseHelper::ajaxReturn();
    }

    /**
     * 制定获取定长的用户列表信息用于头像闪烁
     */
    public function ajaxGetTempUsersInfo()
    {
        try {
            ResponseHelper::accessAllowOrigin();
            $num = isset($_POST['num']) ? intval($_POST['num']) : 30;
            $data = $this->_flag_model->getRandUsers($num, [], 'id,avatar,nickname');
            if ($data) {
                foreach ($data as $key => $item) {
                    $item['nickname'] = pack('H*', $item['nickname']);
                    $data[$key] = $item;
                }
                ResponseHelper::setStatus();
                ResponseHelper::setValue('data', $data);
            }
        } catch (Exception $ex) {
            LogUnit::writeException($ex);
        }
        ResponseHelper::ajaxReturn();
    }

    /**
     * 删除中奖记录
     */
    public function remove_lottery_recode()
    {
        try {
            ResponseHelper::accessAllowOrigin();
            $record_id = isset($_POST['record_id']) ? intval($_POST['record_id']) : 0;
            $userPrizeModel = new User_Prize_model();
            $result = $userPrizeModel->removeLotterRecode($record_id);
            if ($result) {
                ResponseHelper::setStatus();
            }
        } catch (Exception $ex) {
            LogUnit::writeException($ex);
        }
        ResponseHelper::ajaxReturn();
    }


    /**
     * 抽奖清空
     */
    public function lottery_reset()
    {
        try {
            ResponseHelper::accessAllowOrigin();
            $activityid = isset($_POST['activityid']) ? intval($_POST['activityid']) : 0;
            $prizeid = isset($_POST['prizeid']) ? intval($_POST['prizeid']) : 0;
            if ($prizeid <= 0 || !$activityid) {
                throw new RequestException('数据格式错误', -1);
            }
            $userPrizeModel = new User_Prize_model();
            $result = $userPrizeModel->lotteryReset($activityid, $prizeid);
            if ($result) {
                ResponseHelper::setStatus();
            }
        } catch (RequestException $ex) {
            ResponseHelper::setException($ex);
        } catch (Exception $ex) {
            LogUnit::writeException($ex);
        }
        ResponseHelper::ajaxReturn();
    }


    /**
     * 3dlottery 获取用户中奖人员列表信息,参考ajaxGetWinners
     *
     */
    public function prize_info()
    {
        try {
            $activityid = isset($_POST['activityid']) ? intval($_POST['activityid']) : 0;
            $prizeid = isset($_POST['prizeid']) ? intval($_POST['prizeid']) : 0;
            if ($prizeid <= 0) {
                throw new RequestException('数据格式错误', -1);
            }
            $lotteryconfig_model = new LotteryConfig_model();
            $lotteryconfig = $lotteryconfig_model->getById($activityid, false);

            $prize_api = new Api();
            $data = $prize_api->getWinnersByPrizeId('lottery', $lotteryconfig['id'], $prizeid);
            if ($data['code'] <= 0) {
                throw new RequestException('数据格式错误', -2);
            }
            $meepoXianChangUserBean = new meepoXianChangUserBean();
            $winnerData = [];
            foreach ($data['data'] as $val) {
                $winner = $this->_getUserInfo($val['userid']);
                if (!$winner) {
                    continue;
                }
                $meepoXianChangUserBean->setId($val['id'])->setAvatar($winner['avatar'])->setDbMqwyk($winner['signname'])->setMobile($winner['phone'])->setNickName($winner['nickname'])->setOpenid($winner['openid']);
                $winnerData[] = $meepoXianChangUserBean->getOrigin();
            }
            $lottery_model = new Lottery_model();
            $num = $lottery_model->getParticipantsNum($prizeid, $activityid);
            $data = [
                'winners' => $winnerData,
                'num' => $num
            ];
            ResponseHelper::setStatus();
            ResponseHelper::setValue('data', $data);

        } catch (RequestException $ex) {
            ResponseHelper::setException($ex);
        } catch (Exception $ex) {
            LogUnit::writeException($ex);
        }
        ResponseHelper::ajaxReturn();
    }


}