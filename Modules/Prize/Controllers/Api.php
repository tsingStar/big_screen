<?php

namespace Modules\Prize\Controllers;
use \Modules\Prize\Models\Prizes_model;
use \Modules\Prize\Models\User_Prize_model;

class Api{
    public function __construct(){
    }

    //发奖
    public function givePrize()
    {

    }
    /**
     * 重置游戏，清空中奖记录
     * 根据组件名称和活动编号取消所有中奖记录
     * 
     * @param string $plugname 组件名称
     * @param int $activityid 活动编号
     * 
     * @return bool true 成功 false 失败
     * 
     */
    public function resetprizes($plugname,$activityid){
        $activityid=intval($activityid);
        if(empty($plugname) || $activityid<=0){
            return false;
        }
        $user_prize_model=new User_Prize_model();
        return $user_prize_model->resetPrizes($plugname,$activityid);
    }
    /**
     * 按照获奖记录的id取消获奖记录
     * 
     * @param int $id 中奖记录id
     * 
     * @return bool true 成功 false失败
     */
    public function cancelprizebyid($id){

    }
    
    //中奖
    public function winprize($plugname,$activityid,$userid,$title=''){
        if(empty($plugname) || $activityid<=0 || $userid<=0){
            $returndata=array('code'=>-1,'message'=>'数据格式错误');
            return $returndata;
        }
        $user_prize_model=new User_Prize_model();
        $prize=$user_prize_model->winPrize($plugname,$activityid,$userid,$title);
        
        $prize=$prize?$prize:null;
        $returndata=array('code'=>1,"message"=>"获取数据成功","data"=>array('prize'=>$prize));
        return $returndata;
    }
    //批量中奖
    public function winprizebatch($plugname,$activityid,$userids,$prizeid,$title=''){
        if(empty($plugname) || $activityid<=0 || count($userids)<=0){
            $returndata=array('code'=>-1,'message'=>'数据格式错误');
            return $returndata;
        }   
        $user_prize_model=new User_Prize_model();
        $result=$user_prize_model->winPrizeBatch($plugname,$activityid,$userids,$prizeid,$title);
        if($result){
            $returndata=['code'=>1,'message'=>'成功','data'=>[]];
            return $returndata;
        }else{
            $returndata=['code'=>-1,'message'=>'失败成功','data'=>[]];
            return $returndata;
        }
       
    }
    /**
     * 获取内定列表
     */
    public function getdesignatedlist($plugname,$activityid){
        if(empty($plugname) || $activityid<=0 ){
            $returndata=array('code'=>-1,'message'=>'数据格式错误');
            return $returndata;
        }
        $user_prize_model=new User_Prize_model();
        $data=$user_prize_model->getDesignated($plugname,$activityid);
        return $data;
    }
    /**
     * 获取奖品数量
     * total 总数 freeze 冻结数量 left 剩余数量
     * {"total":10,"freeze":1,"left":3}
     * 
     * @return void
     */
    public function getprizesnum($plugname,$activityid)
    {
        if($plugname==''){
            $returndata=array('code'=>-1,'message'=>'数据参数错误');
            echo json_encode($returndata);
            return;
        }
        $prizes_model=new Prizes_model();
        $data=$prizes_model->countPrizesNum($plugname,$activityid);
        $returndata=array('code'=>1,'message'=>'获取数据成功','data'=>$data);
        return $returndata;
    }
    /**
     * 获取奖品库存，包含剩余和已经被获奖的信息
     * 
     * @return void
     */
    public function getprizes($plugname,$activityid){
        if($plugname==''){
            $returndata=array('code'=>-1,'message'=>'数据参数错误');
            return $returndata;
        }
        $prizes_model=new Prizes_model();
        $data=$prizes_model->getAllPrize($plugname,$activityid);
        if(!$data){
            $returndata=array('code'=>-1,'message'=>'没有找到奖品数据');
            return $returndata;
        }
        $returndata=array('code'=>1,'message'=>'','data'=>$data);
        return $returndata;
    }
    /**
     * 获取所有中奖信息
     * 
     * @param string $plugname 组件名
     * @param int $activityid 活动编号
     * @param bool $withuserinfo 是否包含用户表信息 true包含用户信息 false不包含用户信息
     * 
     * @return array 所有中奖信息
     */
    public function getwinners($plugname,$activityid=0,$withuserinfo=true){
        if($plugname==''){
            $returndata=array('code'=>-1,'message'=>'数据参数错误');
            return json_encode($returndata);
        }
        $activityid=isset($activityid)?intval($activityid):0;
        $user_prize_model=new User_Prize_model();
        $userprizelist=$user_prize_model->getAllData($plugname,$activityid,$withuserinfo);
        $returndata=array('code'=>1,'message'=>'获取数据成功','data'=>$userprizelist);
        return $returndata;
    }
    //通过id获取中奖用户名单
    public function getWinnersByPrizeId($plugname,$activityid,$prizeid){
        if($plugname==''){
            $returndata=array('code'=>-1,'message'=>'数据参数错误');
            return json_encode($returndata);
        }
        $activityid=isset($activityid)?intval($activityid):0;
        $user_prize_model=new User_Prize_model();
        $userprizelist=$user_prize_model->getWinnersByPrizeId($plugname,$activityid,$prizeid);
        $returndata=array('code'=>1,'message'=>'获取数据成功','data'=>$userprizelist);
        return $returndata;
    }
    /**
     * 获取中奖的人数
     * 
     * @param string $plugname 组件名
     * @param int $activityid 活动编号
     * 
     * @return array 获奖人数
     */
    public function getwinnercount($plugname,$activityid){
        if($plugname==''){
            $returndata=array('code'=>-1,'message'=>'数据参数错误');
            return json_encode($returndata);
        }
        $activityid=isset($activityid)?intval($activityid):0;
        $user_prize_model=new User_Prize_model();
        $data=$user_prize_model->winnerCount($plugname,$activityid);
        $returndata=array('code'=>1,'message'=>'获取数据成功','data'=>$data);
        return $returndata;
    }
    /**
     * 获取我的中奖信息，同类游戏
     */
    public function getmyprizes($userid,$plugname=''){
        $userid=intval($userid);
        if($userid<=0){
            return false;
        }
        $user_prize_model=new User_Prize_model();
        return $user_prize_model->getMyPrizes($userid,$plugname);
    }
    /**
     * 获取我的中奖信息，一轮游戏
     */
    public function getmyprizeshistory($userid,$plugname='',$activityid=0){
        $userid=intval($userid);
        if($userid<=0){
            return false;
        }
        $user_prize_model=new User_Prize_model();
        return $user_prize_model->getMyPrizes($userid,$plugname,$activityid,2);
    }
    /**
     * 删除包含了 4种可能的方式
     * 1.删除签到记录或者导入的信息时 删除所有中奖记录相关的记录
     * 2.删除奖品时                删除相关的中奖记录
     * 3.删除活动时                删除所有奖品和相关的中奖记录及内定
     * 4.删除中奖记录
     * 5.删除全部
     */
    /**
     * 删除签到记录或者导入的信息时 删除所有中奖记录相关的记录
     * 
     * @param integer $userid         用户id
     * @param array  $exceptplugname  除了哪些模块以外
     * 
     * @return array 返回删除的结果
     * 
     */
    public function delUserPrizeByUserId($userid,$exceptplugname){
        $user_prize_model=new User_Prize_model();
        $returndata=$user_prize_model->delUserPrizeByUserId($userid,$exceptplugname);
        $returndata=array('code'=> -1,'message'=>'删除失败');
        if($returndata){
            $returndata=array('code'=> 1,'message'=>'删除成功');
        }
        return $returndata;
    }
    
    /**
     * 删除奖品
     * 
     * @param integer $prizeid 奖品id
     * 
     * @return array 返回删除的结果
     * 
     */
    public function delUserPrizeByPrizeId($prizeid){

    }
    /**
     * 删除活动
     * 
     * @param string $plugname 组件名称
     * @param integer $activityid 活动id
     * 
     * @return array 返回删除的结果
     * 
     */
    public function delUserPrizeByActivityId($plugname,$activityid){
        $prizes_model=new Prizes_model();
        // echo $plugname,$activityid;
        $prizes_model->delete($plugname,$activityid);
        $user_prize_model=new User_Prize_model();
        $user_prize_model->delUserPrizeByActivityId($plugname,$activityid);
        $returndata=array('code'=>1,'message'=>'删除完成');
        return $returndata;
    }
    /**
     * 删除记录
     * 
     * @param integer $id
     * 
     * @return array 返回删除的结果
     * 
     */
    public function delUserPrizeById($id){

    }
    /**
     * 删除全部中奖记录
     * 
     * @param array  $exceptplugname  除了哪些模块以外
     * 
     * @return array 返回删除的结果
     */
    public function delUserPrize($exceptplugname){
        $user_prize_model=new User_Prize_model();
        $returndata=$user_prize_model->delUserPrize($exceptplugname);
        $returndata=array('code'=>1,'message'=>'删除完成');
        return $returndata;
    }

    //恢复模块对应轮次的奖品数量
    public function restoreprize($plugname,$activityid){
        $user_prize_model=new User_Prize_model();
        $user_prize_model->restorePrize($plugname,$activityid);
    }

    //删除奖项信息
    public function deleteprizes($plugname,$activityid){
        $prizes_model=new Prizes_model();
        // echo $plugname,$activityid;
        $prizes_model->deleteByPlugnameAndActivityId($plugname,$activityid);
        $user_prize_model=new User_Prize_model();
        $user_prize_model->deleteByPlugnameAndActivityId($plugname,$activityid);
        $returndata=array('code'=>1,'message'=>'删除完成');
        return $returndata;
    }
    //通过id删除奖项信息
    public function deletePrize($id){
        $prizes_model=new Prizes_model();
        $result=$prizes_model->delete('',0,$id);
        //删除奖项时也要删除对应的中奖记录
        $user_prize_model=new User_Prize_model();
        $user_prize_model->delUserPrizeByPrizeId($id);
        if($result){
            $returndata=array('code'=>1,'message'=>'删除完成');
            return $returndata;
        }else{
            $returndata=array('code'=> -1,'message'=>'删除失败');
        return $returndata;
        }
    }
    //删除和用户id相关的所有中奖和内定信息
    public function deleteUserPrizebyUserid($plugname,$activityid,$userid){
        $user_prize_model=new User_Prize_model();
        $data=$user_prize_model->deleteUserPrizeByUserid($plugname,$activityid,$userid);
        return $data;
    }

    
    /**
     * 获取奖品信息
     */
    public function getprizeinfo($id){
        $prizes_model=new Prizes_model();
        $prizeinfo=$prizes_model->getById($id);
        $returndata=array('code'=>1,'message'=>'奖品信息','data'=>$prizeinfo);
        return $returndata;
    }

    
}