<?php
/**
 * 菜单模块model
 * PHP version 5.4+
 * 
 * @category Modules
 * 
 * @package Prize
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
namespace Modules\Menu\Models;
class Menu_model{
    var $_menu_m=null;
    public function __construct(){
        $this->_menu_m=new \M('menu');
    }

    public function getAll(){
        $data=$this->_menu_m->select(' 1=1 order by ordernum asc,id asc');
        if(!$data)return null;
        foreach($data as $k=>$v){
            $data[$k]['iconpath']=$this->_seticonpath($v['icon']);
        }
        return $data;
    }

    public function getById($id){
        $data=$this->_menu_m->find(' id='.$id.' limit 1');
        // $image=empty($data[''])
        if($data){
            $data['iconpath']=$this->_seticonpath($data['icon']);
            return $data;
        }
        return null;
    }

    public function deleteById($id){
        $result=$this->_menu_m->delete('id='.$id);
        return $result?true:false;
    }
    
    public function deleteByUrl($url){
        $result=$this->_menu_m->delete('link="'.$url.'"');
        return $result?true:false;
    }
    
    public function save($menu){
        $id=$menu['id'];
        unset($menu['id']);
        $result=false;
        if($id>0){
            $result=$this->_menu_m->update('id='.$id,$menu);
        }else{
            $result=$this->_menu_m->add($menu);
        }
        return $result;
    }

    private function _seticonpath($iconid){
        $image=empty($iconid)?'/Modules/Menu/templates/assets/images/aui-icon-question.png':$this->_getfilepath($iconid);
        return $image;
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
        $load=\Loader::getInstance();
        $load->model('Attachment_model');
        $attachmentinfo=$load->attachment_model->getById($attachmentid);
        return $attachmentinfo['filepath'];
    }
}