<?php 
//组件model
//todo:被调用的地方比较多，需要增加缓存
class Plugs_model{
    var $_plugs_m=null;
    public function __construct(){
        $this->_plugs_m=new M('plugs');
    }

    //获取所有组件的信息
    public function switchPlug($name,$switch)
    {
        $where='name="'.$name.'"';
        $result=$this->_plugs_m->update($where, array('switch'=>$switch));
        return $result;
    }
    //设置快捷键
    public function setHotkey($name,$key)
    {
        $where='name="'.$name.'"';
        $result=$this->_plugs_m->update($where, array('hotkey'=>$key));
        return $result;
    }
    //获取抽奖组件
    public function getChoujiangPlug()
    {
        return $this->_plugs_m->select('choujiang=1 order by ordernum desc');
    }
    //获取所有组件的信息
    public function getPlugs($switch=0)
    {
        $where=' 1 ';
        if ($switch>0) {
            $where.=' and switch='.$switch.' ';
        }else{
            $where.='and (switch=1 or switch=2)';
        }

        $where.=' order by ordernum asc';
        $plugs=$this->_plugs_m->select($where);
        foreach ($plugs as $k=>$v) {
            $item=$this->_formartdata($v);
            if($item==null){
                continue;
            }
            $plugs[$k]=$item;
        }
        return $plugs;
    }

    public function getPlugByName($name)
    {
        $where='name="'.$name.'" limit 1';
        $result=$this->_plugs_m->find($where);
        $result=$this->_formartdata($result);
        return $result;
    }
    private function _formartdata($item)
    {
        if (isset($item['ismodule']) && $item['ismodule']==2) {
            return $item;
        }
        $path=__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.ucfirst($item['name']).DIRECTORY_SEPARATOR.'config.php';
        if(file_exists($path)){
            include $path;
            if(!isset($config['front'])){
                return null;
            }
            $item['title']=$config['front']['menu']['name'];
            $item['url']=$config['front']['menu']['link'];
            $item['img']=$config['front']['menu']['icon'];
            if(empty($item['hotkey'])){
                $item['hotkey']=$config['front']['menu']['shortcut'];
            }
            $item['mobile']=$config['mobile'];
            return $item;
        }
        return null;
    }
}