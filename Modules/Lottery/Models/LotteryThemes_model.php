<?php
namespace Modules\Lottery\Models;

class LotteryThemes_model{
    var $_lotterythemes_m=null;
    var $_cache=null;
    public function __construct(){
        $this->_lotterythemes_m=new \M('lottery_themes');
        $this->_cache=new \CacheFactory(CACHEMODE);
    }

    //获取所有抽奖轮次信息
    public function getAll(){
        $data=$this->_lotterythemes_m->select(' 1=1 order by id asc');
        return $data;
    }

    public function save($data){
        
    }

    /**
     * 按照id获取数据
     * 
     * @param integer $id 抽奖配置的id
     * @param bool $withdefault 是否带默认值
     * 
     * @return mixed 返回null 或者array 指定id的数据
     */
    public function getById($id,$withdefault=false){
        $data=null;
        if($withdefault==true && $id<=0){
            $data=$this->_lotterythemes_m->find(' 1=1 order by id asc limit 1');
        }else{
            $data=$this->_lotterythemes_m->find('id='.$id);
        }
        return $data;
    }
}