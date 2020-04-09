<?php
namespace Modules\Menu\Controllers;
use \Modules\Menu\Models\Menu_model;

class Api{
    
    public function __construct(){
    }

    public function getAll($params){
        $menu_model=new Menu_model();
        $data=$menu_model->getAll();
        if(empty($data))return null;
        foreach($data as $k=>$v){
            $data[$k]['url']=empty($params)?$v['link']:$this->_formaturl($v['link'],$params);
        }
        return $data;
    }

    private function _formaturl($url,$params){
        $params_array=[];
        foreach($params as $k=>$v){
            $params_array[]=$k.'='.urlencode($v);
        }
        $params_str=implode('&',$params_array);
        $url=$url.(strpos($url,'?')!==false ?'&':'?').$params_str;
        return $url;
    }

    /**
     * 添加菜单
     * 
     * @param string $url 链接
     * @param string $text 链接文字
     * @param int $icon 图标附件id
     * @param int $order 排序号
     * @param int $type 类型 1表示手机端2是pc端
     * 
     * @return bool false添加失败，true 添加成功 
     */
    public function addmenu($url,$text,$icon,$order,$type=1){
        $menu_model=new Menu_model();
        $menu=['link'=>$url,'title'=>$text,'icon'=>$icon,'ordernum'=>$order,'type'=>$type];
        $data=$menu_model->save($menu);
        return $data;
    }

    /**
     * 按照链接删除菜单
     * 
     * @param string $url 链接
     * 
     * @return bool false 删除失败 true 删除成功
     */
    public function delmenu($url){
        $menu_model=new Menu_model();
        $result=$menu_model->deleteByUrl($url);
        return $result;
    }
}