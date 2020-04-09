<?php
require_once '../common/db.class.php';
$action = $_GET['action'];
switch ($action) {
    case 'redpacket_start':
        redpacket_start();
        break;
    case 'redpacket_result':
        redpacket_result();
        break;
    case 'redpacket_open':
        redpacket_open();
        break;
    
}

//打开红包
function redpacket_open()
{
    $roundid = isset($_POST['roundid']) ? intval($_POST['roundid']) : 0;
    $openid = isset($_POST['openid']) ? strval($_POST['openid']) : '';
    if ($roundid == 0) {
        $data = array('errno' => -7, "message" => '数据异常，无法获取活动数据');
        echo json_encode($data);
        return;
    }
    if ($openid == '') {
        $data = array('errno' => -8, "message" => '数据异常，无法获取活动数据');
        echo json_encode($data);
        return;
    }
    $load = Loader::getInstance();
    $load->model('Redpacket_model');
    $redpacket_round = $load->redpacket_model->getRoundById($roundid);
    if ($redpacket_round['status'] == 3) {
        $data = array('errno' => -1, "message" => '本轮红包活动已经结束');
        echo json_encode($data);
        return;
    }
    $redpacket_round['chance'] = $redpacket_round['chance'] < 1 ? 0 : $redpacket_round['chance'];
    $redpacket_round['chance'] = $redpacket_round['chance'] > 1000 ? 1000 : $redpacket_round['chance'];
 
    if (rand(1, 1000) > $redpacket_round['chance']) {
        $data = array('errno' => -6, "message" => '没有中奖');
        echo json_encode($data);
        return;
    }

    $load->model('Flag_model');
    $flag = $load->flag_model->getUserinfo($openid);
    $redpacket_user_count = $load->redpacket_model->getRedpackettimes($roundid, $flag['id']);
    if (intval($redpacket_user_count) >= intval($redpacket_round['numperperson'])) {
        $data = array('errno' => -4, "message" => '您没有中奖');
        echo json_encode($data);
        return;
    }

    $result = $load->redpacket_model->setWinner($roundid, $flag['id']);
    if (!$result) {
        $data = array('errno' => -6, "message" => '没有中奖');
    } else {
        $data = array('errno' => 1, "message" => '您中奖了', "data" => array('money' => $result['amount'] / 100, 'zzs' => ''));
    }
    echo json_encode($data);
    return;
}
//游戏结果
function redpacket_result()
{
    $openid = isset($_POST['openid']) ? strval($_POST['openid']) : '';
    if ($openid == '') {
        $data = array('errno' => -8, "message" => '数据异常，无法获取活动数据');
        echo json_encode($data);
        return;
    }
    $load = Loader::getInstance();
    $load->model('Flag_model');
    $flag = $load->flag_model->getUserinfo($openid);
    $load->model('Redpacket_model');
    $redpacket_users = $load->redpacket_model->getRedpacketsByUserid($flag['id']);
    if ($redpacket_users) {
        $redpacket_users = processzjlist($redpacket_users);
        $data = array('errno' => 1, "message" => '中奖数据', "data" => $redpacket_users);
        echo json_encode($data);
        return;
    } else {
        $data = array('errno' => -1, "message" => '暂时无人中奖');
        echo json_encode($data);
        return;
    }
}
//开始游戏
function redpacket_start()
{
    $roundid = isset($_POST['roundid']) ? intval($_POST['roundid']) : 0;
    if ($roundid == 0) {
        $data = array('errno' => -3, "message" => '目前没有进行中的游戏，请按主持人提示操作。');
        echo json_encode($data);
        return;
    }
    $load = Loader::getInstance();
    $load->model('Redpacket_model');
    $redpacket_round = $load->redpacket_model->getRoundById($roundid);
    if ($redpacket_round['status'] == 3) {
        $data = array('errno' => -2, "message" => '活动已经结束');
        echo json_encode($data);
        return;
    }
    if ($redpacket_round['status'] == 1) {
        $data = array('errno' => -1, "message" => '游戏还没开始,请等待!');
        echo json_encode($data);
        return;
    }
    $lefttime = time() - $redpacket_round['started_at'];
    $lefttime = $redpacket_round['lefttime'] - $lefttime;
    $lefttime = $lefttime <= 0 ? 0 : $lefttime;
    $data = array('errno' => 1, "message" => '游戏中', "lefttime" => $lefttime);
    echo json_encode($data);
    return;
}

//合计一个人的中奖金额和红包数量
function processzjlist($redpacket_users)
{
    $newredpacket_users = array('num' => 0, 'money' => 0);
    foreach ($redpacket_users as $k => $v) {
        $newredpacket_users['num']++;
        $newredpacket_users['money'] += ($v['amount'] / 100);
    }
    return $newredpacket_users;
}
