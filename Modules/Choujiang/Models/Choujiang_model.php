<?php
namespace Modules\Choujiang\Models;
class Choujiang_model{
    var $_choujiang_config_m=null;
    var $_choujiang_themes_m=null;
    var $_choujiang_users_m=null;
    var $_cache=null;
    var $_themehashs_cache='_themeshash_cache';
    public function __construct(){
        $this->_choujiang_config_m=new \M('choujiang_config');
        $this->_choujiang_themes_m=new \M('choujiang_themes');
        $this->_choujiang_users_m=new \M('choujiang_users');
        $this->_cache=new \CacheFactory(CACHEMODE);
    }
    //获取所有抽奖配置
    public function getAll(){
        $select='id,title,defaultnum,description,showleftnum,started_at,created_at,ended_at,themeid';
        $data=$this->_choujiang_config_m->select('1 order by id asc',$select,'','assoc');
        return $data;
    }
    public function getById($id){
        $data=$this->_choujiang_config_m->find('id='.$id);
        return $data;
    }

    public function resetConfig($id){
        $prize_api=new \Modules\Prize\Controllers\Api();
        $result=$prize_api->resetprizes('choujiang',$id);

        $this->_choujiang_users_m->query('truncate table weixin_choujiang_users');

        return $result;
    }
    //删除一个配置信息
    public function delete($id){
        $prize_api=new \Modules\Prize\Controllers\Api();
        $result=$prize_api->delUserPrizeByActivityId('choujiang',$id);
        if(!$result)return false;
        $result=$this->_choujiang_config_m->delete('id='.$id);
        return $result;
    }
    /**
     * 保存配置信息
     */
    public function save($data){
        $id=$data['id'];
        unset($data['id']);
        $result=false;
        if($id>0){
            $result=$this->_choujiang_config_m->update('id='.$id,$data);
        }else{
            $data['created_at']=time();
            $result=$this->_choujiang_config_m->add($data);
        }
        return $result;
    }
    /**
     * 获取所有主题
     */
    public function getAllThemes(){
        $data=$this->_choujiang_themes_m->select('1');
        return $data;
    }
    //按照id获取theme的信息
    public function getThemeById($id){
        $data=$this->_cache->get($this->_themehashs_cache);
        if($data){
            return $data[$id];
        }else{
            $themes=$this->getAllThemes();
            $hash_themes=[];
            foreach($themes as $item){
                $hash_themes[$item['id']]=$item;
            }
            $this->_cache->set($this->_themehashs_cache,$hash_themes,24*3600);
            return $hash_themes[$id];
        }
    }
    /**
     * 加入游戏
     */
    public function joinGame($config,$userid){
        $data=$this->_choujiang_users_m->find(' 1 and choujiangid='.$config['id'].' and userid='.$userid);
        if(empty($data)){
            $newdata=[
                'choujiangid'=>$config['id'],
                'userid'=>$userid,
                'cjtimes'=>0,
                'lefttimes'=>$config['defaultnum']
            ];
            $insertid=$this->_choujiang_users_m->add($newdata);
            $newdata['id']=$insertid;
            return $newdata;
        }else{
            return $data;
        }
    }

    public function incrCjtimes($configid,$userid){
        $data=$this->_choujiang_users_m->find(' 1 and choujiangid='.$configid.' and userid='.$userid);
        $cjtimes=$data['cjtimes']+1;
        $lefttimes=$data['lefttimes']-1;
        if($lefttimes<0){
            return false;
        }else{
            return $this->updateLefttimes($configid,$userid,$cjtimes,$lefttimes);
        }
    }

    public function incrLeftimes($configid,$userid){
        $data=$this->_choujiang_users_m->find(' 1 and choujiangid='.$config['id'].' and userid='.$userid);
        $cjtimes=$data['cjtimes'];
        $lefttimes=$data['lefttimes']+1;
        if($lefttimes<0){
            return false;
        }else{
            return $this->updateLefttimes($configid,$userid,$cjtimes,$lefttimes);
        }
    }

    //修改参与次数
    public function updateLefttimes($configid,$userid,$cjtimes,$lefttimes){
        $data=[
            'cjtimes'=>$cjtimes,
            'lefttimes'=>$lefttimes
        ];
        $result=$this->_choujiang_users_m->update(' 1 and choujiangid='.$configid.' and userid='.$userid,$data);
        return $result;
    }
    //重置游戏
    public function resetAllGames(){
        $this->_choujiang_users_m->query('truncate table weixin_choujiang_users');
    }
}