<?php
namespace Modules\Lottery\Models;

use Modules\Lottery\Models;

class LotteryConfig_model{
    var $_lotteryconfig_m=null;
    var $_cache=null;
    public function __construct(){
        $this->_lotteryconfig_m=new \M('lottery_config');
        $this->_cache=new \CacheFactory(CACHEMODE);
    }

    //获取所有抽奖轮次信息
    public function getAll($columns=''){
        $data=null;
        if($columns!=''){
            $data=$this->_lotteryconfig_m->select(' 1=1 order by id asc',$columns);
        }else{
            $data=$this->_lotteryconfig_m->select(' 1=1 order by id asc');
        }
        return $data;
    }
    /**
     * 保存抽奖配置
     */
    public function save($data){
        // echo var_export($data);
        $id=$data['id'];
        unset($data['id']);
        if($id>0){
            $olddata=$this->getById($id,true);
            if(isset($data['themeid']) && $olddata['themeid']!=$data['themeid']){
                $data['themeconfig']="";
            }else{
                $data['themeconfig']=is_array($data['themeconfig'])?$data['themeconfig']:[];
                $data['themeconfig']=array_merge($olddata['themeconfig'],$data['themeconfig']);
                $lotterythemeconfig=LotteryThemeFactory::create($olddata['themepath']);
                $lotterythemeconfig->data($data['themeconfig']);
                $data['themeconfig']=$lotterythemeconfig->serialize();
            }
            $result=$this->_lotteryconfig_m->update('id='.$id,$data);
            if($result){
                return $id;
            }
        }else{
            $data['created_at']=time();
            $data['themeconfig']='';
            $result=$this->_lotteryconfig_m->add($data);
            if($result){
                return $result;
            }
        }
        return false;
    }
    /**
     * 按照id获取数据
     * 
     * 如果找不到
     * 
     * @param integer   $id             抽奖配置的id
     * @param bool      $withtheme      是否带上theme的信息
     * @param bool      $withdefault    是否带默认值
     * 
     * @return mixed 返回null 或者array 指定id的数据
     */
    public function getById($id,$withtheme=false,$withdefault=false){
        $data=null;
        $join=' left join weixin_lottery_themes on weixin_lottery_config.themeid=weixin_lottery_themes.id ';
        $columns='weixin_lottery_config.id,weixin_lottery_config.title,weixin_lottery_config.themeid,weixin_lottery_config.winagain,weixin_lottery_config.showtype,weixin_lottery_config.themeconfig';
        $where='weixin_lottery_config.id ='.$id .' order by id desc limit 1';
           
        if($id<=0){
            $where=' 1=1 order by weixin_lottery_config.id asc limit 1';
        }
        if($withtheme){
            $columns.=',weixin_lottery_themes.themename,weixin_lottery_themes.themepath';
            $data=$this->_lotteryconfig_m->find($where,$columns,'','assoc',$join);
            if($withdefault==true){
                if(empty($data)){
                    $where=' 1=1 order by id asc limit 1';
                    $data=$this->_lotteryconfig_m->find($where,$columns,'','assoc',$join);
                }
            }
            if(!empty($data)){
                $lotterythemeconfig=LotteryThemeFactory::create($data['themepath']);
                $lotterythemeconfig->data(unserialize($data['themeconfig']));
                $data['themeconfig']=$lotterythemeconfig->toArray();
            }
            
        }else{
            $data=$this->_lotteryconfig_m->find($where);
        }
        if(!empty($data)){
            //上一轮 下一轮编号
            $prev=$this->_lotteryconfig_m->find(' id < '.$data['id']. ' order by id desc limit 1','id');
            $next=$this->_lotteryconfig_m->find(' id > '.$data['id']. ' order by id asc limit 1','id');
            $data['previd']=0;
            if(!empty($prev)){
                $data['previd']=$prev['id'];
            }
            $data['nextid']=0;
            if(!empty($next)){
                $data['nextid']=$next['id'];
            }
        }
        
        return $data;
    }

    public function del($id){
        //删除配置
        $result=$this->_lotteryconfig_m->delete('id='.$id);
        //删除中奖信息
        //删除内定信息
        //删除奖品信息
        if($result){
            return $result;
        }
    }
}