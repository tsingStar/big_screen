<?php
namespace Modules\Importlottery\Models;
use Modules\Prize\Controllers\Api;
class Importlottery_model{
    var $_importlotterycolumns_m=null;
    var $_importlottery_m=null;
    var $_cache=null;
    var $load=null;
    public function __construct(){
        $this->_importlottery_m=new \M('importlottery');
        $this->_importlotterycolumns_m=new \M('importlotterycolumns');
        $this->_view_importlottery_m=new \M('view_importlottery');
        $this->_cache=new \CacheFactory(CACHEMODE);
        $this->load=\Loader::getInstance();
    }
    //保存导入的数据
    public function saveData($activityid,$data){
        $this->clearData($activityid);
        foreach($data as $item){
            $this->_importlottery_m->add($item);
        }
        return true;
    }

    public function clearData($configid){
        if($configid>0){
            $this->_importlottery_m->query('delete from weixin_importlottery where configid='.$configid);
        }
    }
    public function getPagedData($configid,$page=1,$pagesize=20){
        $page=$page<=0?1:$page;
        $pagesize=$pagesize<=0?20:$pagesize;
        $where=' and configid='.$configid;
        $limit=' limit '.(($page-1)*$pagesize).','.$pagesize;
        $data['data']=$this->_importlottery_m->select(' 1=1 '.$where.' order by id desc'.$limit);
        foreach($data['data'] as $k=>$v){
            $v=$this->_processItem($v);
            $data['data'][$k]=$v;
        }
        $data['count']=$this->_importlottery_m->find(' 1=1 '.$where,'*','count');
        return $data;
    }
    /**
     * 获取导入的数据
     */
    // public function getAllData($searchtxt='',$page,$pagesize){
    //     $page=$page<=0?1:$page;
    //     $pagesize=$pagesize<=0?30:$pagesize;
    //     $where='';
    //     if(!empty($searchtxt)){
    //         $where=' and (col1 like "%'.$searchtxt.'%" or col2 like "%'.$searchtxt.'%" or col3 like "%'.$searchtxt.'%")';
    //     }
    //     $limit=' limit '.(($page-1)*$pagesize).','.$pagesize;
    //     $data['data']=$this->_importlottery_m->select(' 1=1 '.$where.' order by id desc'.$limit);
    //     foreach($data['data'] as $k=>$v){
    //         $v=$this->_processItem($v);
    //         $data['data'][$k]=$v;
    //     }
    //     $data['count']=$this->_importlottery_m->select(' 1=1 '.$where,'*','count');
    //     return $data;
    // }
    /**
     * 获取所有数据
     */
    public function getAll($hashtable=false,$searchtxt=''){
        $where='1 = 1 ';
        if(!empty($searchtxt)){
            $where.=' and (col1 like "%'.$searchtxt.'%" or col2 like "%'.$searchtxt.'%" or col3 like "%'.$searchtxt.'%")  order by id desc limit 20';
        }
        $data=$this->_importlottery_m->select($where);
        if($hashtable==true){
            $newdata=[];
            foreach($data as $k=>$v){
                $newdata[$v['id']]=$v;
            }
            return $newdata;
        }
        return $data;
    }

    public function searchData($searchtxt='',$configid){
        $where='1 = 1 ';
        if(!empty($searchtxt)){
            $where.=' and datarow like "%'.$searchtxt.'%"  order by id desc limit 20';
        }
        $data=$this->_importlottery_m->select($where);
        // // if($hashtable==true){
        //     $newdata=[];
        //     foreach($data as $k=>$v){
        //         $v['datarow']=unserialize($v['datarow']);
        //         $newdata[$v['id']]=$v;
        //     }
        //     return $newdata;
        // // }
        return $data;
    }
    public function getByConfigId($id){
        $where='1 = 1 and configid='.$id;
        // if($hashtable==true){
        $data=$this->_importlottery_m->select($where);
        $newdata=[];
        foreach($data as $k=>$v){
            $newdata[$v['id']]=$v;
        }
        return $newdata;
        // }
    }
    /**
     * 按id获取数据
     */
    public function getById($id){
        $data=$this->_importlottery_m->find('id='.$id);
        $data=$this->_processItem($data);
        return $data;
    }
    /**
     * 保存一条数据
     */
    public function saveDataItem($data){
        $id=isset($data['id'])?intval($data['id']):0;
        unset($data['id']);
        if($id>0){
            return $this->_importlottery_m->update('id='.$id,$data);
        }else{
            return $this->_importlottery_m->add($data);
        }
    }

    private function _processItem($item){
        if(empty($item)){
            return ['imgid'=>0,'imagepath'=>'','datarow'=>''];
        }
        $this->load->model('Attachment_model');
        $item['imgid']=isset($item['imgid'])?intval($item['imgid']):0;
        if($item['imgid']>0){
            $attachmentinfo=$this->load->attachment_model->getById($item['imgid']);
            $item['imagepath']=$attachmentinfo['filepath'];
        }else{
            $item['imagepath']='';
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

        $item['datarow']=unserialize($item['datarow']);
        return $item;
    }
    /**
     * 更新列信息
     */
    public function saveColumnName($data){
        $newdata=[
            'col_name1'=>$data[0],
            'col_name2'=>$data[1],
            'col_name3'=>$data[2],
        ];
        $returndata=$this->_importlotterycolumns_m->update(' id=1 ',$newdata);
        return $returndata;
    }
    /**
     * 获取列的信息
     * 
     */
    public function getColumnname(){
        $data=$this->_importlotterycolumns_m->find(' id=1 limit 1');
        $data['col_name1']=empty($data['col_name1'])?'无':$data['col_name1'];
        $data['col_name2']=empty($data['col_name2'])?'无':$data['col_name2'];
        $data['col_name3']=empty($data['col_name3'])?'无':$data['col_name3'];
        return $data;
    }
    /**
     * 获取随机数据
     * 
     */
    public function getRandData($num=20,$configid){
        if($configid<=0)return false;
        $data=$this->_importlottery_m->select('1=1 and configid='.$configid.' order by rand() limit 0,'.$num);
        foreach($data as $k=>$v){
            $data[$k]=$this->_processItem($v);
        }
        return $data;
    }
    /**
     * 删除一条记录
     */
    public function deleteById($id){
        return $this->_importlottery_m->delete('id='.$id);
    }

    /**
     * 获取随机的中奖名单 
     */
    // public function getRandZjlist($num,$prizeid){
    //     $result=[];
    //     $sql=' designated=2 and status=1 and prizeid='.$prizeid.' limit '.$num;
    //     $data=$this->_view_importlottery_m->select($sql,'id,datarow,imgid,designated,status');
    //     echo var_export($data);
    //     $left=$num;
    //     if(is_array($data)){
    //         foreach($data as $v){
    //             $item=$this->_processItem($v);
    //             $result[]=$item;
    //         }
    //         $left=$num-count($data);
    //     }
    //     $sql='select * from weixin_importlottery where (select count(1) from weixin_view_importlottery where weixin_view_importlottery.id=weixin_importlottery.id and (designated=3 and prizeid='.$prizeid.'  or (status=2 or status=3) or (prizeid!='.$prizeid.' and designated=2)))=0 order by rand() limit 0,'.$left;
    //     // echo $sql;
    //     $r=$this->_view_importlottery_m->query($sql);
    //     $data2=$this->_view_importlottery_m->fetch_array($r);
    //     foreach($data2 as $v){
    //         $item=$this->_processItem($v);
    //         $result[]=$item;
    //     }
    //     return $result;
    // }
    /**
     * 每一轮导入抽奖的名单是独立的，不存在不同轮次是否能重复中奖的问题
     *
     * @param int $num 抽取数量
     * @param int $prizeid 奖品编号
     * @param int $activityid 活动id 配置id
     * @return array 随机得到的用户id序列
     */
    public function getRandZjlist($num,$prizeid,$activityid){
        $where = ' isdel = 1 and activityid = ' . $activityid;
        $userlist = $this->_view_importlottery_m->select($where);
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
                    $exceptuserids[] = $user['dataid'];
                    continue;
                }
                //内定必中的人
                
                if ($user['designated'] == 2 && $user['prizeid'] == $prizeid) {
                    $user=$this->_processItem($user);
                    $userids[$user['dataid']] = ['id' => $user['dataid'], 'designated' => $user['designated'], 'datarow' => $user['datarow'], 'imagepath' => $user['imagepath']];
                    $exceptuserids[] = $user['dataid'];
                    continue;
                }
                
                //内定必中其他奖的人
                if ($user['designated'] == 2 && $user['prizeid'] != $prizeid) {
                    $exceptuserids[] = $user['dataid'];
                    continue;
                }
                //内定不会中这个奖项的人
                if ($user['designated'] == 3 && $user['prizeid'] == $prizeid) {
                    $exceptuserids[] = $user['dataid'];
                    continue;
                }
            }
            // echo var_export($userids);
            $leftnum = $num - count($userids);
            //内定的人数等于抽取的人数
            if ($leftnum == 0) {
                // echo var_export($userids);
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
        // echo var_export($exceptuserids);
        $randusers = $this->getRandRows($leftnum, $exceptuserids,$activityid);
        foreach ($randusers as $val) {
            $val=$this->_processItem($val);
            $userids[$val['id']] = ['id' => $val['id'], 'designated' => $val['designated'], 'datarow' => $val['datarow'], 'imagepath' => $val['imagepath']];
        }
        return $userids;
    }
    public function getRandRows($num,$exceptuserids,$configid){
        $where = ' 1 ';
        if (!empty($exceptuserids)) {
            $where .= ' and id not in(' .join($exceptuserids, ',').') ';
        }
        $limit ='limit 0,'.$num;
        $order_by=' order by rand() ';
        $users=$this->_importlottery_m->select($where.$order_by.$limit);
        return $users;
    }
    /**
     * 获取当前奖项的中奖名单
     * 
     * @param integer $prizeid 奖品id
     * 
     * @return array 中奖的名单
     */
    public function getWinners($prizeid){
        $data=$this->_view_importlottery_m->select(' 1=1 and (status=2 or status=3) and prizeid='.$prizeid);
        $result=[];
        foreach($data as $v){
            $item=$this->_processItem($v);
            $result[]=$item;
        }
        return $result;
        // return $data;
    }
    public function getAllWinners($configid=-1){
        $where=' 1=1 and (status=2 or status=3) order by activityid asc, id asc';
        if($configid!=-1){
            $where=' 1=1 and (status=2 or status=3) and activityid='.$configid.' order by id asc';
        }
        $data=$this->_view_importlottery_m->select($where);
        $result=[];
        foreach($data as $v){
            $item=$this->_processItem($v);
            $result[]=$item;
        }
        return $result;
    }
    // private function _formatdata($item)
    // {
    //     if (isset($item['nickname'])) {
    //         $item['nickname'] = pack('H*', $item['nickname']);
    //     }
        // if (isset($item['status'])) {
        //     // 中奖状态1表示未中2表示中奖3表示已发奖4取消
        //     $statustext = ['', '未中', '中奖未发', '中奖已发', '取消资格'];
        //     $item['statustext'] = $statustext[$item['status']];
        // }
        // if (isset($item['designated'])) {
        //     // 中奖状态1表示未中2表示中奖3表示已发奖4取消
        //     $designatedtext = ['', '', '必中', '不会中'];
        //     $item['designatedtext'] = $designatedtext[$item['designated']];
        // }

    //     // $this->_load->model('Prizes_model');
    //     if (isset($item['prizeid'])) {
    //         $item['prize'] = $this->_prize_model->getById($item['prizeid']);
    //         // echo var_export($item['prize']);
    //     }
    //     return $item;
    // }
    /**
     * 统计数据
     * 
     */
    public function countData(){
        $data=$this->_importlottery_m->find('1=1 ','*','count');
        return $data;
    }

    public function clearRoundData($configid){
        //删除导入的名单
        $this->_importlottery_m->delete('configid='.$configid);
    }
    
    public function getLeftDataCount($prizeid,$activityid){
        $data=$this->_view_importlottery_m->select(' 1=1 and (status=2 or status=3) and activityid='.$activityid,'id');
        $newdata=[];
        foreach($data as $v){
            $newdata[$v['id']]=1;
        }
        $data=$this->_view_importlottery_m->select(' 1=1 and activityid='.$activityid.' and ((designated=2 and prizeid!='.$prizeid.')  or (designated=3 and prizeid='.$prizeid.'))','id');
        foreach($data as $v){
            $newdata[$v['id']]=1;
        }
        $count=0;
        foreach($newdata as $v){
            $count++;
        }
        
        $total=$this->_importlottery_m->find('1=1 and configid='.$activityid,'*','count');
        return intval($total)-intval($count);
    }
}