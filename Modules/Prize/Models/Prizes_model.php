<?php
/**
 * 奖品模块model
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
defined('BASEPATH') or define('BASEPATH', str_replace(DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.'Prize'.DIRECTORY_SEPARATOR.'Models', '', dirname(__FILE__)));
/**
 * 奖品模块model
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
class Prizes_model
{
    var $_prizes_m=null;
    var $_cache=null;
    var $_cacheprefix='';
    var $_prizescachename='';
    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_prizes_m=new \M('prizes');
        if (CACHEMODE=="Redis") {
            $this->_cache=new \Predis\Client(
                array(
                    'scheme' => 'tcp',
                    'host'   => REDIS_HOST,
                    'port'   => REDIS_PORT,
                    'password'=> REDIS_PASSWORD
                )
            );
            $this->_cacheprefix=CACHEPREFIX;
            $this->_prizescachename='prizes';
        }
    }

    /**
     * 获取分页的奖品数据
     * 
     * @param string $plugname   组件名称
     * @param int    $activityid 活动id
     * @param int    $page       页码
     * @param int    $pagesize   每页记录条数
     * 
     * @return array 分页数据
     */
    public function getPagedData($plugname='',$activityid=0, $page=1, $pagesize=30)
    {
        $where=' 1 and isdel=1 ';
        if ($plugname!='') {
            $where.='and plugname="'.$plugname.'"';
        }
        if ($activityid>0) {
            $where.='and (activityid=0 or activityid='.$activityid.') ';
        } else {
            $where.=' and activityid=0 ';
        }
        $order=' order by id asc';
        $page=$page<=0?1:$page;
        $pagesize=$pagesize<=0?30:$pagesize;
        $limit=' limit '.(($page-1)*$pagesize).','.$pagesize;
        $data=$this->_prizes_m->select($where. $order.$limit);
        foreach ($data as $k=>$v) {
            $data[$k]=$this->_formatPrizeinfo($v);
        }
        $count=$this->_prizes_m->find($where, '*', 'count');
        return array('count'=>$count,'data'=>$data);
    }

    /**
     * 获取可用的奖品
     * 
     * @param string $plugname   组件名称
     * @param int    $activityid 活动id
     * 
     * @return array 奖品数组
     */
    public function getAvailablePrize($plugname='',$activityid=0 ,$containfreeze=false)
    {
        $where=' 1 and isdel=1 ';
        if ($plugname!='') {
            $where.='and plugname="'.$plugname.'"';
        }
        if ($activityid>0) {
            $where.='and (activityid=0 or activityid='.$activityid.') ';
        } else {
            $where.=' and activityid=0 ';
        }
        if($containfreeze==true){
            $where.=' and (freezenum>0 or leftnum>0) ';
        }else{
            $where.=' and leftnum>0 ';
        }
        $order=' order by id asc';
        $data=$this->_prizes_m->select($where. $order);
        foreach ($data as $k=>$v) {
            $data[$k]=$this->_formatPrizeinfo($v);
        }
        return $data;
    }
    /**
     * 获取所有奖品
     * @param string $plugname   组件名称
     * @param int    $activityid 活动id
     * 
     * @return array 奖品数组 ,key是奖品的id
     */
    public function getAllPrize($plugname='',$activityid=-1 ){
        // echo $plugname;
        $where=' 1 and isdel=1 ';
        if ($plugname!='') {
            $where.='and plugname="'.$plugname.'"';
        }
        if ($activityid>0) {
            $where.='and (activityid=0 or activityid='.$activityid.') ';
        } else if($activityid==0) {
            $where.=' and activityid=0 ';
        }

        $order=' order by id asc';
        $data=$this->_prizes_m->select($where. $order);
   
        $returndata=[];
        foreach ($data as $k=>$v) {
            $returndata[$v['id']]=$this->_formatPrizeinfo($v);
        }
        return $returndata;
    }
    /**
     * 保存奖品
     * 
     * @param array $data 奖品数据
     * 
     * @return mixed 失败是false ,成功的话是奖品id
     */
    public function save($data)
    {   
        if (!empty($data['prizedata'])) {
            $data['prizedata']=serialize($data['prizedata']);
        }
        if ($data['id']>0) {
            //修改
            $id=$data['id'];
            unset($data['id']);
            $result=$this->_prizes_m->update('id='.$id, $data);
            if ($result) {
                $result=$id;
            }
        } else {
            //添加
            //剩余数量和总数一致
            $data['freezenum']=0;
            $data['isdel']=1;
            $result=$this->_prizes_m->add($data);
        }

        return $result;
    }

    /**
     * 把奖品标记为删除状态
     * 
     * @param int $id 奖品的id
     * 
     * @return bool 成功为true 失败是false
     */
    // public function delete($id)
    // {
    //     $result=$this->_prizes_m->update('id='.$id, array('isdel'=>2));
    //     return $result;
    // }

    // public function deleteByPlugnameAndActivityId($plugname,$activityid){
    //     $result=$this->_prizes_m->delete('1 and plugname="'.$plugname.'" and activityid='.$activityid);
    //     return $result;
    // }

    public function delete($plugname='',$activityid=0,$id=0){
        $where =' 1=1 ';
        if($plugname!=''){
            $where.=' and plugname="'.$plugname.'" ';
            if($activityid>0){
                $where.=' and activityid='.$activityid;
            }
        }
        if($id>0){
            $where.=' and id='.$id;
        }
        $result=$this->_prizes_m->delete($where);
        return $result;
    }
    // public function restore($exceptplugname=[]){
    //     $where=' 1=1 ';
    //     if(count($exceptplugname)>0){
    //         $where=' and not in ("'.implode(',',$exceptplugname) .'")';
    //     }
    //     $sql='update weixin_prizes set leftnum=num,freezenum=0 where '.$where;
    //     return $this->
    // }
    // public function setNum($id,$num,$freezenum,$leftnum){

    // }
    /**
     * 按id获取奖品信息
     * 
     * @param int $id 奖品id
     * 
     * @return array 格式化后的奖品信息
     */
    public function getById($id)
    {
        $data=$this->_prizes_m->find('id='.$id);
        $data=$this->_formatPrizeinfo($data);
        return $data;
    }

    // private function 
    /**
     * 格式化奖品数据，类型1普通奖品2微信卡券3微信红包4虚拟卡密
     * 
     * @param array $prize 奖品信息
     * 
     * @return array 格式化后的奖品信息
     */
    private function _formatPrizeinfo($prize)
    {
        // echo $prize['type'];
        $type=isset($prize['type'])?intval($prize['type']):1;
        $prize['type']=$type;
        
        //类型文字
        $typetext=array('','普通奖品','微信卡券','现金','虚拟卡密');
        $prize['typetext']=$typetext[$prize['type']];
        $prize['prizedata_arr']=unserialize($prize['prizedata']);
        $prize['num']=empty($prize['num'])?0:intval($prize['num']);
        $prize['leftnum']=empty($prize['leftnum'])?0:intval($prize['leftnum']);
        switch($type){
        case 1:
            $prize['formatedtext']=$this->prizeNormal($prize['prizedata_arr']);
            break;
        case 3:
        
            $prize['formatedtext']=$this->prizeRedpacket($prize['prizedata_arr']);
            break;
        }
        return $prize;
    }
    /**
     * 格式化微信红包奖品的数据
     * 数据格式 array("imageid"=>1,"amount"=>100);
     * imageid 是对应的附件id
     * imageid=0 表示默认图片
     * 
     * amount是红包金额，单位是分，最小是100
     * 
     * @param array $info 奖品数据
     * 
     * @return string 奖品信息
     */
    public function prizeRedpacket($info){
        // echo var_export($prize['prizedata_arr']);
        $data=array();
        if ($info['imageid']==0) {
            $data['text']='/Modules/Prize/templates/assets/images/redpacket.png';
            $data['html']="<img src='/Modules/Prize/templates/assets/images/redpacket.png' /><br/>金额：".round($info['amount']/100,2).'元';
            return $data;
        }
        
        $load=\Loader::getInstance();
        $load->model('Attachment_model');
        $attachmentinfo=$load->attachment_model->getById($info['imageid']);
        $data['text']=$attachmentinfo['filepath'];
        $data['html']="<img src='".$attachmentinfo['filepath']."'/><br/>金额：".round($info['amount']/100,2).'元';
        return $data;
    }
    /**
     * 格式化普通奖品的数据
     * 数据格式 array("imageid"=>1);
     * imageid 是对应的附件id
     * imageid=0 表示默认图片
     * 
     * @param array $info 奖品数据
     * 
     * @return string 奖品信息
     */
    public function prizeNormal($info)
    {
        $data=array();
        if (empty($info) || $info['imageid']==0) {
            $data['text']='/wall/themes/meepo/assets/images/defaultaward.jpg';
            $data['html']="<img src='/wall/themes/meepo/assets/images/defaultaward.jpg' />";
            return $data;
        }
        
        $load=\Loader::getInstance();
        $load->model('Attachment_model');
        $attachmentinfo=$load->attachment_model->getById($info['imageid']);
        $data['text']=$attachmentinfo['filepath'];
        $data['html']="<img src='".$attachmentinfo['filepath']."'/>";
        return $data;
    }
    /**
     * 统计奖品数量
     */
    public function countPrizesNum($plugname,$configid){
        if(empty($plugname)){
            $returndata=array('totalnum'=>-1,'freezenum'=>-1,'leftnum'=>-1);
            return $returndata;
        }
        $str='';
        if($configid>0){
            $str=' or activityid='.$configid;
        }
        $sql='select sum(num) as totalnum ,sum(freezenum) as freezenum,sum(leftnum) as leftnum from weixin_prizes where plugname="'.$plugname.'" and (activityid=0 '.$str.' ) and isdel=1';
        $result=$this->_prizes_m->query($sql);
        $data=$this->_prizes_m->first_row($result);
        return $data;
    }
}