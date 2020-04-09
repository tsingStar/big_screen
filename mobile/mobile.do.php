<?php
/**
 * 手机端各个功能执行的入口
 * PHP version 5.4+
 * 
 * @category Mobile
 * 
 * @package Mobile
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
error_reporting(E_ALL);
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'db.class.php';
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'function.php';
//加载models
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'system_config_model.php';
$action = $_GET ['action'];
switch ($action) {
case 'user_register' :
    user_register();
    break;
case 'msg_getmore' :
    msg_getmore();
    break;
case 'msg_send' :
    send_msg();
    break;
case 'msg_uploadimg' :
    msg_uploadimg();
    break;
// case 'shake_count':
//     shake_count();
//     break;
case 'vote_insert':
    vote_insert();
    break;
}
/**
 * 获取更多上墙消息记录
 * 
 * @return void 
 */
function msg_getmore() 
{
    include_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'biaoqing.php';
    include_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'emoji'.DIRECTORY_SEPARATOR.'emoji.php';
    $load=Loader::getInstance();
    $openid = $_POST ['openid'];
    $load->model('Flag_model');
    $myinfo =$load->flag_model->getUserinfo($openid);

    if ($myinfo['status']==2) {
        $data = array (
                'message' => '您已经被屏蔽，无法继续参与活动',
                'errno' => -3
        );
        echo trim(json_encode($data));
        return;
    }
    $load->model('Wall_model');
    $messagelist = $load->wall_model->getHistoryByOpenid($openid);

    foreach ( $messagelist as $k => $message ) {
        $message ['nickname'] = emoji_unified_to_html(emoji_softbank_to_unified(pack('H*', $message ['nickname'])));
        $message ['content'] = emoji_unified_to_html(emoji_softbank_to_unified(pack('H*', $message ['content'])));
        // $message = $message;
        $message ['content'] = biaoqing($message ['content']);
        
        $message ['type'] = 1;
        if (!empty($message ['image'])) {
            $message ['type'] = 2;
            $message ['content'] = $message ['image'];
        }
        $message ['createtime'] = $message ['datetime'];
        $messagelist [$k] = $message;
    }
    $msg = array (
            'message' => $messagelist,
            'errno' => 0 
    );
    echo trim(json_encode($msg));
    return;
}

// todo:自动审核关键字处理
// todo:手动审核的默认审核状态
function send_msg() 
{
    $load=Loader::getInstance();
    $openid = $_POST ['openid'];
    $content = htmlentities($_POST ['content'], ENT_NOQUOTES, "utf-8");
    $load->model('Flag_model');
    $myinfo = $load->flag_model->getUserinfo($openid);

    if ($myinfo['status']==2) {
        $data = array (
                'message' => '您已经被屏蔽，无法继续参与活动',
                'errno' => -3
        );
        echo trim(json_encode($data));
        return;
    }

    $load->model('Wall_model');
    $wall_config =$load->wall_model->getConfig();

    $mylastmsg=$load->wall_model->getLastMessage($openid);
    if (!empty($mylastmsg) && time()-$mylastmsg['datetime']<$wall_config['timeinterval']) {
        $data = array (
                'message' => '你发送消息的速度太快了',
                'errno' => -2
        );
        echo trim(json_encode($data));
        return false;
    }

    include_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'emo_helper.php';

    $content = Emo::ProcessEmoMsg($content);
    $ret = 1;
    if ($wall_config ['shenghe'] == 1) {
        $ret = 0;
    } else {
        if (blackword($content, $wall_config['black_word']) == 1) {
            $ret = 0;
        }
    }
    $maxshenheorder=0;
    if ($ret==1) {
        $maxshenheorder=time();
    }

    $message = array (
            'content' => $content,
            'nickname' => bin2hex($myinfo['nickname']),
            'avatar' => $myinfo['avatar'],
            'ret' => $ret,
            'fromtype' => 'weixin',
            'image' => 0,
            'datetime' => time(),
            'openid' => $openid ,
            'shenhetime'=>$maxshenheorder,
    );
    $messageadd = $message;
    $messageadd ['content'] = bin2hex($messageadd ['content']);
    $load->wall_model->addMessage($messageadd);
    $message ['tip'] =$ret==1?'发送成功':'发送成功，请等待管理员审核';
    $data = array (
            'message' => $message,
            'errno' => 0 
    );
    echo trim(json_encode($data));
}
function msg_uploadimg() 
{
    $load=Loader::getInstance();
    $openid = $_POST ['msg_send'];
    $load->model('Flag_model');
    $myinfo = $load->flag_model->getUserinfo($openid);
    if ($myinfo['status']==2) {
        $data = array (
                'message' => '您已经被屏蔽，无法继续参与活动',
                'errno' => -3
        );
        echo trim(json_encode($data));
        return;
    }

    $load->model('Wall_model');
    $wall_config =$load->wall_model->getConfig();

    $mylastmsg=$load->wall_model->getLastMessage($openid);
    if (!empty($mylastmsg) && time()-$mylastmsg['datetime']<$wall_config['timeinterval']) {
        $data = array (
                'message' => '你发送消息的速度太快了',
                'errno' => -2
        );
        echo trim(json_encode($data));
        return false;
    }
    $base64=$_POST['imgbase64'];
    $extension=$_POST['filetype'];
    include_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'http_helper.php';
    include_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'weixin_helper.php';
    include_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'FileUploadFactory.php';
    $fuf = new FileUploadFactory(SAVEFILEMODE);
    $base64= str_replace('data:image/jpg;base64,', '', $base64);
    $base64= str_replace('data:image/jpeg;base64,', '', $base64);
    $base64= str_replace('data:image/png;base64,', '', $base64);
    $file = base64_decode($base64);
    $attachement_data=$fuf->SaveFile($file, $extension);
    $ret = $wall_config ['shenghe'] == 1 ? 0 : 1;
    $returndata = array (
            'errno' => 0,
            'message' => array (
                    "picurl" => $attachement_data['filepath'],
                    "tip" =>($ret==1?'发送成功':'发送成功，请等待管理员审核') 
            ) 
    );
    $maxshenheorder=0;
    if ($ret==1) {
        $maxshenheorder=time();
    }
    // 记录信息到数据库
    $data = array(
            'content' =>'此消息为图片！',
            'nickname' =>bin2hex($myinfo['nickname']),
            'avatar' =>$myinfo['avatar'],
            'ret' => $ret,
            'fromtype' => 'weixin',
            'image' => $attachement_data['id'],
            'datetime' => time(),
            'openid' => $openid ,
            'shenhetime'=>$maxshenheorder
    );

    $return = $load->wall_model->addMessage($data);// $wall_m->add ( $data );
    if ($return == false) {
        $returndata = array (
                'errno' => - 1,
                'message' => array (
                        "picurl" => $attachement_data['filepath'],
                        "tip" => "信息保存失败！" 
                ) 
        );
    }
    echo json_encode($returndata);
}

function user_register() 
{
    $load=Loader::getInstance();
    $openid = $_POST ['openid'];
    $redirect=isset($_POST['redirecturl'])?$_POST['redirecturl']:'';
    $signname='';
    $phone = '';
    //检查是否已经签到
    $load->model('Flag_model');
    $userinfo=$load->flag_model->getUserinfo($openid);

    if ($userinfo && $userinfo['flag']==2) {
        $returndata=array('errno'=>0,'message'=>"签到完成");
        if (!empty($redirect)) {
            $returndata['redirecturl']=$redirect;
        }
        echo json_encode($returndata);
        return ;
    }

    $load->model('Wall_model');
    $wall_config=$load->wall_model->getConfig();
    //检查签到人数是否已经满了
    $flag_count=$load->flag_model->getSignedCount();
    $maxplayers=intval($wall_config['maxplayers']);
    if ($maxplayers>0) {
        if ($flag_count>=$maxplayers) {
            $returndata=array('errno'=>-5,'message'=>"活动人数已经满了");
            echo json_encode($returndata);
            return ;
        }
    }

    $load->model('System_Config_model');
    //签到填写姓名
    $data=$load->system_config_model->get('qiandaosignname');
    $qiandaosignname=intval($data['configvalue']);
    if ($qiandaosignname==1) {
        $signname = isset($_POST ['realname']) ? $_POST ['realname'] : '';
        $signname =htmlentities($signname, ENT_NOQUOTES, "utf-8");
        if ($signname=='') {
            echo '{"errno":-2,"message":"姓名必须填写"}';
            return ;
        }
    }
    //签到填写手机号
    $data=$load->system_config_model->get('qiandaophone');
    $qiandaophone=intval($data['configvalue']);
    if ($qiandaophone==1) {
        $phone = isset($_POST ['mobile'])?$_POST['mobile'] : '';
        
        if ($phone=='') {
            echo '{"errno":-3,"message":"手机号必须填写"}';
            return ;
        }
        if (! preg_match("/^1[0-9]{1}\d{9}$/", $phone)) {
            $phone = '';
        }
        if ($phone=='') {
            echo '{"errno":-4,"message":"手机号格式不正确"}';
            return ;
        }
    }
    //处理自定义字段
    $columns=$load->flag_model->getExtentionColumns();
    if (!empty($columns)) {
        $extentioncolumns=array();
        foreach ($columns as $key => $value) {
            $params['column_'.$value['id']]=isset($_POST['column_'.$value['id']])?strval($_POST['column_'.$value['id']]):'';
            if ($value['ismust']==2 && empty($params['column_'.$value['id']])) {
                $returndata=array('errno'=>-6,'message'=>$value['title'].'必须填写');
                echo json_encode($returndata);
                return ;
            }
            $extentioncolumns['column_'.$value['id']]['id']=$value['id'];
            $extentioncolumns['column_'.$value['id']]['title']=$value['title'];
            $extentioncolumns['column_'.$value['id']]['val']=$params['column_'.$value['id']];
        }

        if (!empty($extentioncolumns)) {
            $serialize_column_data=serialize($extentioncolumns);
            $extentioncolumndata=array('userid'=>$userinfo['id'],'datastr'=>$serialize_column_data);
            $result=$load->flag_model->saveExtentionData($extentioncolumndata);
        }
    }
    

    //是否需要手动审核
    $data=$load->system_config_model->get('qiandaoshenhe');
    $qiandaoshenhe=intval($data['configvalue']);

    //签到用户信息
    $qiandaouserinfo=array();
    if ($qiandaoshenhe==1) {//自动审核
        $qiandaouserinfo['status']=1;
        //signorder审核顺序也记录一下
        $signorder=$load->flag_model->getMaxSignorder();
        $qiandaouserinfo['signorder']=$signorder+1;
    } else {//手动审核
        $qiandaouserinfo['status']=2;
    }
    $qiandaouserinfo['flag']=2;
    $qiandaouserinfo['phone']=$phone;
    $qiandaouserinfo['signname']=$signname;
    $qiandaouserinfo['openid']=$openid;
    //保存签到的用户信息
    //记录签到人数
    $load->flag_model->incrSignedCount();
    $result=$load->flag_model->saveUserinfo($qiandaouserinfo);
    $flagconfig=$load->flag_model->getConfig();
    // if ($flagconfig['reserved_infomation_csv_attachmentid']>0) {
    if ($flagconfig['reserved_infomation_verify']==1) {
        //通过,如果是手动审核,那么在导入名单的人被设置成通过审核
        if ($qiandaoshenhe==2) {
            $newuserinfo=$load->flag_model->getUserinfo($openid, true);
            if (!empty($newuserinfo['verify_realname']) || !empty($newuserinfo['verify_phone'])) {
                $signorder=$load->flag_model->getMaxSignorder();
                $updateuserinfo=array('openid'=>$openid,'status'=>1);
                $updateuserinfo['signorder']=$signorder+1;
                $load->flag_model->saveUserinfo($updateuserinfo);
            }
        }
    } else {
        //不通过，如果是自动审核,那么在导入名单的人被设置成待审核
        if ($qiandaoshenhe==1) {
            $newuserinfo=$load->flag_model->getUserinfo($openid,  true);
            if (!empty($newuserinfo['verify_realname']) || !empty($newuserinfo['verify_phone'])) {
                $updateuserinfo=array('openid'=>$openid,'status'=>2);
                $load->flag_model->saveUserinfo($updateuserinfo);
            }
        }
    }
    // }

    if ($result) {
        $returndata=array('errno'=>0,'message'=>"签到完成");
        if (!empty($redirect)) {
            $returndata['redirecturl']=$redirect;
        }
        echo json_encode($returndata);
        return ;
    } else {
        $returndata=array('errno'=>-1,'message'=>"签到信息记录失败");
        echo json_encode($returndata);
        return ;
    }
}
//摇一摇
// function shake_count()
// {
//     include_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'CacheFactory.php';
//     $openid = $_POST['openid'];
//     $load=Loader::getInstance();
//     $load->model('Flag_model');
//     $myinfo=$load->flag_model->getUserinfo($openid);
//     if ($myinfo['status']==2) {
//         $data = array (
//                 'message' => '您还未通过审核，无法参与活动',
//                 'status' => -3
//         );
//         echo trim(json_encode($data));
//         return;
//     }
//     hasmysql($openid);
// }

//投票
function vote_insert()
{
    $load=Loader::getInstance();
    $openid = $_POST ['openid'];
    $voteconfigid=isset($_POST['voteid'])?intval($_POST['voteid']):0;
    $voteitems_arr= isset($_POST['data']) ? $_POST ['data'] : '';

    $load->model('Flag_model');
    $myinfo = $load->flag_model->getUserinfo($openid);

    if ($myinfo['status']==2) {
        $data = array (
                'message' => '您还未通过审核，无法参与活动',
                'errno' => -3
        );
        echo trim(json_encode($data));
        return;
    }
    $load->model('Vote_model');
    $vote_config=$load->vote_model->getCurrentVoteConfig($voteconfigid);

    $vote_record_m=new M('vote_record');
    $vote_record=$vote_record_m->find('voteconfigid='.$voteconfigid.' and openid="'.$openid.'"');
    if ($vote_record) {
        echo '{"errno":-2,"message":"您已经投过票了！"}';
        return;
    }

    // $vote_config_m=new M('vote_config');
    // $vote_config=$vote_config_m->find('id='.$voteconfigid);

    // 不是大屏幕上显示的投票项
    if ($vote_config['currentshow']==2) {
        echo '{"errno":-5,"message":"这个主题目前没有在进行投票！"}';
        return;
    }
    //投票主题的状态
    if ($vote_config['status']==2) {
        echo '{"errno":-3,"message":"本轮投票已经结束！"}';
        return;
    }

    $votenum=count($voteitems_arr);
    //最大投票数
    if ($vote_config['votemode']==1 && $votenum>$vote_config['votenum']) {
        echo '{"errno":-6,"message":"你没有选择项目或者选择的项目太多了！"}';
        return ;
    }
    //固定投票数
    if ($vote_config['votemode']==2 && $votenum!=$vote_config['votenum']) {
        echo '{"errno":-7,"message":"你必须选择'.$vote_config['votenum'].'项！"}';
        return ;
    }
    //最小投票数
    if ($vote_config['votemode']==3 && $votenum<$vote_config['votenum']) {
        echo '{"errno":-8,"message":"你没有选择项目或者选择的项目太少了！"}';
        return ;
    }

    $vote_items_m=new M('vote_items');

    $datasql='';
    foreach ($voteitems_arr as $v) {
        $datasql.=$datasql==''?'':',';
        $datasql.='(null,'.$voteconfigid.',"'.$openid.'",'.time().','.time().','.$v.')';
        $succed=$vote_items_m->update("`id`=".$v, "`votecount` =  `votecount`+1");
    }
    if (empty($datasql)) {
        echo '{"errno":-4,"message":"数据格式错误"}';
        return ;
    }
    $querysql='insert into weixin_vote_record values '.$datasql;
    $vote_record_m->query($querysql);
    echo '{"errno":0,"message":"投票成功！"}';
    return ;
}

//judge=1 获取分数信息
//judge=2 获取人数
//judge=3 开始
//judge=4 结束
// function hasmemcache($mem,$openid)
// {
//     $wall_state_key='wall_state';
//     // echo $mem->get($wall_state_key);
//     $wall_state=$mem->get($wall_state_key);
//     if (!$wall_state) {
//         echo json_encode(
//             array(
//                 'status' => 5,
//                 'point' => 0
//             )
//         );
//         $mem->quit();
//         return;
//     }
//     if ($wall_state['isopen']==2) {
//         $prefix = 'xianchang_';
//         $data = $mem->get($prefix . $openid);
//         if (!$data) {//没有签到
//             echo json_encode(
//                 array(
//                     'status' => 4,
//                     'point' => 0
//                 )
//             );
//         } else if ($data && $data['point']+1>$wall_state['endshake']) {
//             //活动结束
//             $wall_state['isopen']=1;
//             $data['point']=$wall_state['endshake'];
//             $mem->set($wall_state_key, $wall_state);
//             $mem->set($prefix.$openid, $data, 3600);
//             echo json_encode($data);
//         } else {
//             //增加一分
//             $data['point']+=1;
//             $mem->set($prefix.$openid, $data, 3600);
//             echo json_encode($data);
//         }
//     } else {
//         echo json_encode(
//             array(
//                 'status' => 3,
//                 'point' => 0
//             )
//         );
//     }
//     $mem->quit();
// }

// function hasmysql($openid)
// {
//     $fzz=new CacheFactory(CACHEMODE);
//     $cachename='shake_status';
//     $status=$fzz->get($cachename);
//     //缓存不存在说明活动数据出错，可以联系主持人重置游戏，或者开始游戏解决
//     if (!$status) {
//         //提示活动不存在或者没有开始
//         echo json_encode(
//             array(
//                 'status' => 3,
//                 'point' => 0,
//                 'message'=>"活动不存在"
//             )
//         );
//         return;
//     }

//     //1表示未开始2表示开始3表示人满4表示结束
//     if ($status['status']==1) {
//         echo json_encode(
//             array(
//                 'status' => 3,
//                 'point' => 0,
//                 'message'=>"活动没有开始"
//             )
//         );
//         return;
//     }

//     if ($status['status']==4) {
//         echo json_encode(
//             array(
//                 'status' => 3,
//                 'point' => 0,
//                 'message'=>"活动已经结束"
//             )
//         );
//         return;
//     }
//     $shake_toshake_m = new M('shake_toshake');
//     $where='`openid`="' . $openid . '" and roundno='.$status['roundno'].' order by point desc limit 1';
//     $data = $shake_toshake_m->find($where, 'id,point');
//     if (!$data) {
//         //人数满的情况
//         if ($status['status']==3) {
//             echo json_encode(
//                 array(
//                     'status' => 6,
//                     'started_at'=>$status['started_at'],
//                     'duration'=>$status['duration'],
//                     'point' => 0,
//                     'message'=>"参与人数已经满了，下次动作快一点哦。"
//                 )
//             );
//             return;
//         }
//         if ($status['started_at']+$status['duration']<time()) {
//             echo json_encode(
//                 array(
//                     'status' => 3,
//                     'point' => 0,
//                     'message'=>"活动已经结束"
//                 )
//             );
//             return;
//         }
//         $load=Loader::getInstance();
//         $load->model('Flag_model');
//         $myinfo=$load->flag_model->getUserinfo($openid);

//         $data=array(
//                 'nickname'=>$myinfo['nickname'],
//                 'openid'=>$openid,
//                 'point'=>1,
//                 'avatar'=>$myinfo['avatar'],
//                 'roundno'=>$status['roundno']
//         );
//         $result=$shake_toshake_m->add($data);
//         return;
//     }
//     //已经正常参与活动的人，活动也在进行中
//     //活动时间到
//     if ($status['started_at']+$status['duration']<time()) {
//         echo json_encode(
//             array(
//                 'status' => 3,
//                 'point' => $data['point'],
//                 'message'=>"活动已经结束"
//             )
//         );
//         return;
//     }
//     $point=intval($data['point'])+1;
//     $where='id='.$data['id'];
//     $shake_toshake_m->update($where, 'point='.$point);
//     echo json_encode(
//         array(
//             'status' => 2,
//             'point' => $point
//         )
//     );
//     return;
// }