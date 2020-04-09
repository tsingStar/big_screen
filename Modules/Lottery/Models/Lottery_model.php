<?php

namespace Modules\Lottery\Models;

use Common\helpers\LogUnit;

require_once BASEPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'flag_model.php';

// use
class Lottery_model
{
    // var $_lotteryconfig_m=null;
    var $_view_lottery_m = null;
    /**
     * @var \M
     */
    var $_flag_model = null;
    var $_cache = null;

    public function __construct()
    {
        $this->_view_lottery_m = new \M('view_lottery');
        $this->_flag_model = new \Flag_model();
        $this->_cache = new \CacheFactory(CACHEMODE);
    }

    public function setCurrent($id)
    {
        $this->_cache->set('currentlotteryid', $id, 24 * 3600);
    }

    public function getCurrent()
    {
        $id = $this->_cache->get('currentlotteryid');
        return $id ? $id : 0;
    }

    /**
     * 获取参加活动的参与人数
     * @param $activityid 活动ID
     */
    public function getActivityParticipants($activityid)
    {
        $result = 0;
        try {
            $where = 'isdel = 1 and activityid = ' . $activityid;
            $result = $this->_view_lottery_m->find($where, '*', 'count');
        } catch (\Exception $ex) {
            LogUnit::writeLog($ex);
        }
        return $result;
    }

    /**
     * 获取可以参与到这个奖项的人数
     *
     *
     */
    public function getParticipantsNum($prizeid, $activityid, $winagain = 2)
    {
        //可参与人数=总的签到人数-不会中这个奖品的人
        //不会中这个奖品的人数=内定不会中的人数+内定必中其他奖项的人数+已经中过奖不能再中的人数
        $total = $this->_flag_model->getSignedCount();
        $where = ' isdel = 1 ';
        if ($winagain == 2) {
            $where .= ' and activityid = ' . $activityid;
        }
        $userlist = $this->_view_lottery_m->select($where);
        $exceptuserids = [];
        // $num=0;
        if (!empty($userlist)) {
            foreach ($userlist as $user) {
                //
                if ($user['status'] == 2 || $user['status'] == 3) {
                    $exceptuserids[] = $user['userid'];
                    continue;
                }
                //内定必中其他奖的人
                if ($user['designated'] == 2 && $user['prizeid'] != $prizeid) {
                    $exceptuserids[] = $user['userid'];
                    continue;
                }
                //内定不会中这个奖项的人
                if ($user['designated'] == 3 && $user['prizeid'] == $prizeid) {
                    $exceptuserids[] = $user['userid'];
                    continue;
                }
            }
        }
        $temparr = array_flip($exceptuserids);
        $exceptuserids = array_keys($temparr);
        $num = count($exceptuserids);
        return $total - $num;
    }

    /**
     * 获取随机中奖名单
     * 是否有内定设置,有内定设置先处理内定,然后检查是否达到了抽取人数,没有达到抽取人数
     *
     * @param string $num 抽取的人数
     * @param integer $prizeid 抽取的奖品编号
     * @param bool $winagain 其他轮次中过的人还会再次中奖 1表不可以重复参与,2表示可以重复
     *
     * @return array 返回中奖用户的id
     */

    public function getRandZjlist($num, $prizeid, $activityid, $winagain = 2)
    {
        $where = ' isdel = 1 ';
        if ($winagain == 2) {
            $where .= ' and activityid = ' . $activityid;
        }
        $userlist = $this->_view_lottery_m->select($where);
        $randusers = [];
        $exceptuserids = [];
        $leftnum = $num;
        if (!empty($userlist)) {
            //内定的用户序列
            $userids = [];
            //随机抽取时需要排除的用户id序列
            // $exceptuserids=[];
            foreach ($userlist as $user) {
                //已经中过奖的人
                if ($user['status'] == 2 || $user['status'] == 3) {
                    $exceptuserids[] = $user['userid'];
                    continue;
                }
                //内定必中的人
                if ($user['designated'] == 2 && $user['prizeid'] == $prizeid) {
                    $userids[$user['userid']] = ['id' => $user['userid'], 'designated' => $user['designated'], 'nickname' => pack('H*', $user['nickname']), 'avatar' => $user['avatar'], 'signname' => $user['signname'], 'phone' => $user['phone']];
                    $exceptuserids[] = $user['userid'];
                    continue;
                }
                //内定必中其他奖的人
                if ($user['designated'] == 2 && $user['prizeid'] != $prizeid) {
                    $exceptuserids[] = $user['userid'];
                    continue;
                }
                //内定不会中这个奖项的人
                if ($user['designated'] == 3 && $user['prizeid'] == $prizeid) {
                    $exceptuserids[] = $user['userid'];
                    continue;
                }
            }
            $leftnum = $num - count($userids);
            //内定的人数等于抽取的人数
            if ($leftnum == 0) {
                return $userids;
            }
            //内定的人数大于抽取的人数
            if ($leftnum < 0) {
                return array_slice($userids, 0, $num);
            }
            //内定的人数小于抽取的人数

            $temparr = array_flip($exceptuserids);
            $exceptuserids = array_keys($temparr);
        }

        $randusers = $this->_flag_model->getRandUsers($leftnum, $exceptuserids, 'id,nickname,avatar,signname,phone');
        foreach ($randusers as $val) {
            $userids[$val['id']] = ['id' => $val['id'], 'designated' => 1, 'signname' => $val['signname'], 'nickname' => pack('H*', $val['nickname']), 'avatar' => $val['avatar'], 'phone' => $val['phone']];
        }
        return $userids;
    }
}