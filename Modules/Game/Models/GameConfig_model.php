<?php
namespace Modules\Game\Models;

class GameConfig_model{
    var $_gameconfig_m=null;
    var $_cache=null;
    var $_currentgameconfig_cachename='currentgameconfig';
    public function __construct(){
        $this->_gameconfig_m=new \M('game_config');
        $this->_cache=new \CacheFactory(CACHEMODE);
    }

    public function getAll($columns=''){
        $data=null;
        if($columns!=''){
            $data=$this->_gameconfig_m->select(' 1=1 order by id asc',$columns);
        }else{
            $data=$this->_gameconfig_m->select(' 1=1 order by id asc');
        }
        return $data;
    }

    public function getCurrentConfigId(){
        $data=$this->_cache->get('currentgameconfigid');
        return $data?$data:0;
    }

    public function setCurrentConfigId($configid){
        $this->_cache->set('currentgameconfigid',$configid,24*3600);
        $this->setCurrentConfig($configid);
    }
    public function setCurrentConfig($id){
        $data=$this->getById($id,true,true);
        $this->_cache->set($this->_currentgameconfig_cachename,serialize($data));
    }
    //获取当前的配置信息
    public function getCurrentConfig($id){
        $currentconfig=$this->_cache->get($this->_currentgameconfig_cachename);
        $currentconfig=unserialize($currentconfig);
        if(!$currentconfig){
            return $this->getById($id,true,true);
        }
        return $currentconfig;
    }
    public function deleteCurrentConfig(){
        $this->_cache->delete($this->_currentgameconfig_cachename);
    }
    /**
     * 按照id获取数据
     * 
     * @param integer   $id             抽奖配置的id
     * @param bool      $withtheme      是否带上theme的信息
     * @param bool      $withdefault    是否带默认值
     * 
     * @return mixed 返回null 或者array 指定id的数据
     */
    public function getById($id,$withtheme=false,$withdefault=false){
        $data=null;
        $join=' left join weixin_game_themes on weixin_game_config.themeid=weixin_game_themes.id ';
        $columns='weixin_game_config.id,weixin_game_config.themeid,weixin_game_config.toprank,weixin_game_config.status,weixin_game_config.winagain,weixin_game_config.showtype,weixin_game_config.themeconfig';
        $where='weixin_game_config.id ='.$id .' order by id desc limit 1';
        if($id<=0){
            $where=' 1=1 order by id asc limit 1';
        }
        //包含主题信息
        if($withtheme){
            $columns.=',weixin_game_themes.themename,weixin_game_themes.themepath';
            $data=$this->_gameconfig_m->find($where,$columns,'','assoc',$join);
            
            if($withdefault==true){
                if(empty($data)){
                    $where=' 1=1 order by id asc limit 1';
                    $data=$this->_gameconfig_m->find($where,$columns,'','assoc',$join);
                }
                if(!empty($data)){
                    $gamethemeconfig=ThemeFactory::create($data['themepath']);
                    $gamethemeconfig->data(unserialize($data['themeconfig']));
                    $data['themeconfig']=$gamethemeconfig->toArray();
                }               
            }
        }
        if(!empty($data)){
        $data['previd']=$this->_prevId($data['id']);
        $data['nextid']=$this->_nextId($data['id']);
        }
        return $data;
    }
    
    private function _nextId($currentid){
        $data=$this->_gameconfig_m->find(' id > '.$currentid.' order by id asc limit 1','id');
        if(!empty($data)){
            return $data['id'];
        }
        return 0;
    }

    private function _prevId($currentid){
        $data=$this->_gameconfig_m->find(' id < '.$currentid.' order by id desc limit 1','id');
        if(!empty($data)){
            return $data['id'];
        }
        return 0;
    }
    public function save($data){
        $id=$data['id'];
        unset($data['id']);
        if($id>0){
            $olddata=$this->getById($id,true,true);
            if(isset($data['themeid']) && $olddata['themeid']!=$data['themeid']){
                $data['themeconfig']="";
            }else{
                $data['themeconfig']=isset($data['themeconfig'])?$data['themeconfig']:[];
                $data['themeconfig']=array_merge($olddata['themeconfig'],$data['themeconfig']);
                $gamethemeconfig=ThemeFactory::create($olddata['themepath']);
                $gamethemeconfig->data($data['themeconfig']);
                $data['themeconfig']=$gamethemeconfig->serialize();
            }
            $result=$this->_gameconfig_m->update('id='.$id,$data);
            if(!$result){
                return false;
            }
            $this->deleteCurrentConfig();
            return $id;
        }else{
            $data['themeconfig']='';
            $insertid=$this->_gameconfig_m->add($data);
            $this->deleteCurrentConfig();
            return $insertid;
        }
    }
}