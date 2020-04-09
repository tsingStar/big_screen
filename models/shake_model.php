<?php
/**
 * 摇一摇模块
 * PHP version 5.4+
 * 
 * @category Models
 * 
 * @package Shake
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
/**
 * 摇一摇 model
 * PHP version 5.4+
 * 
 * @category Shake
 * 
 * @package Shake
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
class Shake_model extends Basemodel
{
    var $_configcachename='shake_config';
    var $_recentjoinuser='shake_joinuser_';
    var $_themecache='shake_theme_';
    var $_currentconfig=null;
    var $_shake_config_m=null;
    var $_shake_themes_m=null;
    var $_shake_record_m=null;
    var $_cache=null;
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->_shake_config_m=new M('shake_config');
        $this->_shake_record_m=new M('shake_record');
        $this->_shake_themes_m=new M('shake_themes');
        $this->_cache=new CacheFactory(CACHEMODE);
    }
    //theme 设置 start
    /**
     * 所有获取主题数据
     * 
     * @return array 获取所有主题数据的数组
     */
    public function getThemes()
    {
        $data=$this->_shake_themes_m->select('1');
        return $data;
    }

    /**
     * 按照id获取主题信息
     * 
     * @param int $id 主题的id
     * 
     * @return array 获取所有主题数据的数组
     */
    public function getThemeById($id)
    {
        $data=$this->_cache->get($this->_themecache.$id);
        if (empty($data)) {
            $data=$this->_shake_themes_m->find('id='.$id);
            if (isset($data['themedata'])) {
                $data['themedata']=empty($data['themedata'])?'':unserialize($data['themedata']);
            }
            $data=$this->_formatthemedata($data, true);
            $this->_cache->set($this->_themecache.$id, $data);
        }
        return $data;
    }
    /**
     * 设置头像
     * 
     * @param int $attachmentid 附件的id
     * @param int $index        排名
     * @param int $ishorizontal 1表示横向 2表示纵向
     * 
     * @return text 头像的路径
     */
    private function _setavatar($attachmentid=0,$index=0,$ishorizontal=1)
    {
        $defaultimg=array();
        if ($ishorizontal==1) {
            $defaultimg=array(
                '/wall/themes/meepo/assets/images/shake/ma1.png','/wall/themes/meepo/assets/images/shake/ma2.png','/wall/themes/meepo/assets/images/shake/ma3.png',
                '/wall/themes/meepo/assets/images/shake/ma4.png','/wall/themes/meepo/assets/images/shake/ma5.png','/wall/themes/meepo/assets/images/shake/ma6.png',
                '/wall/themes/meepo/assets/images/shake/ma7.png','/wall/themes/meepo/assets/images/shake/ma8.png','/wall/themes/meepo/assets/images/shake/ma9.png',
                '/wall/themes/meepo/assets/images/shake/ma10.png'
            );
        } else {
            $defaultimg=array(
                '/wall/themes/meepo/assets/images/shake/bo1.png','/wall/themes/meepo/assets/images/shake/bo2.png','/wall/themes/meepo/assets/images/shake/bo3.png',
                '/wall/themes/meepo/assets/images/shake/bo4.png','/wall/themes/meepo/assets/images/shake/bo5.png','/wall/themes/meepo/assets/images/shake/bo6.png',
                '/wall/themes/meepo/assets/images/shake/bo7.png','/wall/themes/meepo/assets/images/shake/bo8.png','/wall/themes/meepo/assets/images/shake/bo9.png',
                '/wall/themes/meepo/assets/images/shake/bo10.png'
            );
        }
        if (empty($attachmentid)) {
            // echo $attachmentid;
            $i=$index-1<0?0:($index-1);
            return $defaultimg[$i];
        }
        
        return $this->_getfilepath($attachmentid);
    }
    /**
     * 起点线图片路径
     * 
     * @param int $attachmentid 附件的id
     * @param int $ishorizontal 1表示横向 2表示纵向
     * 
     * @return text 起点线图片路径
     */
    private function _setstartline($attachmentid=0,$ishorizontal=1)
    {
        $defaultimg='/wall/themes/meepo/assets/images/shake/xxx.png';
        if ($ishorizontal==2) {
            $defaultimg='/wall/themes/meepo/assets/images/shake/xxx2.png';
        }
        if ($attachmentid<=0) {
            return $defaultimg;
        }
        return $this->_getfilepath($attachmentid);
    }
    /**
     * 终点线图片路径
     * 
     * @param int $attachmentid 附件的id
     * @param int $ishorizontal 1表示横向 2表示纵向
     * 
     * @return text 终点线图片路径
     */
    private function _setendline($attachmentid=0,$ishorizontal=1)
    {
        $defaultimg='/wall/themes/meepo/assets/images/shake/zd.png';
        if ($ishorizontal==2) {
            $defaultimg='/wall/themes/meepo/assets/images/shake/zd2.png';
        }
        if ($attachmentid<=0) {
            return $defaultimg;
        }
        return $this->_getfilepath($attachmentid);
    }
    /**
     * 奇数跑道图片路径
     * 
     * @param int $attachmentid 附件的id
     * @param int $ishorizontal 1表示横向 2表示纵向
     * 
     * @return text 奇数跑道图片路径
     */
    private function _settrackodd($attachmentid=0,$ishorizontal=1)
    {
        $defaultimg='/wall/themes/meepo/assets/images/shake/trackodd.png';
       
        if ($attachmentid<=0) {
            return $defaultimg;
        }
        return $this->_getfilepath($attachmentid);
    }
    /**
     * 偶数跑道图片路径
     * 
     * @param int $attachmentid 附件的id
     * @param int $ishorizontal 1表示横向 2表示纵向
     * 
     * @return text 偶数跑道图片路径
     */
    private function _settrackeven($attachmentid=0,$ishorizontal=1)
    {
        $defaultimg='/wall/themes/meepo/assets/images/shake/trackeven.png';
        if ($attachmentid<=0) {
            return $defaultimg;
        }
        return $this->_getfilepath($attachmentid);
    }
    /**
     * 返回图片路径
     * 
     * @param int $attachmentid 附件的id
     * 
     * @return text 返回图片路径
     */
    private function _getfilepath($attachmentid)
    {
        $load=Loader::getInstance();
        $load->model('Attachment_model');
        $attachmentinfo=$load->attachment_model->getById($attachmentid);
        return $attachmentinfo['filepath'];
    }

    /**
     * 返回图片路径
     * 
     * @param int $attachmentid 附件的id
     * 
     * @return text 返回图片路径
     */
    private function _setbg($attachmentid)
    {
        if ($attachmentid<=0) {
            return '';
        }
        return $this->_getfilepath($attachmentid);
    }
    /**
     * 设置手机图片
     * 
     * @param int $attachmentid 附件的id
     * @param int $ishorizontal 1表示横向 2表示纵向
     * 
     * @return text 手机图片路径
     */
    private function _setmobileimg($attachmentid=0,$ishorizontal=1)
    {
        $defaultimg='/mobile/template/app/images/shake/shake0.png';
        if ($ishorizontal==2) {
            $defaultimg='/mobile/template/app/images/shake/shake1.png';
        }
        if ($attachmentid<=0) {
            return $defaultimg;
        }
        return $this->_getfilepath($attachmentid);
    }
    
    /**
     * 保存主题信息
     * 
     * @param array $data 主题信息的数组
     * 
     * @return array 保存后的主题信息的数组
     */
    public function saveTheme($data)
    {
        $newdata=array();
        foreach ($data as $k=>$v) {
            $newdata[$k]=$v;
        }
        $newdata=$this->_formatthemedata($newdata, false);
        //序列化数据
        if (isset($newdata['themedata']) && !empty($newdata['themedata'])) {
            $newdata['themedata']=serialize($newdata['themedata']);
        } else {
            //默认值设置
            $newdata['themedata']=array('ishorizontal'=>1);
            $newdata['themedata']=serialize($newdata['themedata']);
        }

        $id=$newdata['id'];
        unset($newdata['id']);
        $returndata=array();

        if ($id>0) {
            $result=$this->_shake_themes_m->update('id='. $id, $newdata);
            if ($result) {
                $returndata=$newdata;
                $returndata['themedata']=unserialize($returndata['themedata']);
                $returndata['id']=$id;
            }
        } else {
            $id=$this->_shake_themes_m->add($newdata);
            $returndata=$newdata;
            $returndata['themedata']=unserialize($returndata['themedata']);
            $returndata['id']=$id;
        }
        $this->_cache->delete($this->_themecache.$returndata['id']);
        return $returndata;
    }

    /**
     * 获取当前活动的配置信息
     * 
     * @param array $themedata 主题数据
     * @param bool  $showpath  返回结果是否包含附件的路径数据
     * 
     * @return array 返回格式化后的主题数据
     */
    private function _formatthemedata($themedata,$showpath=true)
    {
        if ($showpath==false) {
            for ($i=0,$l=10;$i<$l;$i++) {
                $idx=$i+1;
                if (isset($themedata['themedata']['avatar_'.$idx.'_path'])) {
                    unset($themedata['themedata']['avatar_'.$idx.'_path']);
                }
            }
            if (isset($themedata['themedata']['startline_path'])) {
                unset($themedata['themedata']['startline_path']);
            }
            if (isset($themedata['themedata']['endline_path'])) {
                unset($themedata['themedata']['endline_path']);
            }
            if (isset($themedata['themedata']['trackodd_path'])) {
                unset($themedata['themedata']['trackodd_path']);
            }
            if (isset($themedata['themedata']['trackeven_path'])) {
                unset($themedata['themedata']['trackeven_path']);
            }
            if (isset($themedata['themedata']['bg_path'])) {
                unset($themedata['themedata']['bg_path']);
            }
            if (isset($themedata['themedata']['mobileimg_path'])) {
                unset($themedata['themedata']['mobileimg_path']);
            }
        } else {
            // echo 1;
            for ($i=0,$l=10;$i<$l;$i++) {
                $idx=$i+1;
                $themedata['themedata']['avatar_'.$idx]=isset($themedata['themedata']['avatar_'.$idx])?$themedata['themedata']['avatar_'.$idx]:0;
                $themedata['themedata']['avatar_'.$idx.'_path']=$this->_setavatar($themedata['themedata']['avatar_'.$idx], $idx, $themedata['themedata']['ishorizontal']);
            }
            $themedata['themedata']['startline']=isset($themedata['themedata']['startline'])?$themedata['themedata']['startline']:0;
            $themedata['themedata']['startline_path']=$this->_setstartline($themedata['themedata']['startline'], $themedata['themedata']['ishorizontal']);
    
            $themedata['themedata']['endline']=isset($themedata['themedata']['endline'])?$themedata['themedata']['endline']:0;
            $themedata['themedata']['endline_path']=$this->_setendline($themedata['themedata']['endline'], $themedata['themedata']['ishorizontal']);
    
            $themedata['themedata']['trackodd']=isset($themedata['themedata']['trackodd'])?$themedata['themedata']['trackodd']:0;
            $themedata['themedata']['trackodd_path']=$this->_settrackodd($themedata['themedata']['trackodd'], $themedata['themedata']['ishorizontal']);
    
            $themedata['themedata']['trackeven']=isset($themedata['themedata']['trackeven'])?$themedata['themedata']['trackeven']:0;
            $themedata['themedata']['trackeven_path']=$this->_settrackeven($themedata['themedata']['trackeven'], $themedata['themedata']['ishorizontal']);
            
            $themedata['themedata']['bg']=isset($themedata['themedata']['bg'])?$themedata['themedata']['bg']:0;
            $themedata['themedata']['bg_path']=$this->_setbg($themedata['themedata']['bg']);

            $themedata['themedata']['mobileimg']=isset($themedata['themedata']['mobileimg'])?$themedata['themedata']['mobileimg']:0;
            $themedata['themedata']['mobileimg_path']=$this->_setmobileimg($themedata['themedata']['mobileimg']);
        }
        return $themedata;
    }
    //theme设置 end
    //获取当前活动的配置信息
    /**
     * 获取当前活动的配置信息
     * 
     * @return array 保存后的主题信息的数组
     */
    public function getCurrentConfig()
    {
        //先检查是不是多次调用的
        if (!empty($this->_currentconfig)) {
            return $this->_currentconfig;
        }
        $shake_config=$this->_cache->get($this->_configcachename);
        //检查缓存是否存在
        if (empty($shake_config)) {
            $shake_config=$this->_shake_config_m->find('currentshow=2 order by id asc limit 1');
            $previdandnextid=$this->getPrevidAndNextid($shake_config['id']);
            $shake_config['nextid']=$previdandnextid['nextid'];
            $shake_config['previd']=$previdandnextid['previd'];
            if (empty($shake_config)) {
                return false;
            }
            $this->_cache->set($this->_configcachename, $shake_config);
        }
        $this->_currentconfig=$shake_config;
        return $this->_currentconfig;
    }
    /**
     * 获取上一轮活动的id和下一轮活动的id
     * 
     * @param int $id 指定的活动id
     * 
     * @return array 上一轮活动的id和下一轮活动的id 组成的数组
     */
    public function getPrevidAndNextid($id)
    {
        $previd=$this->_shake_config_m->find(
            'id<'.$id.' order by id desc limit 0,1', 'id'
        );
        $nextid=$this->_shake_config_m->find(
            'id>'.$id.' order by id asc limit 0,1', 'id'
        );
        $data['previd']=isset($previd['id'])?$previd['id']:null;
        $data['nextid']=isset($nextid['id'])?$nextid['id']:null;
        return $data;
    }
    /**
     * 保存一个配置信息
     * 
     * @param array $data 配置信息的数据数组
     * 
     * @return mixed 如果是更新返回true或false ，如果是添加返回id
     */
    public function setConfig($data)
    {
        $columns=array(
            'id','durationtype','duration','toprank','winningagain'
            ,'status','maxplayers','showstyle','currentshow','themeid'
        );
        foreach ($data as $k=>$v) {
            if (!in_array($k, $columns)) {
                unset($data[$k]);
            }
        }
        $id=isset($data['id'])?intval($data['id']):0;
        unset($data['id']);
        if ($id>0) {
            $result=$this->_shake_config_m->update('id='.$id, $data);
        } else {
            $result=$this->_shake_config_m->add($data);
        }
        return $result;
    }
    /**
     * 获取当前参与人数
     * 
     * @param int $id 活动id
     * 
     * @return array 活动参与人的数据数组
     */
    public function getCurrentPlayers($id)
    {
        $data=$this->_shake_record_m->find('configid='.$id, '*', 'count');
        return $data;
    }
    /**
     * 获取最新加入的人
     * 
     * @return array 获取最新加入的人数据数组
     */
    public function getRecentPlayers()
    {
        $shake_config=$this->getCurrentConfig();
        $data=$this->_cache->get($this->_recentjoinuser.$shake_config['id']);
        if (empty($data)) {
            return array('players'=>array(),'openidlist'=>array(),'count'=>0);
        }
        return $data;
    }
    /**
     * 取出一些新加入人员的数据
     * 
     * @param int $num 每次取出多少人
     * 
     * @return array 取出一些新加入人员的数据数组
     */
    public function shiftRecentPlayers($num=0)
    {
        $shake_config=$this->getCurrentConfig();
        $data=$this->getRecentPlayers();
        $len=count($data['players']);
        if ($num==0 || $len<=$num) {
            $returndata=$data['players'];
            $data['players']=array();
        } else {
            $returndata=array_slice($data['players'], 0, $num);
            $leftdata=array_slice($data['players'], $num, $len);
            $data['players']=$leftdata;
        }
        $this->_cache->set($this->_recentjoinuser.$shake_config['id'], $data);
        return $returndata;
    }
    /**
     * 记录添加最近加入的人
     * 
     * @param array $player 人员的数据数组
     * 
     * @return array 最近加入人员的清单
     */
    public function setRecentPlayers($player)
    {
        $shake_config=$this->getCurrentConfig();
        $data=$this->getRecentPlayers();
        if (!isset($data['openidlist'][$player['openid']])) {
            $data['openidlist'][$player['openid']]
                =$shake_config['maxplayers']>$data['count']?1:2;
            $data['count']++;
            array_push($data['players'], array('avatar'=>$player['avatar']));
            $this->_cache->set($this->_recentjoinuser.$shake_config['id'], $data);
        }
        return $data;
    }
    /**
     * 更新活动配置的缓存内容
     * 
     * @param array $options 新的缓存内容
     * 
     * @return void
     */
    public function updateCurrentConfig($options)
    {
        $oldid=$this->_currentconfig['id'];
        $ischanged=false;
        foreach ($options as $k=>$v) {
            //缓存发生变更
            if ($this->_currentconfig[$k]!=$v) {
                $this->_currentconfig[$k]=$v;
                $ischanged=true;
            }
        }

        //id发生变化
        if ($options['id'] && $options['id']!=$oldid) {
            $data=$this->getPrevidAndNextid($options['id']);
            $this->_currentconfig['nextid']=$data['nextid'];
            $this->_currentconfig['previd']=$data['previd'];
            
            $players=$this->getRecentPlayers();
            $this->_currentconfig['currentplayers']=$players['count'];
        }
        
        if ($ischanged) {
            $this->_cache->set($this->_configcachename, $this->_currentconfig);
        }
    }
    /**
     * 获取指定id的游戏结果
     * 
     * @param int $id 游戏的id
     * 
     * @return void
     */
    public function getRecord($id)
    {
        $shake_record=$this->_shake_record_m->select(
            "configid=".$id." order by point desc",
            "weixin_shake_record.*,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone", 
            '', 'assoc', 
            'left join weixin_flag on weixin_flag.id=weixin_shake_record.userid'
        );
        foreach ($shake_record as $k=>$v) {
            $shake_record[$k]['nickname']=pack('H*', $v['nickname']);
        }
        return $shake_record;
    }
    /**
     * 重置游戏指定id的游戏
     * 
     * @param int $id 游戏的id
     * 
     * @return void
     */
    public function resetShake($id)
    {
        $this->deleteShakeRecord($id);
        $shake_config=array();
        $shake_config['status']=1;
        unset($shake_config['id']);
        $this->_shake_config_m->update('id='.$id, $shake_config);
        $current_shake_config=$this->getCurrentConfig();
        if ($current_shake_config['id']==$id) {
            $this->_cache->delete($this->_recentjoinuser.$id);
            $this->_cache->delete($this->_configcachename);
        }
        return;
    }
    /**
     * 删除游戏指定id的游戏
     * 
     * @param int $id 游戏的id
     * 
     * @return void
     */
    public function deleteShake($id)
    {
        $this->deleteShakeRecord($id);
        $this->_shake_config_m->delete("id=".$id);
        $current_shake_config=$this->getCurrentConfig();
        if ($current_shake_config['id']==$id) {
            $this->_cache->delete($this->_recentjoinuser.$id);
            $this->_cache->delete($this->_configcachename);
        }
        return;
    }
    /**
     * 删除游戏指定id的游戏记录
     * 
     * @param int $id 游戏的id
     * 
     * @return void
     */
    public function deleteShakeRecord($id)
    {
        $this->_shake_record_m->delete("configid=".$id);
        return;
    }
    /**
     * 获取指定id游戏的配置信息
     * 
     * @param int $id 游戏的id
     * 
     * @return void
     */
    public function getConfig($id)
    {
        $shake_config=$this->_shake_config_m->find('id='.$id);
        return $shake_config;
    }
    /**
     * 获取所有活动配置
     * 
     * @return array 所有配置信息的数组
     */
    public function getAllConfig()
    {
        $shake_config=$this->_shake_config_m->select(
            "1 order by id asc", 
            'weixin_shake_config.*,weixin_shake_themes.themename', '', 'assoc',
            'left join weixin_shake_themes on weixin_shake_config.themeid=weixin_shake_themes.id'
        );
        return $shake_config;
    }
    /**
     * 把指定id的游戏配置，设置当前的游戏配置信息
     * 
     * @param int $id 游戏id
     * 
     * @return array 返回游戏的配置
     */
    public function setCurrentConfig($id=0)
    {
        $shake_config=$this->getCurrentConfig();
        // echo var_export($shake_config);exit();
        if ($id==0) {
            $shake_config_new=$this->_shake_config_m->find('currentshow=2');
            if (empty($shake_config_new)) {
                $shake_config_new=$this->_shake_config_m->find(
                    '1=1 order by status asc,id asc limit 1'
                );
                $this->_shake_config_m->update('1=1', array('currentshow'=>1));
                $this->_shake_config_m->update(
                    'id='.$shake_config_new['id'], array('currentshow'=>2)
                );
                $shake_config_new['currentshow']=2;
                $shake_config=$shake_config_new;
            } else {
                if ($shake_config_new['id']!=$shake_config['id']) {
                    $shake_config=$shake_config_new;
                } else {
                    foreach ($shake_config_new as $k=>$v) {
                        $shake_config[$k]=$v;
                    }
                }
            }
        } else {
            $this->_shake_config_m->update('1=1', array('currentshow'=>1));
            $this->_shake_config_m->update('id='.$id, array('currentshow'=>2));
            $shake_config=$this->_shake_config_m->find('id='.$id);
        }
        if (empty($shake_config)) {
            return array();
        }
        $this->updateCurrentConfig($shake_config);
        return $this->getCurrentConfig();
    }
    /**
     * 游戏开始
     * 
     * @return array 返回游戏的配置
     */
    public function startgame()
    {
        $shake_config=$this->getCurrentConfig();
        $shake_config['status']=2;
        $shake_config['start_at']=time();
        $this->_shake_config_m->update(
            'id='.$shake_config['id'], array('status'=>$shake_config['status'])
        );
        $this->updateCurrentConfig($shake_config);
        return $this->getCurrentConfig();
    }

    /**
     * 结束游戏
     * 
     * @return array 返回游戏的配置
     */
    public function stopGame() 
    {
        $shake_config=$this->getCurrentConfig();
        $shake_config['status']=3;
        $this->_shake_config_m->update(
            'id='.$shake_config['id'], array('status'=>$shake_config['status'])
        );
        $this->updateCurrentConfig($shake_config);
        return $this->getCurrentConfig();
    }
    /**
     * 加入游戏
     * 
     * @param text $openid 参加有的人的微信的openid
     * 
     * @return array 返回加入结果的数组
     */
    public function joingame($openid)
    {
        $shake_config=$this->getCurrentConfig();
        if (!$shake_config) {
            return array('code'=>-1,'msg'=>'当前活动不存在');
        } else {
            //把最新加入的人的头像插入到最新加入人员头像列表中
            $this->_load->model('Flag_model');
            $player=$this->_load->flag_model->getUserinfo($openid);

            //如果活动是不能重复中奖的，那么检查是否是已经中奖的用户
            if ($shake_config['winningagain']==1) {
                $shake_winner_record
                    =$this->_shake_record_m->find('userid="'.$player['id'].'" and iswinner=2');
                if ($shake_winner_record) {
                    return array('code'=>-3,'msg'=>'您已经中过奖了，无法再次参与活动');
                }
            }
            $joindata=$this->setRecentPlayers($player);
            $shake_config['currentplayers']=$joindata['count'];
            $this->updateCurrentConfig($shake_config);
            if ($shake_config['currentplayers']<=intval($shake_config['maxplayers'])) {
                $shake_record=$this->_shake_record_m->find(
                    'userid="'.$player['id'].'" and configid='.$shake_config['id']
                );
                if (!$shake_record) {
                    $shake_record=array(
                        'point'=>0, 'userid'=>$player['id'], 
                        'configid'=>$shake_config['id']
                    );
                    $this->_shake_record_m->add($shake_record);
                }
                return array('code'=>1,'msg'=>'参与成功');
            } else {
                if ($joindata['openidlist'][$openid]==1) {
                    return array('code'=>1,'msg'=>'参与成功');
                }
                return array('code'=>-2,'msg'=>'互动人数满了');
            }
        }
    }
    /**
     * 获取得奖名单
     * 
     * @return array 得奖名单
     */
    public function getWinner()
    {
        $shake_config=$this->getCurrentConfig();
        if ($shake_config['toprank']>0) {
            //先按积分，然后按加入的次序排名
            $sql="update weixin_shake_record set iswinner=2 where id in (select idtable.id from (select id from weixin_shake_record where  configid=".
                $shake_config['id']." order by point desc,id asc limit ".
                $shake_config['toprank'].
                ") as idtable)";
            $this->_shake_record_m->query($sql);
        }
        return $this->getTopN(
            $shake_config['toprank']>0?$shake_config['toprank']:10
        );
    }

    /**
     * 取前N名的数据
     * 
     * @param int $n 指定获取的前n名
     * 
     * @return array 得奖名单
     */
    public function getTopN($n=10)
    {
        $shake_config=$this->getCurrentConfig();
        $where='configid='.$shake_config['id'].' order by point desc,weixin_shake_record.id asc limit '.$n;
        $data=$this->_shake_record_m->select(
            $where, 
            'weixin_shake_record.*,weixin_flag.nickname,weixin_flag.avatar,weixin_flag.signname,weixin_flag.phone',
            '', 'assoc',
            ' left join weixin_flag on weixin_flag.id=weixin_shake_record.userid'
        );
        foreach ($data as $k=>$v) {
            if ($shake_config['showstyle']==1) {
                $data[$k]['nickname']=pack('H*', $v['nickname']);
            }
            if ($shake_config['showstyle']==2) {
                $data[$k]['nickname']=$v['signname'];
            }
            if ($shake_config['showstyle']==3) {
                $data[$k]['nickname']=substr_replace($v['phone'], '****', 3, 4);
            }
        }
        return $data;
    }

    /**
     * 爬树用户上分函数
     * 
     * @param text $userid 用户的userid
     * @param int  $score  分数
     * 
     * @return array 返回添加分数后游戏的结果
     */
    public function addScore($userid,$score)
    {
        
        $shake_config=$this->getCurrentConfig();
        $where='userid="'.$userid.'" and configid='.$shake_config['id'];
        $shake_record=$this->_shake_record_m->find($where);
        if (!$shake_record) {
            $returndata=array('code'=>-1,'msg'=>'无法参与活动');
            return $returndata;
        }
        if ($shake_config['status']==2) {
            $point=intval($shake_record['point']);//+$score;
            if ($score>0) {
                $point=$point+$score;
                if ($shake_config['durationtype']==2) {
                    if ($point>=$shake_config['duration']) {
                        $point=$shake_config['duration'];
                        $shake_config['status']=3;
                        $this->setConfig($shake_config);
                        $this->updateCurrentConfig($shake_config);
                    }
                }
                $shake_record['point']=$point;
                unset($shake_record['userid']);
                unset($shake_record['id']);
                unset($shake_record['configid']);
                unset($shake_record['iswinner']);
                $this->_shake_record_m->update($where, $shake_record);
            }
        }

        $returndata=array(
            'code'=>1,'msg'=>'',
            'data'=>array(
                'point'=>$shake_record['point'],'status'=>$shake_config['status']
                )
        );
        return $returndata;
    }
    
    function deleteTheme($id){
        $count=$this->_shake_config_m->find('themeid='.$id,'*','count');
        $count=intval($count);
        if($count>0){
            return false;
        }else{
            $result=$this->_shake_themes_m->delete('id='.$id);
            return true;
        }
    }
    /**
     * 按照openid删除游戏结果
     * 
     * @param text $openid 参与游戏的人的微信的openid
     * 
     * @return array 返回删除是否成功
     */
    public function deleteShakeRecordByOpenid($openid)
    {
        return $this->_shake_record_m->delete('openid="'.$openid.'"');
    }

    /**
     * 用于清空上墙数据,清空记录
     * 
     * @return void
     */
    public function clearshake()
    {
        //清空记录
        $this->_shake_config_m->query('truncate table weixin_shake_record');
        $this->_shake_config_m->update('1', array('status'=>1,'currentshow'=>1));
        //清空所有缓存数据
        $configs=$this->getAllConfig();
        foreach ($configs as $v) {
            $this->_cache->delete($this->_recentjoinuser.$v['id']);
        }
        $this->_cache->delete($this->_configcachename);
    }

}