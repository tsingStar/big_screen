<?php
namespace Modules\Game\Models;
require_once BASEPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'flag_model.php';
class Game_model{
    var $_cache=null;
    var $_redis_cache=null;
    var $_joingame_cachename='joingame_';
    var $_recentjoinusers_cachename='recentjoinuser_';
    var $_joinuser_cachename='joinuser_';
    var $_joinusernum='joinusernum_';
    var $_game_records_m=null;
    var $flag_model=null;
    public function __construct(){
        $this->_cache=new \CacheFactory(CACHEMODE);
        if(CACHEMODE=="Redis") {
            $this->_redis_cache=new \Predis\Client(
                array(
                    'scheme' => 'tcp',
                    'host'   => REDIS_HOST,
                    'port'   => REDIS_PORT,
                    'password'=> REDIS_PASSWORD
                )
            );
            $this->_cacheprefix=CACHEPREFIX;
        }
        $this->flag_model=new \Flag_model();
        $this->_game_records_m=new \M('game_records');
        $this->_game_config_m=new \M('game_config');
    }
    /**
     * 加入游戏
     */
    public function joinGame($user,$gameid,$winagain){
        //添加当前进入的人员信息
        unset($user['sex']);
        unset($user['fromtype']);
        unset($user['flag']);
        unset($user['rentopenid']);
        unset($user['status']);
        unset($user['datetime']);
        unset($user['signorder']);
        if($this->_redis_cache){
            return $this->redisJoinGame($user,$gameid,$winagain);
        }else{
            return $this->commonJoinGame($user,$gameid,$winagain);
        }
    }
    /**
     * Undocumented function
     *
     * @param [type] $userid
     * @param [type] $gameid
     * @return void
     */
    public function getJoinStatus($userid,$gameid){
        $joinstatus=1;
        if($this->_redis_cache){
            $cachename=$this->_cacheprefix.$this->_joinuser_cachename.$gameid;
            $joinstatus=$this->_redis_cache->hget($cachename,$userid);
            if($joinstatus){
                return $joinstatus;
            }
        }else{
            $cachename=$this->_joinuser_cachename.$gameid;
            $data=$this->_cache->get($cachename);
            if($data){
                $data=unserialize($data);
                $joinstatus=$data[$userid];
                return $joinstatus;
            }
        }
        return -1;
    }
    /**
     * Undocumented function
     *
     * @param [type] $user
     * @param [type] $gameid
     * @return void
     */
    private function _recentJoinUsers($user,$gameid){
        $cachename=$this->_recentjoinusers_cachename.$gameid;
        $recentjoinusers=$this->_cache->get($cachename);
        if($recentjoinusers){
            $recentjoinusers=unserialize($recentjoinusers);
            array_push($recentjoinusers,$user);
        }else{
            $recentjoinusers=[];
            array_push($recentjoinusers,$user);
        }
        $this->_cache->set($cachename,serialize($recentjoinusers),24*3600);
    }
    public function getRecentJoinUsers($gameid){
        $cachename=$this->_recentjoinusers_cachename.$gameid;
        $recentjoinusers=$this->_cache->get($cachename);
        $recentjoinusers=unserialize($recentjoinusers);
        $this->_cache->delete($cachename);
        return $recentjoinusers;
    }
    //返回参与游戏的人数
    private function redisJoinGame($user,$gameid,$winagain){
        $cachename=$this->_cacheprefix.$this->_joinuser_cachename.$gameid;
        $joinnum=$this->_redis_cache->hlen($cachename);
        if($joinnum>=200){
            $returndata=$this->_redis_cache->hsetnx($cachename,$user['id'],2);
            if($returndata==1){
                $this->_recentJoinUsers($user,$gameid);
                $this->addJoinUserNum($joinnum+1,$gameid);
            }
        }else{
            $iswinner=$this->isWinner($user['id']);
            if($iswinner && $winagain==1){
                $returndata=$this->_redis_cache->hsetnx($cachename,$user['id'],3);
            }else{
                $returndata=$this->_redis_cache->hsetnx($cachename,$user['id'],1);
                $this->redisAddPlayers($user,$gameid);
                if($returndata==1){
                    $this->_recentJoinUsers($user,$gameid);
                    $this->addJoinUserNum($joinnum+1,$gameid);
                }
            }
        }
    }
    private function addJoinUserNum($num,$gameid){
        $cachename=$this->_joinusernum.$gameid;
        $this->_cache->set($cachename,$num,24*3600);
    }
    /**
     * 是否已经得奖过
     *
     * @param [type] $userid
     * @return boolean
     */
    private function isWinner($userid){
        $data=$this->_game_records_m->find('userid='.$userid.' limit 1','id');
        if(!empty($data)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取当前游戏的加入人数
     *
     * @param integer $gameid 游戏编号
     * @return void
     */
    public function getJoinUserNum($gameid){
        $num=$this->_cache->get($this->_joinusernum.$gameid);
        // echo $this->_joinusernum.$gameid;
        $num=$num?$num:0;
        return $num;
    }
    /**
     * 获取加入游戏的用户的信息
     *
     * @param integer $userid 用户id
     * @return void
     */
    private function getJoinUser($userid,$gameid){
        $user=$this->flag_model->getUserinfoById($userid);
        return $user;
        
    }
    //返回参与游戏的人数
    private function commonJoinGame($user,$gameid){
        //考虑记录到数据库中,先试试效果
        $cachename=$this->_joinuser_cachename.$gameid;
        $data=$this->_cache->get($cachename);
        
        if($data){
            $data=unserialize($data);
        }else{
            $data=[];
        }
        $joinnum=count($data);
        if(isset($data[$user['id']])){
            return;
        }
        
        if($joinnum>=200){
            $data[$user['id']]=2;
        }else{
            $iswinner=$this->isWinner($user['id']);
            if($iswinner && $winagain==1){
                $data[$user['id']]=3;
            }else{
                $data[$user['id']]=1;
                $this->commonAddPlayers($user,$gameid);
            }
        }
        $this->_recentJoinUsers($user,$gameid);
        $this->_cache->set($cachename,serialize($data),24*3600);
        $this->addJoinUserNum($joinnum+1,$gameid);
    }
    //添加参与游戏的人
    private function redisAddPlayers($user,$gameid){
        $cachename=$this->_cacheprefix.$this->_joingame_cachename.$gameid;
        $this->_redis_cache->hsetnx($cachename,$user['id'],0);
    }
    //
    private function commonAddPlayers($user,$gameid){
        //考虑记录到数据库中,先试试效果
        $cachename=$this->_joingame_cachename.$gameid;
        $data=$this->_cache->get($cachename);
        if($data){
            $data=unserialize($data);
        }else{
            $data=[];
        }
        if(!isset($data[$user['id']])){
            $data[$user['id']]=0;
            $this->_cache->set($cachename,serialize($data),24*3600);     
        }
    }


    public function addScore($gameid,$userid,$points){
        if($this->_redis_cache){
            $data=$this->_redis_cache->hincrby($this->_cacheprefix.$this->_joingame_cachename.$gameid,$userid,$points);
        }else{
            $data=$this->_cache->get($this->_joingame_cachename.$gameid);
            $data=unserialize($data);
            $data[$userid]=$data[$userid]+$points;
            $data=serialize($data);
            $this->_cache->set($this->_joingame_cachename.$gameid,$data);
        }
        return ;
    }
    /**
     * 保存前n名的数据
     *
     * @param integer $num 前N名
     * @return bool true保存成功 false保存失败
     */
    public function saveWinners($num,$gameid){
        $topusers=$this->getTopUsers($num,$gameid);
        if(count($topusers)<=0){
            return true;
        }
        $records=[];
        $now=time();
        for($i=0,$l=count($topusers);$i<$l;$i++){
            $records[]=['userid'=>$topusers[$i]['userid'],'points'=>$topusers[$i]['point'],'created_at'=>$now,'updated_at'=>$now,'gameid'=>$gameid];
        }
        return $this->_game_records_m->addMutiRows($records);
    }
    /**
     * 获取前n名用户
     *
     * @param integer $num 前n名
     * @param integer $gameid 游戏编号
     * @return void
     */
    public function getTopUsers($num,$gameid){
        if($this->_redis_cache){
            $data=$this->_redis_cache->hgetall($this->_cacheprefix.$this->_joingame_cachename.$gameid);
        }else{
            $data=$this->_cache->get($this->_joingame_cachename.$gameid);
            $data=unserialize($data);
        }
        if(count($data)<=0){
            return [];
        }
        require_once BASEPATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'function.php';
        //todo：缺少一个按照用户id的顺序排序
        $point=[];
        $userid=[];
        $newarr_point_id=[];
        foreach($data as $key=>$v){
            $point[$key]=$v;
            $userid[$key]=$key;
            $newarr_point_id[$key]=['point'=>$v,'userid'=>$key];
        }
        array_multisort($point,SORT_DESC,$userid,SORT_ASC,$newarr_point_id);
        $rows=count($newarr_point_id);
        $maxlength= $num>$rows?$rows:$num;
        $records=array_slice($newarr_point_id,0,$maxlength);
        $result=[];
        foreach($records as $k=>$v){
            $user=$this->flag_model->getUserinfoById($v['userid']);
            $result[]=['userid'=>$v['userid'],'nickname'=>$user['nickname'],
            'avatar'=>$user['avatar'],'phone'=>$user['phone'],
            'signname'=>$user['signname'],
            'point'=>$v['point']
        ];
        }
        return $result;
    }
    /**
     * 显示优胜名单
     *
     * @param integer $gameid 游戏编号
     * @param string $showtype 显示类型 nickname或者signname或者phone
     * @return void
     */
    public function getWinners($gameid,$showtype){
        $where='gameid='.$gameid.' order by points desc,userid asc';
        $data=$this->_game_records_m->select($where,'userid,points,id');
        
        if(empty($data)){
            return [];
        }
        for($i=0,$l=count($data);$i<$l;$i++){
            $user=$this->flag_model->getUserinfoById($data[$i]['userid']);
            $data[$i]['avatar']=$user['avatar'];
            $data[$i]['nickname']=$user[$showtype];
        }
        return $data;
    }
    public function getCompleteWinners(){
        $where=' 1 order by gameid asc, points desc,userid asc';
        $data=$this->_game_records_m->select($where,'gameid,userid,points,id,created_at');
        if(empty($data)){
            return [];
        }
        for($i=0,$l=count($data);$i<$l;$i++){
            $user=$this->flag_model->getUserinfoById($data[$i]['userid']);
            $data[$i]['avatar']=$user['avatar'];
            $data[$i]['nickname']=$user['nickname'];
            $data[$i]['signname']=$user['signname'];
            $data[$i]['phone']=$user['phone'];
        }
        return $data;
    }
    /**
     * 清空缓存
     *
     * @param integer $gameid 游戏id
     * @return void
     */
    private function clearCache($gameid){
        if($this->_redis_cache){
            $this->_redis_cache->del($this->_cacheprefix.$this->_joingame_cachename.$gameid);
            $this->_redis_cache->del($this->_cacheprefix.$this->_joinuser_cachename.$gameid);
        }else{
            $this->_cache->delete($this->_joingame_cachename.$gameid);
            $this->_cache->delete($this->_joinuser_cachename.$gameid);
        }
        $this->_cache->delete($this->_joinusernum.$gameid);
        $this->_cache->delete($this->_recentjoinusers_cachename.$gameid);
    }

    public function clearJoinData($gameid){
        
        if($this->_redis_cache){
            $return=$this->_redis_cache->del($this->_cacheprefix.$this->_joinuser_cachename.$gameid);
        }else{
            $this->_cache->delete($this->_joinuser_cachename.$gameid);
        }
        $this->_cache->delete($this->_joinusernum.$gameid);
        $this->_cache->delete($this->_recentjoinusers_cachename.$gameid);
    }
    /**
     * 重置一轮活动
     *
     * @param integer $gameid 游戏id
     * @return void
     */
    public function clearData($gameid){
        $this->clearCache($gameid);
        //删除游戏结果
        $sql='delete from weixin_game_records where gameid='.$gameid;
        $this->_game_records_m->query($sql);
        //重置游戏状态
        $sql='update weixin_game_config set status=1 where id='.$gameid;
        $this->_game_records_m->query($sql);
    }
    //删除一轮游戏
    public function delete($id){
        $this->clearCache($gameid);
        $this->_game_records_m->delete('gameid='.$id);
        $this->_game_config_m->delete('id='.$id);
        return true;
    }
    /**
     * 删除所有活动记录
     *
     * @return void
     */
    public function clearAllData(){
        $data=$this->_game_config_m->select('1','id');
        foreach($data as $v){
            $this->clearCache($v['id']);
        }
        $sql='truncate  weixin_game_records';
        $this->_game_records_m->query($sql);
        $sql='update weixin_game_config set status=1';
        $this->_game_records_m->query($sql);
    }
    /**
     * 删除单个人的中奖记录
     *
     * @param integer $userid 用户id
     * @return void
     */
    public function deleteRecordByUserId($userid){
        $sql="delete from weixin_game_records where userid=".$userid;
        $this->_game_records_m->query($sql);
    }
}