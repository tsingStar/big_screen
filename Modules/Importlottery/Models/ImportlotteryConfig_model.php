<?php
namespace Modules\Importlottery\Models;

class ImportlotteryConfig_model{

    var $_importlotteryconfig_m=null;
    public function __construct(){
        $this->_importlotteryconfig_m=new \M('importlottery_config');
        $this->_cache = new \CacheFactory(CACHEMODE);
    }

    public function setCurrent($id)
    {
        $this->_cache->set('currentimportlotteryid', $id, 24 * 3600);
    }

    public function getCurrent()
    {
        $id = $this->_cache->get('currentimportlotteryid');
        return $id ? $id : 0;
    }

    public function getById($id,$withtheme=false,$withdefault=false){
        $data=null;
        $join=' left join weixin_importlottery_themes on weixin_importlottery_config.themeid=weixin_importlottery_themes.id ';
        $columns='weixin_importlottery_config.id,weixin_importlottery_config.title,weixin_importlottery_config.themeid,weixin_importlottery_config.themeconfig,weixin_importlottery_config.metadata';
        $where='weixin_importlottery_config.id ='.$id .' order by id desc limit 1';
           
        if($id<=0){
            $where=' 1=1 order by id asc limit 1';
        }
        if($withtheme){
            $columns.=',weixin_importlottery_themes.themename,weixin_importlottery_themes.themepath';
            $data=$this->_importlotteryconfig_m->find($where,$columns,'','assoc',$join);
            if($withdefault==true){
                if(empty($data)){
                    $where=' 1=1 order by id asc limit 1';
                    $data=$this->_importlotteryconfig_m->find($where,$columns,'','assoc',$join);
                }
            }
            if(!empty($data)){
                $lotterythemeconfig=ImportlotteryThemeFactory::create($data['themepath']);
                $lotterythemeconfig->data(unserialize($data['themeconfig']));
                $data['themeconfig']=$lotterythemeconfig->toArray();
            }
        }else{
            $data=$this->_importlotteryconfig_m->find($where);
            // echo var_export($data);
        }
        if(!empty($data)){
            //上一轮 下一轮编号
            $prev=$this->_importlotteryconfig_m->find(' id < '.$data['id']. ' order by id desc limit 1','id');
            $next=$this->_importlotteryconfig_m->find(' id > '.$data['id']. ' order by id asc limit 1','id');
            $data['previd']=0;
            if(!empty($prev)){
                $data['previd']=$prev['id'];
            }
            $data['nextid']=0;
            if(!empty($next)){
                $data['nextid']=$next['id'];
            }
        }
        if(!empty($data['metadata'])){
            $data['metadata']=unserialize($data['metadata']);
        }
        return $data;
    }

    public function getAll($columns=''){
        $data=null;
        if($columns!=''){
            $data=$this->_importlotteryconfig_m->select(' 1=1 order by id asc',$columns);
        }else{
            $data=$this->_importlotteryconfig_m->select(' 1=1 order by id asc');
        }
        return $data;
    }

    public function save($data){
        
        $id=$data['id'];
        unset($data['id']);
        if($id>0){
            $olddata=$this->getById($id,true);
            if(isset($data['themeid']) && $olddata['themeid']!=$data['themeid']){
                $data['themeconfig']="";
            }else{
                $data['themeconfig']=is_array($data['themeconfig'])?$data['themeconfig']:[];
                $data['themeconfig']=array_merge($olddata['themeconfig'],$data['themeconfig']);
                $lotterythemeconfig=ImportlotteryThemeFactory::create($olddata['themepath']);
                $lotterythemeconfig->data($data['themeconfig']);
                $data['themeconfig']=$lotterythemeconfig->serialize();
            }
            $result=$this->_importlotteryconfig_m->update('id='.$id,$data);
            if($result){
                return $id;
            }
        }else{
            $data['created_at']=time();
            $data['themeconfig']='';
            $data['metadata']='';
            // echo var_export($data);
            $result=$this->_importlotteryconfig_m->add($data);
            // echo var_export($result);
            if($result){
                return $result;
            }
        }
        return false;
    }
}