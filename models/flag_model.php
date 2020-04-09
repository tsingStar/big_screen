<?php
/**
 * 摇一摇模块
 * PHP version 5.4+
 *
 * @category Models
 *
 * @package Flag
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'basemodel.php';
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."CacheFactory.php";
/**
 * 摇一摇模块
 * PHP version 5.4+
 *
 * @category Models
 *
 * @package Flag
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
class Flag_model extends Basemodel
{
    var $_flag_config_m=null;
    var $_flag_reserved_infomation_m=null;
    var $_flag_m=null;
    var $_flag_extention_column_type_m=null;
    var $_flag_extension_data_m=null;
    var $_flag_count_cache='flag_count';
    var $_flags_cachename='flags';
    var $_flags_id_openid_cachename='flags_id_openid';
    var $_flag_config_cachename='flag_config';
    var $_flags_cache=null;
    /**
     * 构造函数
     */
    function __construct()
    {
        $this->_flag_m=new M('flag');
        $this->_flag_config_m=new M('flag_config');
        $this->_flag_extention_column_type_m=new M('flag_extention_column_type');
        $this->_flag_extension_data_m=new M('flag_extention_data');
        $this->_flag_reserved_infomation_m=new M('flag_reserved_infomation');
        $this->_cache=new CacheFactory(CACHEMODE);
        if (CACHEMODE=="Redis") {
            $this->_flags_cache=new \Predis\Client(
                array(
                    'scheme' => 'tcp',
                    'host'   => REDIS_HOST,
                    'port'   => REDIS_PORT,
                    'password'=> REDIS_PASSWORD
                )
            );
            $this->_cacheprefix=CACHEPREFIX;
        }
    }
    //保存提交的数据
    function saveExtentionData($data)
    {
        if ($this->getExtentionDataByUserId($data['userid'])) {
            $userid=$data['userid'];
            unset($data['userid']);
            return $this->_flag_extension_data_m->update('userid='.$userid, $data);
        }
        $data['created_at']=time();
        return $this->_flag_extension_data_m->add($data);
    }
    //按照
    function getExtentionDataByUserId($userid)
    {
        return $this->_flag_extension_data_m->find('userid=>'.$userid);
    }
    //删除1个字段
    function deleteExtentionColumnsById($id)
    {
        $data=$this->_flag_extention_column_type_m->delete('id='.$id);
        return $data;
    }

    //获取一个自定义扩展字段信息view_lottery
    function getExtentionColumnsById($id)
    {
        $data=$this->_flag_extention_column_type_m->find('id='.$id);
        return $data;
    }
    //获取自定义扩展字段信息
    function getExtentionColumns()
    {
        $data=$this->_flag_extention_column_type_m->select('1 order by ordernum asc,created_at asc');
        return $data;
    }
    //保存扩展字段
    function saveExtentionColumn($column)
    {
        $id=isset($column['id'])?intval($column['id']):0;
        if ($id>0) {
            unset($column['id']);
            $this->_flag_extention_column_type_m->update('id='.$id, $column);
            return $id;
        } else {
            $column['created_at']=time();
            return $this->_flag_extention_column_type_m->add($column);
        }
    }

    //清空用户信息
    function clearUserInfo()
    {
        $this->_flag_extension_data_m->query('truncate table weixin_flag_extention_data');
        $this->_flag_m->query('truncate table weixin_flag');
        $data=$this->_cache->set($this->_flag_count_cache, 0, 6*3600);
        if ($this->_flags_cache) {
            $this->_flags_cache->del($this->_cacheprefix.$this->_flags_id_openid_cachename);
            $this->_flags_cache->del($this->_cacheprefix.$this->_flags_cachename);
        }
        return ;
    }
    /**
     * 增加一个签到数字
     *
     * @return void
     */
    function incrSignedCount()
    {
        $countsign=$this->getSignedCount();
        $countsign++;
        $this->_cache->set($this->_flag_count_cache, $countsign, 6*3600);
    }
    /**
     * 减少一个签到数字
     */
    function decrSignedCount()
    {
        $countsign=$this->getSignedCount();
        $countsign--;
        $countsign=$countsign<0?0:$countsign;
        $this->_cache->set($this->_flag_count_cache, $countsign, 6*3600);
    }
    /**
     * 获取已经签到的人的数量
     *
     * @return int 签到人数
     */
    function getSignedCount()
    {
        $countsign=$this->_cache->get($this->_flag_count_cache);
        if (empty($countsign)) {
            $countsign=$this->_getUsersCount('flag=2');
            $this->_cache->set($this->_flag_count_cache, $countsign, 6*3600);
        }
        return $countsign;
    }

    //获取通过审核的人数
    function getShenheCount()
    {
        return $this->_getUsersCount('flag=2 and status=1');
    }

    //按照openid获取用户相关的信息
    function getUserinfo($openid, $withreservedinfomation = false)
    {
        
        $userinfo=$this->_getUserInfoByOpenid($openid);
        
        if ($withreservedinfomation) {
            $flagconfig=$this->getConfig();
            if ($flagconfig['reserved_infomation_match']==1) {
                $where='1 and (realname="'.$userinfo['signname'].'" or phone="'.$userinfo['phone'].'")';
            } else {
                $where='1 and (realname="'.$userinfo['signname'].'" and phone="'.$userinfo['phone'].'")';
            }
            $select='info,realname,phone as verify_phone';
            $reservedinformation=$this->_flag_reserved_infomation_m->find($where,$select);
            $reservedinformation=$reservedinformation?$reservedinformation:[];
            return array_merge($userinfo,$reservedinformation);
        }
        return $userinfo;
    }

    private function _getUserInfoByOpenid($openid){
        $data=null;
        if($this->_flags_cache){
            $data=$this->_getUserInfoFromRedisByOpenid($openid);
            if(!$data){
                $data=$this->_flag_m->find('openid="'.$openid.'"', '*');
                if(!$data){
                    return null;
                }
                $data['nickname']=pack('H*', $data['nickname']);
                $this->_setUserInfoRedis($data);
            }
        }else{
            $data=$this->_flag_m->find('openid="'.$openid.'"', '*');
            if($data){
                $data['nickname']=pack('H*', $data['nickname']);
            }
        }
        return $data;    
    }

    
    private function _setUserInfoRedis($userinfo){
        $this->_flags_cache->hset($this->_cacheprefix.$this->_flags_cachename, $userinfo['openid'], serialize($userinfo));
        $this->_flags_cache->hset($this->_cacheprefix.$this->_flags_id_openid_cachename,$userinfo['id'],$userinfo['openid']);
    }
    
    private function _getUserInfoFromRedisByOpenid($openid){
        $data=$this->_flags_cache->hget($this->_cacheprefix.$this->_flags_cachename,$openid);
        if(!$data)return null;
        return unserialize($data);
    }
    private function _getUserInfoFromRedisById($userid){
        $openid=$this->_flags_cache->hget($this->_cacheprefix.$this->_flags_id_openid_cachename,$userid);
        if(!$openid) return null;
        $userinfo=$this->_flags_cache->hget($this->_cacheprefix.$this->_flags_cachename, $openid);
        return unserialize($userinfo);
    }
    private function _deleteUserInfoFromRedisById($userid){
        $data=$this->_getUserInfoFromRedisById($userid);
        $this->_flags_cache->hdel($this->_cacheprefix.$this->_flags_id_openid_cachename, [$userid]);
        $this->_flags_cache->hdel($this->_cacheprefix.$this->_flags_cachename, [$data['openid']]);
    }
    private function _deleteUserInfoFromRedisByOpenid($openid){
        $data=$this->_getUserInfoFromRedisByOpenid($openid);
        $this->_flags_cache->hdel($this->_cacheprefix.$this->_flags_id_openid_cachename, [$data['id']]);
        $this->_flags_cache->hdel($this->_cacheprefix.$this->_flags_cachename, [$openid]);
    }
    //按照id获取用户相关信息
    function getUserinfoById($id)
    {
        $data=null;
        if ($this->_flags_cache) {
            $data=$this->_getUserInfoFromRedisById($id);
            if (!$data) {
                $data=$this->_flag_m->find('id="'.$id.'"', '*');
                if(!$data)return null;
                $data['nickname']=pack('H*', $data['nickname']);
                $this->_setUserInfoRedis($data);
            }
        } else {
            $data=$this->_flag_m->find('id="'.$id.'"', '*');
            if ($data) {
                $data['nickname']=pack('H*', $data['nickname']);
            }
        }
        return $data;
    }
    //获取当前最大的审核编号
    function getMaxSignorder()
    {
        $signorder=$this->_flag_m->find('1 limit 0,1', 'signorder', 'max');
        $signorder=isset($signorder)?intval($signorder):0;
        return $signorder;
    }
    /**
     * 保存用户信息，目前用于确认签到
     * 头像改成了https的路径
     * 
     */
    function saveUserinfo($info)
    {
        $openid=$info['openid'];
        if ($openid=='') {
            return false;
        }
        $data=$this->_flag_m->find("openid='" . $openid. "'");
        $result=false;
        $id=0;
        $newdata=[];
        if ($data) {
            $newdata=array_merge($data, $info);
            unset($newdata['openid']);
            unset($newdata['id']);
            if(isset($newdata['avatar'])){
                $newdata['avatar']=str_replace('http://','https://',$newdata['avatar']);
            }
            $result=$this->_flag_m->update("openid='" . $openid . "'", $newdata);
            if ($this->_flags_cache) {
                $this->_deleteUserInfoFromRedisByOpenid($openid);
            }
            $id=$newdata['id'];
        } else {
            if(isset($info['avatar'])){
                $info['avatar']=str_replace('http://','https://',$info['avatar']);
            }
            $newdata=$info;
            $result=$this->_flag_m->add($newdata);
            $id=$result;
        }
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    //获取查询结果的分页数据
    function getPagedData($where, $page, $pagesize, $joinextention = false)
    {
        $data=$this->_getPagedUsers($where, $page, $pagesize, $joinextention);
        $count=$this->_getUsersCount($where);
        return array('data'=>$data,'count'=>$count);
    }
    //获取审核用户的数据
    function getShenheUsers($lastsignorder = 0, $num = 50)
    {
        $where='signorder>'.$lastsignorder.' and flag=2 and status=1 order by signorder asc';
        $data=$this->_getPagedUsers($where, 1, $num);
        return $data;
    }
    //获取最近签到的人信息
    function getRecentSignedUsers($num = 30, $lastsignorder = 0)
    {
        $where='flag=2 and status=1 order by signorder desc';
        if ($lastsignorder>0) {
            $where='signorder > '.$lastsignorder.' and '.$where;
        }
        $returndata=$this->_getPagedUsers($where, 1, $num);
        return $returndata;
    }

    //保存扫码后由微信提供的用户信息
    function saveRemoteUserinfo($info)
    {
        //解决转义符
        //json字符串转化成数组
        $infoarr=null;
        if (is_array($info)) {
            $infoarr=$info;
        } else {
            $infoarr = json_decode($infoarr, true);
        }
        $flag_info=$this->getUserinfo($infoarr['openid']);
        if (intval($flag_info['flag'])>=2) {
            return true;
        }
        $flag_info_new = array(
            "openid"=>$infoarr['openid'],
            "rentopenid"=>$infoarr['rentopenid'],
            "nickname" => $infoarr['nickname'],
            "avatar" => $infoarr['headimgurl'],
            "sex" => $infoarr['sex'],
            "fromtype" => 'weixin',
            "datetime" => time(),
            "flag" => "1"
        );
        return $this->saveUserinfo($flag_info_new);
    }

    //添加预留信息
    public function addReservedInfomation($data)
    {
        $result=$this->_flag_reserved_infomation_m->add($data);
        return $result;
    }
    //获取所有预留信息
    public function getAllReservedInfomation()
    {
        $result=$this->_flag_reserved_infomation_m->select('1=1');
        return $result;
    }

    public function clearReservedInfomation()
    {
        $this->_flag_reserved_infomation_m->query('truncate table weixin_flag_reserved_infomation');
    }
    //获取全部的数据
    public function getAllUsers()
    {
        $where=' 1 order by weixin_flag.id asc';
        $join='left join weixin_flag_extention_data on weixin_flag_extention_data.userid=weixin_flag.id';
        $columns='weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone,weixin_flag_extention_data.datastr';
        $data=$this->_flag_m->select($where, $columns, '', 'assoc', $join);
        foreach ($data as $k => $v) {
            $data[$k]['extentions']=unserialize($v['datastr']);
        }
        return $data;
    }
    //按照条件获取用户列表
    private function _getPagedUsers($where, $page, $pagesize, $joinextention = false)
    {
        $page=$page>0?$page:1;
        $wherecondition=$where. ' limit '.(($page-1)*$pagesize).','.$pagesize;
        if ($joinextention==true) {
            $join='left join weixin_flag_extention_data on weixin_flag_extention_data.userid=weixin_flag.id';
            $data=$this->_flag_m->select($wherecondition, 'weixin_flag.*,weixin_flag_extention_data.datastr', '', 'assoc', $join);
            foreach ($data as $k => $v) {
                $data[$k]['extentions']=unserialize($v['datastr']);
            }
        } else {
            $data=$this->_flag_m->select($wherecondition, '*');
        }
        
        return $data;
    }

    //保存配置
    public function setConfig($data)
    {
        $this->_cache->delete($this->_flag_config_cachename);
        return $this->_flag_config_m->update('1', $data);
    }

    //读取配置
    public function getConfig()
    {
        $data=$this->_cache->get($this->_flag_config_cachename);
        if(!$data){
            $data=$this->_flag_config_m->find('1');
            $this->_cache->set($this->_flag_config_cachename,$data,3600);   
        }
        return $data;
    }

    //按照条件获取用户的数量
    private function _getUsersCount($where)
    {
        $count=$this->_flag_m->find($where, '*', 'count');
        return $count;
    }
    /**
     * 获取随机n个已经签到并通过审核的人的名单
     *
     * @param integer $num 选取的人数
     * @param array   $exceptuserids 不包含的用户id
     *
     * @return array 随机取出的用户列表
     */
    public function getRandUsers($num, $exceptuserids = [], $select = '*')
    {
        $where = ' flag=2 and  status = 1 ';
        if (!empty($exceptuserids)) {
            $where .= ' and id not in(' .join($exceptuserids, ',').') ';
        }
        $limit ='limit 0,'.$num;
        $order_by=' order by rand() ';
        $users=$this->_flag_m->select($where.$order_by.$limit, $select);
        return $users;
    }
    //根据id删除用户信息
    public function deleteByUserid($userid){
        $this->_flag_m->delete('`id`='.$userid);
        if($this->_flags_cache){
            $this->_deleteUserInfoFromRedisById($userid);
        }
    }
}
