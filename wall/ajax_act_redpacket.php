<?php
ini_set("display_errors", "On");
error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../common/db.class.php';
require_once dirname(__FILE__) . '/../common/function.php';
require_once dirname(__FILE__) . '/../common/http_helper.php';
// require_once(dirname(__FILE__) . '/../common/function.php');
require_once dirname(__FILE__) . '/../library/deejasdk/DeejaSDK.php';
use library\deejasdk\DeejaSDK;

$action = $_GET['action'];

switch ($action) {
    case 'start':
        gamestart();
        break;
    case 'end':
        gameover();
        break;
    case 'redpacket_activity_screen_record':
        redpacket_activity_screen_record();
        break;
    case 'redpacket_users':
        redpacket_users();
        break;
    case 'redpacke_zjlist':
        redpacke_zjlist();
        break;
    case 'sendingredpacket':
        ajax_act_sending_redpacket();
        break;
    case 'redpacket_notify':
        redpacket_notify();
        break;
}
//开始游戏
function gamestart()
{
    $roundid = isset($_POST['roundid']) ? intval($_POST['roundid']) : 0;
    $load = Loader::getInstance();
    $load->model('Redpacket_model');
    $redpacket_round = $load->redpacket_model->getRoundById($roundid);
    if (empty($redpacket_round)) {
        $data = array('errno' => -1, "message" => '没有可以进行的活动了');
        echo json_encode($data);
        return;
    }

    if ($redpacket_round['status'] == 1) {
        //活动未开始
        $newdata = array('id' => $roundid, 'started_at' => time(), 'status' => 2);
        $result = $load->redpacket_model->updateRound($newdata);
        $data = array('errno' => 1, "message" => "活动未开始", "lefttime" => $redpacket_round['lefttime']);
        echo json_encode($data);
        return;
    }
    if ($redpacket_round['status'] == 2) {
        //活动进行中
        $lefttime = time() - $redpacket_round['started_at'];
        $lefttime = $redpacket_round['lefttime'] - $lefttime;
        $lefttime = $lefttime <= 0 ? 0 : $lefttime;
        $data = array('errno' => 2, "message" => "活动进行中", "lefttime" => $lefttime);
        echo json_encode($data);
        return;
    }
    if ($redpacket_round['status'] == 3) {
        //活动已经结束
        $data = array('errno' => -2, "message" => '本轮活动已经结束');
        echo json_encode($data);
        return;
    }
}
//游戏结束
function gameover()
{
    $roundid = isset($_POST['roundid']) ? intval($_POST['roundid']) : 0;
    $load = Loader::getInstance();
    $load->model('Redpacket_model');
    $redpacket_round = $load->redpacket_model->getRoundById($roundid);
    if (empty($redpacket_round)) {
        $data = array('errno' => -1, "message" => '没有可以进行的活动了');
        echo json_encode($data);
        return;
    }
    if ($redpacket_round['status'] == 1) {
        $data = array('errno' => -2, "message" => '活动还未开始，不能结束');
        echo json_encode($data);
        return;
    }
    if ($redpacket_round['status'] == 2) {
        //活动进行中
        //检查时间
        $lefttime = time() - $redpacket_round['started_at'];
        if ($lefttime >= $redpacket_round['lefttime']) {
            $newdata = array('id' => $roundid, 'status' => 3);
            $result = $load->redpacket_model->updateRound($newdata);
            $data = array('errno' => 1, "message" => '活动结束');
            echo json_encode($data);
            return;
        } else {
            $data = array('errno' => -3, "message" => '活动时间没到，不能结束');
            echo json_encode($data);
            return;
        }
    }
}

//本轮活动的中奖名单
function redpacket_activity_screen_record()
{
    $maxid = isset($_POST['max_record_id']) ? intval($_POST['max_record_id']) : 0;
    $roundid = isset($_POST['roundid']) ? intval($_POST['roundid']) : 0;
    $load = Loader::getInstance();
    $load->model('Redpacket_model');
    $redpacket_users = $load->redpacket_model->getWinner($roundid, $maxid);
    $redpacket_users = processzjlist($redpacket_users);
    if ($redpacket_users) {
        $data = array('errno' => 1, "message" => '中奖记录', 'data' => $redpacket_users);
        echo json_encode($data);
        return;
    } else {
        $data = array('errno' => -1, "message" => '暂时没有中奖记录');
        echo json_encode($data);
        return;
    }
}

//修改为本轮中奖名单
function redpacke_zjlist()
{
    $roundid = isset($_POST['roundid']) ? intval($_POST['roundid']) : 0;
    if ($roundid <= 0) {
        $data = array('errno' => -1, "message" => '活动信息错误');
        echo json_encode($data);
        return;
    }
    $load = Loader::getInstance();
    $load->model('Redpacket_model');
    $redpacket_users = $load->redpacket_model->getWinners($roundid);
    $redpacket_users = processzjlist($redpacket_users);
    if ($redpacket_users) {
        $data = array('errno' => 1, "message" => '中奖记录', 'data' => $redpacket_users);
        echo json_encode($data);
        return;
    } else {
        $data = array('errno' => -1, "message" => '暂时没有中奖记录');
        echo json_encode($data);
        return;
    }
}

//获取参与用户列表
function redpacket_users()
{
    $maxuserid = isset($_POST['maxuserid']) ? intval($_POST['maxuserid']) : 0;
    $load = Loader::getInstance();
    $load->model('Flag_model');
    //获取最后签到的6个人
    $flags = $load->flag_model->getRecentSignedUsers(6, $maxuserid);
    $redpacket_users = processuserlist($flags);
    if ($redpacket_users) {
        $data = array('errno' => 1, "message" => '参数记录', 'data' => $redpacket_users);
        echo json_encode($data);
        return;
    } else {
        $data = array('errno' => -1, "message" => '暂时没有签到记录');
        echo json_encode($data);
        return;
    }
}

//处理中奖名单
function processzjlist($redpacket_users)
{
    $newredpacket_users = array();
    foreach ($redpacket_users as $k => $v) {
        $row = array();
        $row['id'] = $v['id'];
        $row['avatar'] = $v['avatar'];
        $row['nick_name'] = pack('H*', $v['nickname']);
        $row['money'] = $v['amount'] / 100;
        $newredpacket_users[] = $row;
    }
    return $newredpacket_users;
}

//处理参与用户名单
function processuserlist($redpacket_users)
{
    $newredpacket_users = array();
    foreach ($redpacket_users as $k => $v) {
        $row = array();
        $row['id'] = $v['signorder'];
        $row['avatar'] = $v['avatar'];
        $row['nick_name'] = pack('H*', $v['nickname']);
        $newredpacket_users[] = $row;
    }
    return $newredpacket_users;
}

//发送红包
function ajax_act_sending_redpacket()
{
    $roundid = isset($_POST['roundid']) ? intval($_POST['roundid']) : 0;
    if ($roundid <= 0) {
        $resultdata = array('errno' => -1, "message" => '轮次信息错误');
        echo json_encode($resultdata);
        return;
    }
    $redis=null;
    if (CACHEMODE=="Redis") {
        $redis=new \Predis\Client(
            array(
                'scheme' => 'tcp',
                'host'   => REDIS_HOST,
                'port'   => REDIS_PORT,
                'password'=> REDIS_PASSWORD
            )
        );
        $cacheprefix=CACHEPREFIX;
        $cachename='redpacket_'.$roundid;
        if(!$redis->setnx($cacheprefix.$cachename,time())){
            $time=$redis->get($cacheprefix.$cachename);
            if($time+30<time()){
                $redis->set($cacheprefix.$cachename,time());
            }else{
                exit();
            }
            
        }
    }

    $load = Loader::getInstance();
    $load->model('Redpacket_model');
    $load->model('Wall_model');
    $wall_config=$load->wall_model->getConfig();
    //获取当前轮次中已经被人抢到，但是没有发放的红包
    $redpacket_users = $load->redpacket_model->getRedpacketUserinfoByStatus(1, $roundid);
    if(empty($redpacket_users)){
        $resultdata = array('errno' => -2, "message" => '没有未发的中奖记录');
        echo json_encode($resultdata);
        return;
    }
    $redpacket_config = $load->redpacket_model->getRedpacketConfig();
    $sendname = $redpacket_config['sendname'];
	$wishing = $redpacket_config['wishing'];
    //如果是对接了自己的公众号 使用官方发放接口
    if($wall_config['rentweixin']==2){
        $returndata=DeejaSendRedpackets($redpacket_users,$sendname,$wishing,$roundid);
        $resultdata =array('errno' => -1, "message" => '红包发放失败，请到后台查看原因，并安排重新发放');
        if($returndata['code']>0){
            $resultdata = array('errno' => 1, "message" => '红包发放中，请耐心等待');
        }
        echo json_encode($resultdata);
        return;
    }else{
        $this->OfficalSendRedpackets($redpacket_users,$sendname,$wishing,$roundid);
        $resultdata=array('errno'=>1,"message"=>'红包发放完成');
        echo json_encode($resultdata);
        return;
    }
}
//使用迪加红包接口发放红包，回调页面
function redpacket_notify(){
    $data=$_POST;
    $load = Loader::getInstance();
    $load->model('Redpacket_model');
    $load->model('Flag_model');
    // $data=json_decode('{"code":1,"message":"","data":[{"orderno":"3beffe828bb349a58b4c53da4bc3e4c1","result_code":"SUCCESS"}]}',true);
    $data['code']=isset($data['code'])?intval($data['code']):0;
    // $log_m=new M('log');
    // $log_m->add(['echostr'=>json_encode($data)]);
    if($data['code']>0){
        if($data['data']){
            foreach($data['data'] as $item){
                if($item['result_code']=="SUCCESS"){
                    //修改订单状态
                    $order=['orderno'=>$item['orderno'],'status'=>2,'remark'=>''];
                    $load->redpacket_model->updateDeejaOrder($order);
                    $order=$load->redpacket_model->getDeejaOrder($item['orderno']);
                    $user=$load->flag_model->getUserinfo($order['openid']);
                    //修改红包发放状态
                    $load->redpacket_model->setRedpacketSendingStatusByOrderno($item['orderno'], 3);
                }else{
                    if($item['err_code']=='PROCESSING' || $item['err_code']=='SYSTEMERROR'){
                        $item['err_code_des']=unicodeDecode($item['err_code_des']);
                        //修改订单状态
                        $order=['orderno'=>$item['orderno'],'status'=>4,'remark'=>$item['err_code_des']];
                        $load->redpacket_model->updateDeejaOrder($order);
                        $order=$load->redpacket_model->getDeejaOrder($item['orderno']);
                        $user=$load->flag_model->getUserinfo($order['openid']);
                        //修改红包发放状态
                        $load->redpacket_model->setRedpacketSendingStatusByOrderno($item['orderno'],5);
                    }else{
                        $item['err_code_des']=unicodeDecode($item['err_code_des']);
                        //修改订单状态
                        $order=['orderno'=>$item['orderno'],'status'=>3,'remark'=>$item['err_code_des']];
                        $load->redpacket_model->updateDeejaOrder($order);
                        $order=$load->redpacket_model->getDeejaOrder($item['orderno']);
                        $user=$load->flag_model->getUserinfo($order['openid']);
                        //修改红包发放状态
                        $load->redpacket_model->setRedpacketSendingStatusByOrderno($item['orderno'],4);
                    }
                }
            }
        }
        echo "OK";
    }else{
        echo "FAIL";
    }
}
function unicodeDecode($unicode_str){
    $unicode_str = str_replace('"', '\"', $unicode_str);
    $unicode_str = str_replace("'", "\'", $unicode_str);
    $unicode_str=str_replace('u','\u',$unicode_str);
    $json = '{"str":"'.$unicode_str.'"}';
    $arr = json_decode($json,true);
    if(empty($arr)){
        return '';
    }
    return $arr['str'];
}
//使用自己的公众号发放红包
function OfficalSendRedpackets($redpackets,$sendname,$wishing,$roundid){
	$load = Loader::getInstance();
    $load->model('Redpacket_model');
	foreach ($redpackets as $user) {
        $sendingstatus = $load->redpacket_model->getRedpacketSendingStatusByUserid($user['userid'], $roundid);
        //如果已经发放完毕就跳过
        if ($sendingstatus >= 2) {
            continue;
        }
        //修改红包发放状态，标记为正在发送中
        $load->redpacket_model->setRedpacketSendingStatusByUserid($user['userid'], 2, $roundid);
        //发放红包
        $returndata = $load->redpacket_model->sendingRedpacket($user['openid'], $user['totalmoney'], $sendname, $wishing);
        $redpacket_order_return_data = array(
            'return_code' => $returndata['return_code'],
            'return_msg' => $returndata['return_msg'],
            'result_code' => $returndata['result_code'],
            'err_code' => $returndata['err_code'],
            'err_code_des' => $returndata['err_code_des'],
            'mch_billno' => $returndata['mch_billno'],
            'mch_id' => $returndata['mch_id'],
            'wxappid' => $returndata['wxappid'],
            're_openid' => $returndata['re_openid'],
            'total_amount' => $returndata['total_amount'],
            'send_listid' => isset($returndata['send_listid']) ? $returndata['send_listid'] : '',
        );
        $load->redpacket_model->addRedpacketSendingOrderReturn($redpacket_order_return_data);
        if ($returndata['return_code'] == 'SUCCESS' && $returndata['return_msg'] = '发放成功') {
            $result = $load->redpacket_model->setRedpacketSendingStatusByUserid($user['userid'], 3, $roundid);
        } else {
            $result = $load->redpacket_model->setRedpacketSendingStatusByUserid($user['userid'], 4, $roundid);
        }
    }
}
//使用迪加红包发放接口发放红包
function DeejaSendRedpackets($redpackets,$sendname,$wishing,$roundid){
	$load = Loader::getInstance();
	$load->model('Redpacket_model');
	$load->model('System_Config_model');
	$appid=$load->system_config_model->get('deeja_appid');
	$appsecret=$load->system_config_model->get('deeja_appsecret');
	$appid=$appid['configvalue'];
	$appsecret=$appsecret['configvalue'];
	$deejaapi=new DeejaSDK($appid,$appsecret);
	$sendingdata=[];
	foreach ($redpackets as $user) {
		$orderno=NewOrderNo();
        $dataitem=['openid'=>$user['openid'],'money'=>$user['totalmoney'],'orderno'=>$orderno];
        $load->redpacket_model->addDeejaOrder($dataitem);
        $load->redpacket_model->updateRedpacketUserMchBillno($roundid,$user['userid'],$orderno,1);
        unset($dataitem['roundid']);
		$sendingdata[]=$dataitem;
	}
    $notifyurl='https://'.$_SERVER['HTTP_HOST'].'/wall/ajax_act_redpacket.php?action=redpacket_notify';
    $result=$deejaapi->Redpacket($sendingdata,$sendname,$wishing,$notifyurl);
    return $result;
}
