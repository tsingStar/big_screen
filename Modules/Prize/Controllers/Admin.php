<?php
/**
 * 奖品模块后台页面
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

require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Adminbase.php';
require_once BASEPATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'CacheFactory.php';
use \Modules\Prize\Models\Prizes_model;
use \Modules\Prize\Models\User_Prize_model;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use library\deejasdk\DeejaSDK;
/**
 * 奖品模块后台页面
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
class Admin extends Adminbase
{
    /**
     * 构造函数
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 游戏轮次设置
     *
     * @return void
     */
    public function index()
    {
        $plugname = isset($_GET['plugname']) ? strval($_GET['plugname']) : 'ydj';
        $activityid = isset($_GET['activityid']) ? intval($_GET['activityid']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $pagesize = 15;
        $this->setTitle('奖品列表');
        $this->setDescription('奖品列表');
        $prizes_model = new Prizes_model();
        $prizes = $prizes_model->getPagedData($plugname, $activityid, $page, $pagesize);
        $url = '/Modules/module.php?m=prize&c=admin&a=index&plugname=' . $plugname . '&activityid=' . $activityid;
        $this->assign('plugname', $plugname);
        $this->assign('activityid', $activityid);
        $this->assign('prizes', $prizes);
        $this->assign('pagehtml', $this->pagerhtml($page, $pagesize, $prizes['count'], $url));
        $this->show("index.html");
    }
    /**
     * 中奖列表
     *
     * @return void
     */
    public function zjlist()
    {
        $this->setTitle('获奖名单');
        $this->setDescription('获奖名单');
        $plugname = isset($_GET['plugname']) ? strval($_GET['plugname']) : 'ydj';
        $activityid = isset($_GET['activityid']) ? intval($_GET['activityid']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
        $page = $page <= 0 ? 1 : $page;
        $user_prize_model = new User_Prize_model();
        $pagesize = 30;
        $data = $user_prize_model->getPagedData($plugname, $activityid, $page, $pagesize);
        $url = '/Modules/module.php?m=prize&c=admin&a=zjlist&plugname=' . $plugname . '&activityid=' . $activityid;
        $this->assign('plugname', $plugname);
        $this->assign('activityid', $activityid);
        $this->assign('data', $data['data']);
        $this->assign('pagehtml', $this->pagerhtml($page, $pagesize, $data['count'], $url));
        $this->show("zjlist.html");
    }
    /**
     * 内定名单
     *
     * @return void
     */
    public function designatedlist()
    {
        $this->setTitle('内定名单');
        $this->setDescription('内定名单和设置内定');
        $plugname = isset($_GET['plugname']) ? strval($_GET['plugname']) : 'ydj';
        $activityid = isset($_GET['activityid']) ? intval($_GET['activityid']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $user_prize_model = new User_Prize_model();
        $data = $user_prize_model->getDesignatedPagedData($plugname, $activityid, 1, 20);
        // echo var_export($data);
        $pagesize = 30;
        $prizes_model = new Prizes_model();
        $prizes = $prizes_model->getAvailablePrize($plugname, $activityid);
        $url = '/Modules/module.php?m=prize&c=admin&a=designatedlist&plugname=' . $plugname . '&activityid=' . $activityid;
        $this->assign('plugname', $plugname);
        $this->assign('activityid', $activityid);
        $this->assign('prizes', $prizes);
        $this->assign('data', $data['data']);
        // echo var_export($data);
        $this->assign('pagehtml', $this->pagerhtml($page, $pagesize, $data['count'], $url));
        $this->show("designatedlist.html");
    }

    /**
     * 搜索人员名单
     *
     * @return void
     */
    public function ajax_act_get_users()
    {
        $searchtext = isset($_GET['searchtext']) ? strval($_GET['searchtext']) : '';
        if (empty($searchtext)) {
            $returndata = array('code' => -1, 'message' => "必须输入内容才能搜索哦");
            echo json_encode($returndata);
            return;
        }
        $this->_load->model('Flag_model');
        $searchwhere = ' 1 and (nickname like "%' . bin2hex($searchtext) . '%" or signname like "%' . $searchtext . '%" or phone like "%' . $searchtext . '%") ';
        $where = $searchwhere . ' and status=1 and flag=2 order by id desc';
        $maxrow = 20;
        $result = $this->_load->flag_model->getPagedData($where, 1, $maxrow, false);
        $message = '';
        if ($result['count'] > 20) {
            $message = "找到超过" . $maxrow . "条数据,条件可以再精确一点哦";

        }
        foreach ($result['data'] as $k => $v) {
            $result['data'][$k] = $this->_formatuserinfo($v);
        }
        $returndata = array('code' => 1, 'message' => $message, "data" => $result['data']);
        echo json_encode($returndata);
        return;
    }
    /**
     * 格式化用户信息的数据
     *
     * @param array $item 一条用户信息数据
     *
     * @return void
     */
    private function _formatuserinfo($item)
    {
        if (isset($item['nickname'])) {
            $item['nickname'] = pack('H*', $item['nickname']);
        }
        return $item;
    }
    /**
     * 取消内定
     *
     * @return void
     */
    public function ajax_act_cancel_designated()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $returndata = array('code' => -1, 'message' => "数据不存在");
            echo json_encode($returndata);
            return;
        }

        $user_prize_model = new User_Prize_model();
        $result = $user_prize_model->cancelDesignated($id);

        if ($result) {
            $returndata = array('code' => 1, 'message' => "修改成功");
            echo json_encode($returndata);
            return;
        }
        $returndata = array('code' => -2, 'message' => "修改失败");
        echo json_encode($returndata);
        return;
    }

    /**
     * 设置内定
     *
     * @return void
     */
    public function ajax_act_save_designated()
    {
        $prizeid = isset($_GET['prizeid']) ? intval($_GET['prizeid']) : 0;
        $userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;
        $designated = isset($_GET['designated']) ? intval($_GET['designated']) : 0;
        $activityid = isset($_GET['activityid']) ? intval($_GET['activityid']) : 0;
        $plugname = isset($_GET['plugname']) ? strval($_GET['plugname']) : '';
        $title = isset($_GET['title']) ? strval($_GET['title']) : '';
        if ($prizeid <= 0 || $userid <= 0 || $plugname == '') {
            $returndata = array('code' => -1, 'message' => "数据错误");
            echo json_encode($returndata);
            return;
        }
        $data = array(
            'prizeid' => $prizeid,
            'userid' => $userid,
            'designated' => $designated,
            'activityid' => $activityid,
            'plugname' => $plugname,
            'title' => $title,
        );
        // $this->_load->model('User_Prize_model');
        $user_prize_model = new User_Prize_model();
        $result = $user_prize_model->setDesignated($data);
        if ($result) {
            $returndata = array('code' => 1, 'message' => "设置成功");
            echo json_encode($returndata);
            return;
        } else {
            $returndata = array('code' => -2, 'message' => "设置失败，请检查这个人是否已经设置过内定了，或者奖品已经没有剩余了");
            echo json_encode($returndata);
            return;
        }
    }

    /**
     * 删除奖品
     *
     * @return void
     */
    public function ajax_act_delete_prize()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $returndata = array('code' => -1, 'message' => "数据不存在");
            echo json_encode($returndata);
            return;
        }
        $prizes_model = new Prizes_model();
        //删除奖品
        $result = $prizes_model->delete('', 0, $id);
        //中奖记录也标记一下 删除
        $user_prize_model = new User_Prize_model();
        $user_prize_model->delUserPrizeByPrizeId($id);

        if ($result) {
            $returndata = array('code' => 1, 'message' => "删除成功");
            echo json_encode($returndata);
            return;
        } else {
            $returndata = array('code' => -1, 'message' => "删除失败");
            echo json_encode($returndata);
            return;
        }

    }
    /**
     * 获取奖品信息
     *
     * @return void
     */
    public function ajax_act_get_prize()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $returndata = array();
        if ($id <= 0) {
            $returndata = array('code' => -1, 'message' => "数据不存在");
        } else {
            $prizes_model = new Prizes_model();
            $prize = $prizes_model->getById($id);
            if (!empty($prize)) {
                $prize['rate'] = number_format($prize['rate'] / 10000, 4);
                $returndata = array('code' => 1, 'message' => "获得奖品数据", "data" => $prize);
            } else {
                $returndata = array('code' => -1, 'message' => "数据不存在");
            }
        }
        echo json_encode($returndata);
        return;

    }
    /**
     * 保存轮次配置信息
     *
     * @return void
     */
    public function ajax_act_save_prize()
    {
        $prize = array();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id > 0) {
            $prize['id'] = $id;
        }
        $prize['prizename'] = isset($_POST['prizename']) ? strval($_POST['prizename']) : '';
        if ($prize['prizename'] == '') {
            $returndata = array('code' => -1, 'message' => "奖品名称必须填写");
            echo json_encode($returndata);
            return;
        }
        //剩余数量
        $prize['leftnum'] = isset($_POST['num']) ? intval($_POST['num']) : 0;
        if ($prize['leftnum'] < 0) {
            $returndata = array('code' => -2, 'message' => "奖品剩余数量不能是负数");
            echo json_encode($returndata);
            return;
        }
        $prize['plugname'] = isset($_POST['plugname']) ? strval($_POST['plugname']) : '';
        if ($prize['plugname'] == '') {
            $returndata = array('code' => -3, 'message' => "数据来源不正确");
            echo json_encode($returndata);
            return;
        }

        $prize['activityid'] = isset($_POST['activityid']) ? intval($_POST['activityid']) : 0;
        $prize['rate'] = is_numeric($_POST['rate']) ? $_POST['rate'] * 10000 : 1000000;
        $prize['type'] = isset($_POST['type']) ? intval($_POST['type']) : 1; // 1;//isset($_POST['num'])?intval($_POST['num']):0;
        $prizedata = [];
        if ($prize['type'] == 1 || $prize['type'] == 3) {
            $imageid = isset($_POST['imageid']) ? intval($_POST['imageid']) : 0;
            $prizedata['imageid'] = $imageid;
            if ($prizedata['imageid'] <= 0) {
                if (!empty($_FILES['imagepath']['type'])) {
                    //上传的文件
                    $this->_load->model('Attachment_model');
                    $file = $this->_load->attachment_model->saveFormFile($_FILES['imagepath']);
                    $prizedata['imageid'] = $file['id'];
                }
            }
        }
        if ($prize['type'] == 3) {
            $amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;
            $amount = $amount * 100;
            if ($amount < 100) {
                $returndata = array('code' => -5, 'message' => "红包金额不能少于1元");
                echo json_encode($returndata);
                return;
            }
            $prizedata['amount'] = $amount;
        }
        $prize['prizedata'] = $prizedata;
        if (count($prizedata) <= 0) {
            unset($prize['prizedata']);
        }
        $prizes_model = new Prizes_model();
        if ($prize['id'] > 0) {
            $prizeinfo = $prizes_model->getById($id);
            $deltaleft = $prize['leftnum'] - $prizeinfo['leftnum'];
            $prize['num'] = $prizeinfo['num'] + $deltaleft;
        } else {
            $prize['num'] = $prize['leftnum'];
        }

        $result = $prizes_model->save($prize);
        if (!$result) {
            $returndata = array('code' => -4, 'message' => "保存失败");
            echo json_encode($returndata);
            return;
        }
        $returndata = array('code' => 1, 'message' => "保存成功");
        echo json_encode($returndata);
        return;
    }
    /**
     * 删除用户的中奖记录
     *
     * @return void
     */
    public function ajax_act_delete_userprize()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $returndata = array('code' => -1, "message" => "删除失败");
            echo json_encode($returndata);
            return;
        }
        $user_prize_model = new User_Prize_model();
        $return = $user_prize_model->deleteUserPrizeById($id);
        if ($return) {
            $returndata = array('code' => 1, "message" => "删除成功");
            echo json_encode($returndata);
            return;
        } else {
            $returndata = array('code' => -2, "message" => "删除失败");
            echo json_encode($returndata);
            return;
        }
    }
    /**
     * 发奖
     *
     * @return void
     */
    public function ajax_act_give_userprize()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $returndata = array('code' => -1, "message" => "发放失败");
            echo json_encode($returndata);
            return;
        }
        $user_prize_model = new User_Prize_model();
        $return = $user_prize_model->giveUserPrize($id);
        if ($return) {
            $returndata = array('code' => 1, "message" => "发放成功");
            echo json_encode($returndata);
            return;
        } else {
            $returndata = array('code' => -2, "message" => "发放失败");
            echo json_encode($returndata);
            return;
        }
    }
    /**
     * 取消用户的发奖状态
     *
     * @return void
     */
    public function ajax_act_cancel_userprize()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id <= 0) {
            $returndata = array('code' => -1, "message" => "取消失败");
            echo json_encode($returndata);
            return;
        }
        $user_prize_model = new User_Prize_model();
        $return = $user_prize_model->cancelUserPrize($id);
        if ($return) {
            $returndata = array('code' => 1, "message" => "取消成功");
            echo json_encode($returndata);
            return;
        } else {
            $returndata = array('code' => -2, "message" => "取消失败");
            echo json_encode($returndata);
            return;
        }
    }
    /**
     * 清空中奖记录
     *
     * @return void
     */
    public function ajax_act_cleardata()
    {
        $plugname = isset($_GET['plugname']) ? strval($_GET['plugname']) : 'ydj';
        $activityid = isset($_GET['activityid']) ? intval($_GET['activityid']) : 0;
        $user_prize_model = new User_Prize_model();
        $return = $user_prize_model->resetPrizes($plugname, $activityid);
        $returndata = ['code' => -1, 'message' => '中奖记录清空失败'];
        if ($return) {
            $returndata = ['code' => 1, 'message' => '中奖记录已经清空'];
        }
        echo json_encode($returndata);
        return;
    }
    public function exportdata()
    {
        $plugname = isset($_GET['plugname']) ? strval($_GET['plugname']) : '';
        if ($plugname == '') {
            return;
        }
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getProperties()->setCreator("金华迪加网络科技有限公司")
            ->setLastModifiedBy("金华迪加网络科技有限公司")
            ->setTitle("Office 2007 XLSX 现场活动大屏幕系统中奖结果")
            ->setSubject("Office 2007 XLSX 现场活动大屏幕系统中奖结果")
            ->setDescription("现场活动大屏幕系统中奖结果.")
            ->setKeywords("现场活动大屏幕系统中奖结果")
            ->setCategory("金华迪加网络科技有限公司程序导出文件");
        $user_prize_model = new User_Prize_model();
        $data = $user_prize_model->getResultData($plugname, 0);
        $prizes_model = new Prizes_model();
        $prizes = $prizes_model->getAllPrize($plugname, -1);

        $sheetindex = 0;
        $oldactivityid = 0;
        $currentactivityid = 0;
        $rownum = 2;
        $activesheet = null;
        foreach ($data as $row) {
            $currentactivityid = $row['activityid'];
            if ($currentactivityid != $oldactivityid) {
                $oldactivityid = $currentactivityid;
                $activesheet = $this->_createsheet($sheetindex, $oldactivityid, $objPHPExcel);
                $sheetindex++;
                $rownum = 2;
            }
            $this->_writedata($row, $rownum, $prizes, $activesheet);
            $rownum++;

        }

        // exit();
        $title = '现场活动大屏幕系统中奖结果';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        return;

    }

    private function _createsheet($index, $activityid, $excelobj)
    {
        if ($index > 0) {
            $excelobj->createSheet();
        }
        $activesheet = $excelobj->setActiveSheetIndex($index);
        $activesheet->setCellValue('A1', '昵称');
        $activesheet->setCellValue('B1', '姓名');
        $activesheet->setCellValue('C1', '手机号');
        $activesheet->setCellValue('D1', '奖品名称');
        $activesheet->setCellValue('E1', '状态');
        $activesheet->setCellValue('F1', '兑奖码');
        $activesheet->setCellValue('G1', '获奖时间');
        $activesheet->setCellValue('H1', '发奖时间');
        $title = '第' . $activityid . '轮游戏中奖结果';
        $excelobj->getActiveSheet()->setTitle($title);
        return $activesheet;
    }

    private function _writedata($row, $rownum, $prizes, $activesheet)
    {
        $statustext = ['', '', '未发', '已发'];
        // echo var_export($prizes[$row['prizeid']]['prizename']);
        $activesheet->setCellValue('A' . $rownum, pack('H*', $row['nickname']));
        $activesheet->setCellValue('B' . $rownum, $row['signname']);
        $activesheet->setCellValue('C' . $rownum, $row['phone']);
        $activesheet->setCellValue('D' . $rownum, $prizes[$row['prizeid']]['prizename']);
        $activesheet->setCellValue('E' . $rownum, $statustext[$row['status']]);
        $activesheet->setCellValue('F' . $rownum, $row['verifycode']);
        $activesheet->setCellValue('G' . $rownum, date('Y-m-d H:i:s', $row['wintime']));
        $activesheet->setCellValue('H' . $rownum, empty($row['awardtime']) ? '' : date('Y-m-d H:i:s', $row['awardtime']));
    }

    /**
     * 格式化配置数据
     *
     * @param array $configs 未格式的话数据
     *
     * @return array 格式化后的数据
     */
    private function _formatconfigdata($configs)
    {
        $showconfigs = array();
        $statustext_arr = array('', "未开始", "进行中", "结束");
        $winningagaintext_arr = array('', "不能重复获奖", "可重复获奖");
        $shakeshowstyletext_arr = array('', '昵称', '姓名', '手机号');
        foreach ($configs as $k => $v) {
            $showconfigs[$k] = $v;
            $showconfigs[$k]['statustext'] = $statustext_arr[$v['status']];
            $showconfigs[$k]['winningagaintext']
            = $winningagaintext_arr[$v['winningagain']];
            $showconfigs[$k]['showstyletext']
            = $shakeshowstyletext_arr[$v['showstyle']];
        }
        return $showconfigs;
    }
    public function ajaxGetSendRedpackets()
    {

        $userprizeid = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($userprizeid <= 0) {
            $returndata = array('code' => -1, "message" => "数据格式错误");
            echo json_encode($returndata);
            return;
        }
        $user_prize_model = new User_Prize_model();
        $data = $user_prize_model->getPrizeRedpackets($userprizeid);
        foreach ($data as $k => $v) {
            $data[$k] = $this->_formatdata($v);
        }
        if ($data) {
            $returndata = array('code' => 1, "message" => "数据格式错误", 'data' => $data);
            echo json_encode($returndata);
            return;
        }
    }
    public function ajaxCheckRedpacket()
    {
        $orderno = isset($_GET['orderno']) ? strval($_GET['orderno']) : '';
        if (empty($orderno)) {
            $returndata = array('code' => -1, "message" => "数据格式错误");
            echo json_encode($returndata);
            return;
        }
        $this->_load->model('System_Config_model');
        $appid = $this->_load->system_config_model->get('deeja_appid');
        $appsecret = $this->_load->system_config_model->get('deeja_appsecret');
        $appid = $appid['configvalue'];
        $appsecret = $appsecret['configvalue'];
        $deejaapi = new DeejaSDK($appid, $appsecret);

        $data = $deejaapi->CheckRedpacket($orderno);
        if(!$data){
            $returndata = array('code' => -2, 'message' => '查询失败');
            echo json_encode($returndata);
            return;
        }
        $user_prize_model=new User_Prize_model();
        if ($data['code'] > 0) {
            $returnorder = $data['order'];
            if ($returnorder['status'] == 2) {
                $statustext = '发放完成';
                $user_prize_model->updateUserRedpacket($orderno,2);
            }
            if ($returnorder['status'] == 3) {
                $statustext = '发放失败';
                $user_prize_model->updateUserRedpacket($orderno,3,$statustext);
            }
            if ($returnorder['status'] == 4) {
                $statustext = '发放中，请耐心等待';
                $user_prize_model->updateUserRedpacket($orderno,4,$statustext);
            }

            $returndata = array('code' => 1, 'message' => $statustext);
            echo json_encode($returndata);
            return;
        } else {
            $returndata = array('code' => -1, 'message' => '查询失败');
            echo json_encode($returndata);
            return;
        }
    }
    
    public function ajaxResendRedpacket()
    {
        $orderno = isset($_GET['orderno']) ? strval($_GET['orderno']) : '';
        if (empty($orderno)) {
            $returndata = array('code' => -1, "message" => "数据格式错误");
            echo json_encode($returndata);
            return;
        }
        $user_prize_model=new User_Prize_model();
        $orderinfo=$user_prize_model->getUserRedpacket($orderno);
        $returndata=$user_prize_model->giveUserRedpacket($orderinfo['userprizeid'],$orderinfo['openid'],$orderinfo['money']);
        $returndata = array('code' => 1, 'message' => '已经申请重发，主要不要重复点击，避免重复发放');
        echo json_encode($returndata);
        return;

    }

    private function _formatdata($row)
    {
        $row['created_at'] = date('Y-m-d H:i:s', $row['created_at']);
        $statustext_arr = ['', '开始发放', '发放完成', '发放失败', '等待微信处理中'];
        $row['status'] = $statustext_arr[$row['status']];
        return $row;
    }
}
