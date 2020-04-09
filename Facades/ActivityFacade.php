<?php
namespace Facades;
// require_once '..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'flag_model.php';
require_once '../Modules/Prize/Controllers/Api.php';
require_once '../Modules/Prize/Models/User_Prize_model.php';
require_once '../Modules/Prize/Models/Prizes_model.php';
require_once '../Modules/Ydj/Controllers/Api.php';
require_once '../Modules/Ydj/Models/Ydj_model.php';
require_once '../Modules/Choujiang/Controllers/Api.php';
require_once '../Modules/Choujiang/Models/Choujiang_model.php';
require_once '../Modules/Game/Models/Game_model.php';
use Modules\Prize\Controllers\Api;
use Modules\Ydj\Controllers\Api as YdjApi;
use Modules\Choujiang\Controllers\Api as ChoujiangApi;
use Modules\Game\Models\Game_model;
class ActivityFacade
{
    /**
     * 清空上墙数据
     *
     * @return void
     */
    public function reset()
    {
        $wall_config_m = new \M('wall_config');
        $load = \Loader::getInstance();
        //清空签到数据
        $load->model('Flag_model');
        $load->flag_model->clearUserInfo();

        //清空上墙消息
        $load->model('Wall_model');
        $load->wall_model->clearWallMessage();

        //清空投票票数
        $load->model('Vote_model');
        $load->vote_model->clearVoteData();

        //清空红包轮次数据
        $wall_config_m->query('truncate table weixin_redpacket_round');
        //清空红包中奖数据
        $wall_config_m->query('truncate table weixin_redpacket_users');
        //清空红包发送记录
        $wall_config_m->query('truncate table weixin_redpacket_orders');
        //清空红包接收记录
        $wall_config_m->query('truncate table weixin_redpacket_order_return');

        //清空幸运号码
        $wall_config_m->query('truncate table weixin_xingyunhaoma');
        //清空幸运手机号
        $wall_config_m->query('truncate table weixin_xingyunshoujihao');

        //清空倒入抽奖结果
        $prize_api = new Api();
        $prize_api->delUserPrize(['importlottery']);
        //清空摇大奖的数据
        $ydj_api = new YdjApi();
        $ydj_api->resetAllGames();

        //清空抽奖功能记录
        $cj_api = new ChoujiangApi();
        $cj_api->resetAllGames();
        //删除所有游戏的数据并恢复游戏状态
        $game_model=new Game_model();
        $game_model->clearAllData();
    }
    /**
     * 从活动中删除一个用户
     *
     * @param string $openid 用户的openid
     * @return void
     */
    public function deleteUser($openid){
        $load = \Loader::getInstance();
        //清空签到数据
        $load->model('Flag_model');
        $user=$load->flag_model->getUserinfo($openid);
       
        //删除手机端中奖记录
        //删除抽奖中奖记录
        $prize_api=new Api();
        $result=$prize_api->delUserPrizeByUserId($user['id'],['importlottery']);

        //删除红包信息
        $redpacket_users_m=new \M('redpacket_users');
        $redpacket_users_m->update('`userid`='.$user['id'],array('userid'=>null,'updated_at'=>null));
        $redpacket_orders_m=new \M('redpacket_orders');
        $redpacket_orders_m->delete('`re_openid`="'.$openid.'"');
        $redpacket_order_return_m=new \M('redpacket_order_return');
        $redpacket_order_return_m->delete('`re_openid`="'.$openid.'"');

        //删除上墙数据
        $load->model('Wall_model');
        $load->wall_model->deleteWallMessageByOpenid($openid);

        //删除投票数据
        $load->model('Vote_model');
        $load->vote_model->deleteRecordByOpenid($openid);
        //删除游戏记录
        // $flag_m=new \M('redpacket_users');
        $sql="delete from `weixin_game_records` where userid=".$user['id'];
        $result=$redpacket_users_m->query($sql);

        //删除幸运手机号
        $xingyunshoujihao_m=new \M('xingyunshoujihao');
        $xingyunshoujihao_m->delete('`openid`="'.$openid.'"');
        //删除签到信息
    
        $load->flag_model->deleteByUserid($user['id']);
        $load->flag_model->decrSignedCount();    
    }
}
