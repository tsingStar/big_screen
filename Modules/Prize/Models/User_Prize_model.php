<?php
/**
 * 中奖发奖模块model
 * PHP version 5.4+
 *
 * @category Modules
 *
 * @package Prize
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */

namespace Modules\Prize\Models;
require_once dirname(__FILE__) . '/../../../models/flag_model.php';
require_once dirname(__FILE__) . '/../../../models/system_config_model.php';
require_once dirname(__FILE__) . '/../../../common/function.php';
require_once dirname(__FILE__) . '/../../../common/url_helper.php';
use Common\helpers\LogUnit;
use System_Config_model;
use \Modules\Prize\Models\Prizes_model;
use library\deejasdk\DeejaSDK;


use Flag_model;
use Exception;

/**
 * 中奖发奖模块model
 * PHP version 5.4+
 *
 * @category Modules
 *
 * @package Prize
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
class User_Prize_model
{
    var $_user_prize_m = null;
    var $_cache = null;
    var $_winlockcachename = 'prize_win_lock_cache';
    var $_prize_model = null;
    var $_cacheprefix = null;

    /**
     * 构造函数
     *
     * @return void
     */
    public function __construct()
    {
        $this->_user_prize_m = new \M('user_prize');
        
        // $this->_load=\Loader::getInstance();
        $this->_prize_model = new Prizes_model();

        // $this->_prize_model=new Prizes_model();
        if (CACHEMODE == "Redis") {
            $this->_cache = new \Predis\Client(
                array(
                    'scheme' => 'tcp',
                    'host' => REDIS_HOST,
                    'port' => REDIS_PORT,
                    'password' => REDIS_PASSWORD
                )
            );
            $this->_cacheprefix = CACHEPREFIX;
            // $this->_prizescachename='prizes';
        } else {
            $this->_cache = new \CacheFactory(CACHEMODE);
        }
    }

    /**
     * 获取分页的中奖记录
     *
     * @param string $plugname 组件名称
     * @param int $activityid 活动编号
     * @param int $page 页码
     * @param int $pagesize 每页记录条数
     *
     * @return array 分页数据
     */
    public function getPagedData($plugname = '', $activityid = 0, $page = 1, $pagesize = 30)
    {
        $where = ' 1 ';
        if ($plugname != '') {
            $where .= 'and plugname="' . $plugname . '" ';
        }
        if ($activityid > 0) {
            $where .= 'and activityid=' . $activityid;
        }
        $where .= ' and isdel=1 and weixin_user_prize.status>1 ';
        $order = ' order by id asc';
        $page = $page <= 0 ? 1 : $page;
        $pagesize = $pagesize <= 0 ? 30 : $pagesize;
        $limit = ' limit ' . (($page - 1) * $pagesize) . ',' . $pagesize;
        $join = ' left join weixin_flag on weixin_flag.id=weixin_user_prize.userid';
        $data = $this->_user_prize_m->select(
            $where . $order . $limit,
            'weixin_user_prize.id,weixin_user_prize.prizeid,weixin_user_prize.status,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone',
            '',
            'assoc',
            $join
        );
        foreach ($data as $k => $v) {
            $data[$k] = $this->_formatdata($v);
        }
        $count = $this->_user_prize_m->find($where, '*', 'count');
        return array('count' => $count, 'data' => $data);
    }
    /**
     * 获取红包发放的记录
     *
     * @param [type] $userprizeid
     * @return void
     */
    public function getPrizeRedpackets($userprizeid){
        $prize_redpacket_m=new \M('prize_redpackets');
        $data=$prize_redpacket_m->select('userprizeid='.$userprizeid.' order by created_at desc','orderno,status,created_at,remark');
        return $data;
    }
    //获取中奖记录

    /**
     * 获取所有中奖信息
     *
     * @param string $plugname 组件名
     * @param int $activityid 活动编号
     * @param bool $withuserinfo 是否包含用户表信息
     *
     * @return array 所有中奖信息
     */
    public function getAllData($plugname = '', $activityid = 0, $withuserinfo = true)
    {
        $where = ' 1 and isdel=1 and (weixin_user_prize.status=2 or weixin_user_prize.status=3) ';
        if ($plugname != '') {
            $where .= ' and plugname="' . $plugname . '" ';
        }
        if ($activityid > 0) {
            $where .= ' and activityid=' . $activityid;
        }
        if ($withuserinfo == true) {
            $select = 'weixin_user_prize.id,prizeid,userid,designated,weixin_user_prize.status,activityid,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone';
            $join = 'left join weixin_flag on weixin_flag.id=weixin_user_prize.userid';
            $order = ' order by weixin_user_prize.id asc';
            $data = $this->_user_prize_m->select($where . $order, $select, '', 'assoc', $join);
            foreach ($data as $k => $v) {
                $data[$k] = $this->_formatdata($v);
            }
        } else {
            $select = 'weixin_user_prize.id,prizeid,userid,designated,weixin_user_prize.status,activityid';
            $order = ' order by weixin_user_prize.id asc';
            $data = $this->_user_prize_m->select($where . $order, $select);

        }
        return $data;
    }

    public function getWinnersByPrizeId($plugname, $activityid, $prizeid)
    {
        $where = ' 1 and isdel=1 and (weixin_user_prize.status=2 or weixin_user_prize.status=3) ';
        if ($plugname != '') {
            $where .= ' and plugname="' . $plugname . '" ';
        }
        if ($activityid > 0) {
            $where .= ' and activityid=' . $activityid;
        }
        if ($prizeid > 0) {
            $where .= ' and prizeid=' . $prizeid;
        }
        $select = 'weixin_user_prize.id,prizeid,userid,designated,weixin_user_prize.status,activityid';
        $order = ' order by weixin_user_prize.id asc';
        $data = $this->_user_prize_m->select($where . $order, $select);
        return $data;
    }

    //用于导出excel表的数据
    public function getResultData($plugname = '', $activityid = 0)
    {
        $where = ' 1 and isdel=1 and (weixin_user_prize.status=2 or weixin_user_prize.status=3) ';
        if ($plugname != '') {
            $where .= ' and plugname="' . $plugname . '" ';
        }
        if ($activityid > 0) {
            $where .= ' and activityid=' . $activityid;
        }
        $select = 'weixin_user_prize.verifycode,weixin_user_prize.wintime,weixin_user_prize.awardtime, prizeid,weixin_user_prize.status,activityid,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone';
        $join = 'left join weixin_flag on weixin_flag.id=weixin_user_prize.userid';
        $order = ' order by weixin_user_prize.activityid asc,weixin_user_prize.wintime asc';
        $data = $this->_user_prize_m->select($where . $order, $select, '', 'assoc', $join);
        return $data;
    }

    /**
     * 获取分页的内定记录
     *
     * @param string $plugname 组件名称
     * @param int $activityid 活动编号
     * @param int $page 页码
     * @param int $pagesize 每页记录条数
     *
     * @return array 分页数据
     */
    public function getDesignatedPagedData($plugname = '', $activityid = 0, $page = 1, $pagesize = 30)
    {
        $where = ' 1 ';
        if ($plugname != '') {
            $where .= 'and plugname="' . $plugname . '" ';
        }
        if ($activityid > 0) {
            $where .= 'and activityid=' . $activityid;
        }

        $where .= ' and ( designated=2 or designated=3 ) and isdel=1 ';
        $order = ' order by id asc';
        $page = $page <= 0 ? 1 : $page;
        $pagesize = $pagesize <= 0 ? 30 : $pagesize;
        $limit = ' limit ' . (($page - 1) * $pagesize) . ',' . $pagesize;
        $join = ' left join weixin_flag on weixin_flag.id=weixin_user_prize.userid';
        $data = $this->_user_prize_m->select(
            $where . $order . $limit,
            'weixin_user_prize.id,weixin_user_prize.designated,weixin_user_prize.prizeid,weixin_user_prize.status,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone',
            '',
            'assoc',
            $join
        );
        foreach ($data as $k => $v) {
            $data[$k] = $this->_formatdata($v);
        }
        $count = $this->_user_prize_m->find($where, '*', 'count');
        return array('count' => $count, 'data' => $data);
    }

    /**
     * 用户接口 获取内定名单
     */
    public function getDesignated($plugname, $activityid)
    {
        $where = ' 1 ';

        $where .= 'and plugname="' . $plugname . '" ';

        $where .= 'and activityid=' . $activityid;


        $where .= ' and ( designated=2 or designated=3 ) and isdel=1 ';
        $order = ' order by id asc';

        $data = $this->_user_prize_m->select(
            $where . $order,
            'weixin_user_prize.userid,weixin_user_prize.id,weixin_user_prize.designated,weixin_user_prize.prizeid,weixin_user_prize.status'
        );
        foreach ($data as $k => $v) {
            $data[$k] = $this->_formatdata($v);
        }
        return $data;
    }

    /**
     * 格式化数据
     *
     * @param array $item 一行数据
     *
     * @return array 格式化后的数据
     */
    private function _formatdata($item)
    {
        if (isset($item['nickname'])) {
            $item['nickname'] = pack('H*', $item['nickname']);
        }
        if (isset($item['status'])) {
            // 中奖状态1表示未中2表示中奖3表示已发奖4取消
            $statustext = ['', '未中', '中奖未发', '中奖已发', '取消资格'];
            $item['statustext'] = $statustext[$item['status']];
        }
        if (isset($item['designated'])) {
            // 中奖状态1表示未中2表示中奖3表示已发奖4取消
            $designatedtext = ['', '', '必中', '不会中'];
            $item['designatedtext'] = $designatedtext[$item['designated']];
        }

        // $this->_load->model('Prizes_model');
        if (isset($item['prizeid'])) {
            $item['prize'] = $this->_prize_model->getById($item['prizeid']);
            // echo var_export($item['prize']);
        }
        return $item;
    }

    /**
     * 取消内定
     *
     * @param int $id 内定记录的id
     *
     * @return bool true设置成功false设置失败
     */
    public function cancelDesignated($id)
    {
        try {
            $find_designated = 'select prizeid,designated,status from weixin_user_prize where id=' . $id . ' limit 1';
            //取消冻结的奖品
            $unfreeze_prize_sql = 'update weixin_prizes set freezenum=freezenum-1,leftnum=leftnum+1 where id={prizeid} limit 1';
            //取消已经中奖的用户的内定记录
            $unfreeze_winner_sql='update weixin_prizes set leftnum=leftnum+1 where id={prizeid} limit 1';
            //奖品记录信息
            // $find_prize_sql = 'select num,freezenum,leftnum from weixin_prizes where id={prizeid} limit 1';
            //删除内定记录
            $delete_designated = "delete from weixin_user_prize where id=" . $id;
            $this->_user_prize_m->autocommit(false);
            $this->_user_prize_m->begin_transaction();
            $result = $this->_user_prize_m->query($find_designated);
            $data = $this->_user_prize_m->first_row($result);
            if (empty($data)) {
                $this->_user_prize_m->commit();
                return false;
            }
            $prizeid = $data['prizeid'];
            if ($data['designated'] == 2) {
                if($data['status']==2 || $data['status']==3){
                    //如果已经中奖的取消内定
                    $unfreeze_winner_sql = str_replace('{prizeid}', $prizeid, $unfreeze_winner_sql);
                    $this->_user_prize_m->query($unfreeze_winner_sql);
                }else{
                    //没有中奖的取消内定
                    $unfreeze_prize_sql = str_replace('{prizeid}', $prizeid, $unfreeze_prize_sql);
                    $this->_user_prize_m->query($unfreeze_prize_sql);
                }
                
            }

            $this->_user_prize_m->query($delete_designated);
            $this->_user_prize_m->commit();
            $this->_user_prize_m->autocommit(true);
            return true;
        } catch (Exception $e) {
            $this->_user_prize_m->rollback();
            $this->_user_prize_m->autocommit(true);
            return false;
        }
    }

    /**
     * 设置内定
     *
     * @param array $data 设置内定的信息
     *
     * @return bool true设置成功 false设置失败
     */
    public function setDesignated($data)
    {
        try {
            $data['title'] = isset($data['title']) ? $data['title'] : '';
            //找到奖品数据
            $find_prize_sql = 'select num,freezenum,leftnum from weixin_prizes where id=' . $data['prizeid'];
            //找已经存在的数据
            // $find_designated_sql='select count(*) as cnt from weixin_user_prize where userid='.$data['userid'].' and plugname="'.$data['plugname'].'" and activityid='.$data['activityid'];
            $find_designated_sql = 'select count(*) as cnt from weixin_user_prize where userid=' . $data['userid'] . ' and plugname="' . $data['plugname'] . '" and activityid=' . $data['activityid'] . ' and prizeid=' . $data['prizeid'];
            //冻结奖品
            $freeze_prize_sql = 'update weixin_prizes set freezenum=freezenum+1,leftnum=leftnum-1 where id=' . $data['prizeid'];
            //设置内定
            $designated_sql = 'insert into weixin_user_prize(prizeid,plugname,activityid,userid,designated,status,verifycode,created_at,isdel,title)values('
                . $data['prizeid'] . ',"' . $data['plugname'] . '",' . $data['activityid'] . ',' . $data['userid'] . ',' . $data['designated'] . ',1,"' . substr(uniqid(), 5, 8) . '",' . time() . ',1,"' . $data['title'] . '")';
            $this->_user_prize_m->autocommit(false);
            $this->_user_prize_m->begin_transaction();
            if ($data['designated'] == 2) {
                $this->_user_prize_m->query($freeze_prize_sql);
            }
            $result = $this->_user_prize_m->query($find_prize_sql);
            $prize = $this->_user_prize_m->first_row($result);
            if ($prize['leftnum'] < 0 || $prize['freezenum'] > $prize['num']) {
                $this->_user_prize_m->rollback();
                return false;
            }
            $this->_user_prize_m->query($designated_sql);
            $result = $this->_user_prize_m->query($find_designated_sql);
            $designatednum = $this->_user_prize_m->first_row($result);
            if ($designatednum['cnt'] > 1) {
                $this->_user_prize_m->rollback();
                return false;
            }
            $this->_user_prize_m->commit();
            $this->_user_prize_m->autocommit(true);
            return true;

        } catch (Exception $e) {
            $this->_user_prize_m->rollback();
            $this->_user_prize_m->autocommit(true);
            return false;
        }
    }


    /**
     * 按照id获取获奖信息
     *
     * @param int $id 获奖记录id
     *
     * @return array 获奖记录
     */
    public function getById($id)
    {
        $data = $this->_user_prize_m->find('id=' . $id);
        return $data;
    }

    public function winPrizeBatch($plugname, $activityid, $userids, $prizeid, $title = '')
    {
        $freezenum = 0;
        $leftnum = 0;
        $sql = [];
        $time = time();
        // echo var_export($userids);
        foreach ($userids as $v) {
            if ($v['designated'] == 2) {
                $sql[] = "update weixin_user_prize set status=2,wintime=" . $time . " where plugname='" . $plugname . "' and activityid=" . $activityid . " and designated=2 and userid=" . $v['id'];
                $freezenum++;
            } else {
                $sql[] = 'insert into weixin_user_prize (`prizeid`,`plugname`,`activityid`,`userid`,`designated`,`status`,`verifycode`,`created_at`,`wintime`,`awardtime`,`isdel`,`title`) values (' . $prizeid . ',"' . $plugname . '",' . $activityid . ',' . $v['id'] . ',1,2,"' . substr(uniqid(), 5, 8) . '",' . $time . ',' . $time . ',null,1,"' . $title . '");';
                $leftnum++;
            }
        }
        $changeleftnumsql = 'update weixin_prizes set freezenum=freezenum-' . $freezenum . ',leftnum=leftnum-' . $leftnum . ' where id=' . $prizeid;
        // echo $changeleftnumsql;
        $prizenumsql = 'select leftnum,freezenum from weixin_prizes where  id=' . $prizeid . ' and (freezenum<0 or leftnum<0)';
        // echo $prizenumsql;
        try {
            $this->_user_prize_m->autocommit(false);
            $this->_user_prize_m->begin_transaction();
            for ($i = 0, $l = count($sql); $i < $l; $i++) {
                $this->_user_prize_m->query($sql[$i]);
            }

            $result = $this->_user_prize_m->query($changeleftnumsql);
            if (!$result) {
                $this->_user_prize_m->rollback();
                $this->_user_prize_m->autocommit(true);
                return false;
            }
            //可能需要检查库存是否为负数
            $result = $this->_user_prize_m->query($prizenumsql);
            $data = $this->_user_prize_m->first_row($result);
            // echo var_export($data);
            //剩余量少于0
            if ($data) {
                $this->_user_prize_m->rollback();
                $this->_user_prize_m->autocommit(true);
                return false;
            }
            $this->_user_prize_m->commit();
            $this->_user_prize_m->autocommit(true);
            return true;
        } catch (Exception $e) {
            $this->_user_prize_m->rollback();
            $this->_user_prize_m->autocommit(true);
            return false;
        }
    }

    /**
     * 得奖
     *
     * @param int $prizeid 奖品id
     * @param int $userid 用户id
     * @param string $plugname 模块名称
     * @param int $activityid 活动编号
     *
     * @return bool true设置成功 false 失败
     */
    public function winPrize($plugname, $activityid, $userid, $title = '')
    {
        if (CACHEMODE == 'Redis') {
            $time = time() + 3;
            $ret = $this->_cache->setnx($this->_cacheprefix . $this->_winlockcachename, $time);
            if ($ret == 1) {
                $result = $this->_calwinner($plugname, $activityid, $userid, $title, $time);
                $this->_cache->del($this->_cacheprefix . $this->_winlockcachename);
                return $result;
            } else {
                //超时
                if ($this->_cache->get($this->_cacheprefix . $this->_winlockcachename) < time()) {
                    $this->_cache->del($this->_cacheprefix . $this->_winlockcachename);
                }
                return false;
            }
        } else {
            $lock = $this->_cache->get($this->_winlockcachename);
            $time = time() + 10;
            if ($lock == 'locked') {
                return false;
            } else {
                $this->_cache->set($this->_winlockcachename, 'locked', 10);
            }
            $result = $this->_calwinner($plugname, $activityid, $userid, $title, $time);
            $this->_cache->delete($this->_winlockcachename);
            return $result;
        }

    }

    public function _calwinner($plugname, $activityid, $userid, $title = '', $expire)
    {
        $where = ' plugname="' . $plugname . '" and activityid=' . $activityid . ' and userid=' . $userid;//.' and prizeid='.$prizeid;
        $data = $this->_user_prize_m->select($where, 'id,prizeid,designated,status');
        $prizes_model = new Prizes_model();
        $prizes = $prizes_model->getAvailablePrize($plugname, $activityid, true);
        //内定或者跳过
        $prizeid = 0;
        $hash_prizes = [];
        foreach ($prizes as $prize) {
            $hash_prizes[$prize['id']] = $prize;
            if ($prizeid == 0) {
                $rand = rand(0, 1000000);
                if ($prize['leftnum'] > 0 && $rand <= $prize['rate']) {
                    $prizeid = $prize['id'];
                }
            }
        }
        $sql = '';
        $isdesignated = false;
        $time = time();
        //不可以中的奖品id序列
        $prizeids = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                //内定中奖
                if ($item['designated'] == 2 && $item['status'] == 1) {
                    $prizeid = $item['prizeid'];
                    $isdesignated = true;
                    //获得奖项 结束
                    $sql = "update weixin_user_prize set status=2,wintime=" . $time . " where id=" . $item['id'];
                    break;
                }
                //内定不中这个奖
                if ($item['designated'] == 3 && $item['prizeid'] == $prizeid) {
                    array_push($prizeids, $item['prizeid']);
                    $prizeid = 0;
                }
                //已经中过这个奖品了
                if ($item['designated'] == 1 && $item['status'] != 1) {
                    array_push($prizeids, $item['prizeid']);
                    $prizeid = 0;
                }
            }
        }
        if (!empty($prizeids)) {
            foreach ($prizes as $prize) {
                if (!in_array($prize['id'], $prizeids)) {
                    $rand = rand(0, 1000000);
                    if ($prize['leftnum'] > 0 && $rand <= $prize['rate']) {
                        $prizeid = $prize['id'];
                    }
                }
                if ($prizeid > 0) {
                    break;
                }
            }
        }

        if ($prizeid == 0) {
            return false;
        }
        if ($sql == '') {
            $sql = 'insert into weixin_user_prize (`prizeid`,`plugname`,`activityid`,`userid`,`designated`,`status`,`verifycode`,`created_at`,`wintime`,`awardtime`,`isdel`,`title`) values (' . $prizeid . ',"' . $plugname . '",' . $activityid . ',' . $userid . ',1,2,"' . substr(uniqid(), 5, 8) . '",' . $time . ',' . $time . ',null,1,"' . $title . '");';
        }
        $changeleftnumsql = 'update weixin_prizes set leftnum=leftnum-1 where id=' . $prizeid;
        if ($isdesignated == true) {
            $changeleftnumsql = 'update weixin_prizes set freezenum=freezenum-1 where id=' . $prizeid;
        }
        $prizenumsql = 'select leftnum,freezenum from weixin_prizes where  id=' . $prizeid . ' and (freezenum<0 or leftnum<0)';
        try {
            $this->_user_prize_m->autocommit(false);
            $this->_user_prize_m->begin_transaction();
            $this->_user_prize_m->query($sql);
            $this->_user_prize_m->query($changeleftnumsql);
            //可能需要检查库存是否为负数
            $result = $this->_user_prize_m->query($prizenumsql);
            $data = $this->_user_prize_m->first_row($result);
            //剩余量少于0
            if ($data) {
                $this->_user_prize_m->rollback();
                $this->_user_prize_m->autocommit(true);
                return false;
            }
            //执行超时回滚
            if (CACHEMODE == 'Redis') {
                if ($expire < time()) {
                    $this->_user_prize_m->rollback();
                    $this->_user_prize_m->autocommit(true);
                    return false;
                }
            }
            $this->_user_prize_m->commit();
            $this->_user_prize_m->autocommit(true);
            return $hash_prizes[$prizeid];
        } catch (Exception $e) {
            $this->_user_prize_m->rollback();
            $this->_user_prize_m->autocommit(true);
            return false;
        }
    }

    /**
     * 获取我的中奖记录
     *
     * @param int $userid 用户id
     * @param string $plugname 进入的组件名称
     * @param int $activityid 活动的轮次信息
     * @param int $withcurrent 是否包含本轮游戏的信息1包含，2不包含
     *
     * @return mixed 有数据就返回数据，没有返回false;
     */
    public function getMyPrizes($userid, $plugname = '', $activityid = 0, $withcurrent = 1)
    {
        $where = ' userid=' . $userid . ' and weixin_user_prize.isdel=1 and (status=2 or status=3) ';
        if ($plugname != '') {
            $where = $where . ' and weixin_user_prize.plugname="' . $plugname . '"';
        }
        if ($activityid > 0) {
            if ($withcurrent == 1) {
                $where = $where . ' and weixin_user_prize.activityid=' . $activityid;
            }
            if ($withcurrent == 2) {
                $where = $where . ' and weixin_user_prize.activityid!=' . $activityid;
            }
        }
        $where = $where . ' order by wintime asc';
        $join = ' left join weixin_prizes on  weixin_prizes.id=weixin_user_prize.prizeid ';
        $select = 'weixin_user_prize.title,weixin_user_prize.plugname,weixin_user_prize.id,weixin_user_prize.prizeid,weixin_user_prize.activityid,userid,weixin_user_prize.status,weixin_user_prize.verifycode,weixin_user_prize.created_at,weixin_user_prize.wintime,weixin_user_prize.awardtime,weixin_prizes.prizename,weixin_prizes.prizedata';
        $data = $this->_user_prize_m->select($where, $select, '', 'assoc', $join);
        if (empty($data)) {
            return false;
        }
        foreach ($data as $k => $v) {
            $data[$k]['prizedata'] = $this->_prize_model->prizeNormal(unserialize($v['prizedata']));
        }
        return $data;
    }


    /**
     * 根据奖品id删除中奖
     * 不需要处理奖品剩余的问题
     *
     * @param integer $prizeid 奖品id
     *
     * @return array 返回删除的结果
     *
     */
    public function delUserPrizeByPrizeId($prizeid)
    {
        $where = 'prizeid=' . $prizeid;
        $result = $this->_user_prize_m->delete($where);
        return $result;
    }

    /**
     * 删除记录
     *
     * @param integer $id
     *
     * @return array 返回删除的结果
     *
     */
    public function deleteUserPrizeById($id)
    {
        return $this->delete($id);
    }

    /**
     * 根据用户id删除中奖记录
     */
    public function delUserPrizeByUserId($userid, $exceptplugname)
    {
        // $data
        $where = ' 1=1 and userid=' . $userid;
        if (count($exceptplugname) > 0) {
            $where .= ' and plugname not in ("' . implode(',', $exceptplugname) . '")';
        }
        $data = $this->_user_prize_m->select($where, 'id');
        if (count($data) <= 0) {
            return true;
        }
        foreach ($data as $k => $v) {
            $this->delete($v['id']);
        }
        return true;

    }

    /**
     * 删除活动
     * 删除活动的时候也会删除奖项 所以不用处理奖品剩余量的问题
     *
     * @param string $plugname 组件名称
     * @param integer $activityid 活动id
     *
     * @return array 返回删除的结果
     *
     */
    public function delUserPrizeByActivityId($plugname = '', $activityid = 0)
    {
        $where = ' 1=1 ';
        if ($plugname != '') {
            $where .= ' and plugname="' . $plugname . '" ';
            if ($activityid > 0) {
                $where .= ' and activityid=' . $activityid;
            }
        }
        $result = $this->_user_prize_m->delete($where);
        return $result;
    }

    /**
     * 删除全部中奖记录
     * 恢复被删除模块的所有奖品数量即可
     *
     * @param array $exceptplugname 除了哪些模块以外
     *
     * @return array 返回删除的结果
     */
    public function delUserPrize($exceptplugname = [])
    {
        $where = ' 1=1 ';
        if (count($exceptplugname) > 0) {
            $where .= ' and plugname not in ("' . implode(',', $exceptplugname) . '")';
        }
        $result = $this->_user_prize_m->delete($where);
        //恢复奖品数量
        $sql = 'update weixin_prizes set leftnum=num,freezenum=0 where 1=1  and plugname not in ("' . implode(',', $exceptplugname) . '")';
        $this->_user_prize_m->query($sql);
        if (CACHEMODE == 'Redis') {
            $this->_cache->del($this->_cacheprefix . $this->_winlockcachename);
        } else {
            $this->_cache->delete($this->_winlockcachename);
        }
        return $result;
    }

    public function resetPrizes($plugname, $activityid)
    {
        $select = 'prizeid,status,designated';

        $data = $this->_user_prize_m->select(' plugname="' . $plugname . '" and activityid=' . $activityid . ' and isdel=1 and (status=2 or status=3) ', $select);

        if (empty($data)) {
            return true;
        }
        
        $prizedata = [];
        foreach ($data as $v) {
            if (!isset($prizedata[$v['prizeid']])) {
                $prizedata[$v['prizeid']] = ['freezenum' => 0, 'leftnum' => 0];
            }
            if ($v['designated'] == 2) {
                $prizedata[$v['prizeid']]['freezenum']++;
            } else {
                $prizedata[$v['prizeid']]['leftnum']++;
            }
        }
        foreach ($prizedata as $k => $v) {
            $updatefeilds = [];
            if ($v['freezenum'] > 0) {
                $updatefeilds[] = 'freezenum=freezenum+' . $v['freezenum'];
            }
            if ($v['leftnum'] > 0) {
                $updatefeilds[] = 'leftnum=leftnum+' . $v['leftnum'];
            }
            $sql = 'update weixin_prizes set ' . join(',', $updatefeilds) . ' where id=' . $k;
            $result = $this->_user_prize_m->query($sql);
        }
        //删除非内定的中奖记录
        $sql = 'delete from weixin_user_prize where designated=1 and plugname="' . $plugname . '" and activityid=' . $activityid;
        $this->_user_prize_m->query($sql);
        $sql = 'update weixin_user_prize set status=1 where designated=2 and plugname="' . $plugname . '" and activityid=' . $activityid;
        $this->_user_prize_m->query($sql);
        //修改内定的中奖状态
        return true;
    }

    /**
     * 标记删除中奖记录
     *
     * @param int $id id
     *
     * @return bool 成功为true 失败是false
     */
    public function delete($id)
    {
        $user_prize = $this->_user_prize_m->find('id=' . $id, 'status,prizeid,designated');
        if (empty($user_prize)) {
            return false;
        }
        if ($user_prize['designated'] == 2) {
            //删除一个内定必中的人 已经中和还未中 
            //已经中奖的内定记录删除 需要增加一个剩余奖品数
            //未中奖的内定记录删除 需要减少一个冻结数 增加一个剩余奖品数
            if ($user_prize['status'] == 1 || $user_prize['status'] == 4) {
                $sql = 'update weixin_prizes set leftnum=leftnum+1,freezenum=freezenum-1 where id=' . $user_prize['prizeid'];
                $this->_user_prize_m->autocommit(false);
                $this->_user_prize_m->begin_transaction();
                $this->_user_prize_m->query($sql);
                $this->_user_prize_m->delete('id=' . $id);
                $this->_user_prize_m->commit();
                $this->_user_prize_m->autocommit(true);
                // $this->_user_prize_m->close();
                return true;
            } else {
                $sql = 'update weixin_prizes set leftnum=leftnum+1 where id=' . $user_prize['prizeid'];
                $this->_user_prize_m->autocommit(false);
                $this->_user_prize_m->begin_transaction();
                $this->_user_prize_m->query($sql);
                $this->_user_prize_m->delete('id=' . $id);
                $this->_user_prize_m->commit();
                // $this->_user_prize_m->close();
                $this->_user_prize_m->autocommit(true);
                return true;
            }
        } else {
            //删除一个其他人 已经中和还未中
            //已经中奖的增加一个奖品剩余数
            //没有中奖的直接删除
            if ($user_prize['status'] == 1 || $user_prize['status'] == 4) {
                $this->_user_prize_m->delete('id=' . $id);
                return true;
            } else {
                $sql = 'update weixin_prizes set leftnum=leftnum+1 where id=' . $user_prize['prizeid'];
                $this->_user_prize_m->autocommit(false);
                $this->_user_prize_m->begin_transaction();
                $this->_user_prize_m->query($sql);
                $this->_user_prize_m->delete('id=' . $id);
                $this->_user_prize_m->commit();
                $this->_user_prize_m->autocommit(true);
                // $this->_user_prize_m->close();
                return true;
            }
        }
    }

    /**
     * 删除活动id对应的中奖记录
     *
     * @Deprecated
     */
    public function deleteByPlugnameAndActivityId($plugname, $activityid)
    {
        return $this->_user_prize_m->delete(' 1 and plugname="' . $plugname . '" and activityid=' . $activityid);
    }

    /**
     * 取消用户的发奖状态
     *
     * @return bool true取消成功false 取消失败
     */
    public function cancelUserPrize($id)
    {
        $result = $this->_user_prize_m->update('id=' . $id, array('status' => 2, 'awardtime' => null));
        // echo var_export($result);
        return $result;
    }

    /**
     * 给用户发放奖品
     *
     * @return bool true取消成功false 取消失败
     */
    public function giveUserPrize($id)
    {
        $userprize=$this->getById($id);
        $prizeinfo=$this->_prize_model->getById($userprize['prizeid']);
        //普通奖品 红包奖品要分开   
        if($prizeinfo['type']==3){
            $flag_model=new Flag_model();
            $user=$flag_model->getUserinfoById($userprize['userid']);
            //发放红包
            $this->giveUserRedpacket($id,$user['openid'],$prizeinfo['prizedata_arr']['amount']);
            // if($result['code']<0){
            //     return false;
            // }
        }
        
        $result = $this->_user_prize_m->update('id=' . $id, array('status' => 3, 'awardtime' => time()));
        return $result;
    }

    public function winnerCount($plugname, $activityid)
    {
        $data = $this->_user_prize_m->find(' isdel=1 and (status=2 or status=3) and plugname="' . $plugname . '" and activityid=' . $activityid . ' ', '*', 'count');
        return $data;
    }
    //给用户红包
    public function giveUserRedpacket($userprizeid,$openid,$amount){
        $orderno=NewOrderNo();
        $prize_redpacket_m=new \M('prize_redpackets');
        $prize_repacket=['orderno'=>$orderno,'money'=>$amount,'userprizeid'=>$userprizeid,'openid'=>$openid,'status'=>1,'remark'=>''];
        $prize_repacket['created_at']=time();
        $prize_repacket['updated_at']=time();
        $result=$prize_redpacket_m->add($prize_repacket);
        return $this->sendUserRedpacket($prize_repacket);
    }
    public function sendUserRedpacket($prizeredpacket){
        $system_config_model=new System_Config_model();
        $appid=$system_config_model->get('deeja_appid');
        $appsecret=$system_config_model->get('deeja_appsecret');
        $appid=$appid['configvalue'];
        $appsecret=$appsecret['configvalue'];
        // echo $appid.','.$appsecret;
        $deejaapi=new DeejaSDK($appid,$appsecret);
        $notifyurl=request_scheme().'://'.$_SERVER['HTTP_HOST'].'/Modules/module.php?m=prize&c=front&a=ajaxRedpacketNotify';
        $sendingdata=[['openid'=>$prizeredpacket['openid'],'money'=>$prizeredpacket['money'],'orderno'=>$prizeredpacket['orderno']]];
        $result=$deejaapi->Redpacket($sendingdata,'奖品','恭喜发财，大吉大利',$notifyurl);
        if($result['code']<0){
            $this->updateUserRedpacket($prizeredpacket['orderno'],3,$result['messages']);
        }
        return $result;
    }
    //更新红包发放状态
    public function updateUserRedpacket($orderno,$status,$remark=''){
        $prize_redpacket_m=new \M('prize_redpackets');
        $prize_redpacket_m->update('orderno="'.$orderno.'"',['status'=>$status,'remark'=>$remark]);
    }
    public function getUserRedpacket($orderno){
        $prize_redpacket_m=new \M('prize_redpackets');
        $data=$prize_redpacket_m->find('orderno="'.$orderno.'"');
        return $data;
    }
    //
    public function restorePrize($plugname, $activityid)
    {
        $sql = 'delete from weixin_user_prize where plugname="' . $plugname . '" and activityid=' . $activityid;
        $this->_user_prize_m->query($sql);
        $sql = 'update weixin_prizes set leftnum=num,freezenum=0 where  plugname="' . $plugname . '" and activityid=' . $activityid;
        $this->_user_prize_m->query($sql);
    }

    /**
     * 根据用户id删除和这个用户相关的中奖及内定记录
     */
    public function deleteUserPrizeByUserid($plugname, $activityid, $userid)
    {
        $where = '1=1 and plugname="' . $plugname;
        if ($activityid > 0) {
            $where .= ' and activityid=' . $activityid;
        }
        if ($userid > 0) {
            $where .= ' and userid=' . $userid;
        }
        $data = $this->_user_prize_m->select($where, 'id');
        $returndata = true;
        foreach ($data as $v) {
            $returndata = $this->delete($v['id']);
            if (!$returndata) {
                break;
            }
        }
        return $returndata;
    }

    /**
     * 逻辑逻辑中奖用户名单，并且回复奖品数量等
     * @param $recodeId
     * @return bool
     */
    public function removeLotterRecode($recodeId)
    {
        $result = false;
        try {
            $info = $this->_user_prize_m->find(" id = {$recodeId} and isdel = 1 and status = 2 ", 'prizeid,status');
            if (empty($info)) {
                return false;
            }
            $this->_user_prize_m->autocommit(false);
            $this->_user_prize_m->begin_transaction();
            $updateResult = $this->_user_prize_m->update(" id = {$recodeId} and isdel = 1 and status = 2  ", ["isdel" => 2, 'status' => 4]);
            if (!$updateResult) {
                throw new Exception('删除逻辑中奖记录失败!');
            }
            $sql = 'update weixin_prizes set leftnum=leftnum+1 where id=' . $info['prizeid'];
            $updateResult = $this->_user_prize_m->executeUpdate($sql);
            if (!$updateResult) {
                throw new Exception('恢复奖品数量失败!');
            }
            $this->_user_prize_m->commit();
            $this->_user_prize_m->autocommit(true);
            $result = true;
        } catch (Exception $ex) {
            $this->_user_prize_m->rollback();
            $this->_user_prize_m->autocommit(true);
            LogUnit::writeLog($ex);
        }
        return $result;
    }

    /**
     * 全部清空抽奖记录
     * @param $activeId 活动ID
     * @return bool
     */
    public function lotteryReset($activeId, $prizeid)
    {
        $result = false;
        try {
            $info = $this->_user_prize_m->select(" activityid = {$activeId} and prizeid = {$prizeid} and isdel = 1 and status = 2 ", 'prizeid,status');
            if (empty($info)) {
                return false;
            }
            $lotteryPrize = [];
            foreach ($info as $key => $item) {
                if (isset($lotteryPrize[$item['prizeid']])) {
                    $lotteryPrize[$item['prizeid']]++;
                } else {
                    $lotteryPrize[$item['prizeid']] = 1;
                }
            }
            $this->_user_prize_m->autocommit(false);
            $this->_user_prize_m->begin_transaction();
            $updateResult = $this->_user_prize_m->update(" activityid = {$activeId} and prizeid = {$prizeid} and  isdel = 1 and status = 2  ", ["isdel" => 2, 'status' => 4]);
            if (!$updateResult) {
                throw new Exception('删除逻辑中奖记录失败!');
            }
            foreach ($lotteryPrize as $key => $item) {
                $sql = "update weixin_prizes set leftnum=leftnum+{$item} where id=" . $key;
                $updateResult = $this->_user_prize_m->executeUpdate($sql);
                if (!$updateResult) {
                    throw new Exception('恢复奖品数量失败!');
                }
            }
            $this->_user_prize_m->commit();
            $this->_user_prize_m->autocommit(true);
            $result = true;
        } catch (Exception $ex) {
            $this->_user_prize_m->rollback();
            $this->_user_prize_m->autocommit(true);
            LogUnit::writeLog($ex);
        }
        return $result;
    }
}