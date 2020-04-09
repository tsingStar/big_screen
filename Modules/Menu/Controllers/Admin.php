<?php
/**
 * 奖品模块后台页面
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


require_once MODULE_PATH.DIRECTORY_SEPARATOR.'Adminbase.php';
require_once BASEPATH.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'CacheFactory.php';
require_once BASEPATH.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'url_helper.php';

use \Modules\Menu\Models\Menu_model;
/**
 * 奖品模块后台页面
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
class Admin extends Adminbase
{
    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 游戏轮次设置
     * 
     * @return void
     */
    public function index()
    {
        $this->setTitle('手机端菜单');
        $this->setDescription('手机端的菜单设置');
        $menu_model=new Menu_model();
        $data=$menu_model->getAll();
        // $data=$this->_formatdata($data);
        $this->assign('data',$data);
        $this->show("index.html");
    }
    //获取菜单信息
    public function ajax_act_get_menu(){
         $id=isset($_GET['id'])?intval($_GET['id']):0;
         if($id<=0){
             $returndata=array('code'=>-1,'message'=>"数据错误",'data'=>[]);
         }
         $menu_model=new Menu_model();
         $menu=$menu_model->getById($id);
         $returndata=array('code'=>1,'message'=>"获取成功",'data'=>$menu);
         echo json_encode($returndata);
         return ;
    }
    
    public function ajax_act_delete_menu(){
        $id=isset($_GET['id'])?intval($_GET['id']):0;
         if($id<=0){
             $returndata=array('code'=>-1,'message'=>"数据错误",'data'=>[]);
             echo json_encode($returndata);
             return ;
         }
        $menu_model=new Menu_model();
        $return=$menu_model->deleteById($id);
        $returndata=[];
        if($return){
            $returndata=array('code'=>1,'message'=>"获取成功");
        }else{
            $returndata=array('code'=>-2,'message'=>"删除失败");
        }
        echo json_encode($returndata);
        return ;
    }
    public function ajax_act_save_menu(){
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $ordernum=isset($_POST['ordernum'])?intval($_POST['ordernum']):0;
        $title=isset($_POST['title'])?strval($_POST['title']):'';
        $link=isset($_POST['link'])?strval($_POST['link']):'';
        $returndata=[];
        if($ordernum<=0 || $id<0){
            $returndata=['code'=>-1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return ;
        }
        $strlen=mb_strlen($title);
        if($strlen<=0 || $strlen>4){
            $returndata=['code'=>-1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return ;
        }
        $linklen=mb_strlen($link);
        if($linklen<=0 || check_url($link)==false){
            $returndata=['code'=>-1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return ;
        }

        $file=$_FILES['iconpath'];
        $imageid=0;
        if(!empty($file['type'])){
            $imageid=$this->_uploadfile($file);
        }else{
            $imageid=isset($_POST['icon'])?intval($_POST['icon']):0;
        }
        $data=[
            'id'=>$id,
            'title'=>$title,
            'icon'=>$imageid,
            'link'=>$link,
            'ordernum'=>$ordernum,
            'type'=>1
        ];
        $menu_model=new Menu_model();
        $result=$menu_model->save($data);
        if($result){
            $returndata=['code'=>1,'message'=>'保存成功'];
        }else{
            $returndata=['code'=>-2,'message'=>'保存失败，请重新试试一次'];
        }
        echo json_encode($returndata);
        return ;
    }
    function _uploadfile($file,$allowtypes='image/jpg,image/jpeg,image/png,image/gif')
    {
        if (!empty($file['type'])) {
            //上传的文件
            $this->_load->model('Attachment_model');
            $savedfile=$this->_load->attachment_model->saveFormFile($file, $allowtypes);
            if ($savedfile) {
                return $savedfile['id'];
            }
        }
        return 0;
    }
}