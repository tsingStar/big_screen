<?php

use Modules\Choujiang\Models\Choujiang_model;
use Modules\Menu\Controllers\Api;
require_once MODULE_PATH.DIRECTORY_SEPARATOR.'Adminbase.php';
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
     * 手机端抽奖游戏设置
     * 
     * @return void
     */
    public function index()
    {
        $this->setTitle('手机端抽奖游戏设置');
        $this->setDescription('手机端抽奖游戏,每个配置都是一个游戏,有独立的链接');
        $cj_model=new Choujiang_model();
        $configs=$cj_model->getAll();
        $themes=$cj_model->getAllThemes();
        $themes_hash=[];
        foreach($themes as $item){
            $themes_hash[$item['id']]=$item;
        }
        foreach($configs as $key=>$val){
            $configs[$key]=$this->_formatdata($val,$themes_hash);
        }
        $this->assign('themesjson',json_encode($themes_hash));
        $this->assign('configs', $configs);
        $this->show("index.html");
    }

    public function ajax_act_get_config(){
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        if($id<=0){
            $returndata=['code'=>-1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $cj_model=new Choujiang_model();
        $data=$cj_model->getById($id);
        if(!$data){
            $returndata=['code'=>-2,'message'=>'数据不存在'];
            echo json_encode($returndata);
            return;
        }
        $data['started_at']=date('Y-m-d H:i:s',$data['started_at']);
        $data['ended_at']=date('Y-m-d H:i:s',$data['ended_at']);
        $returndata=['code'=>1,'message'=>'','data'=>$data];
        echo json_encode($returndata);
        return;
    }

    public function ajax_act_save_config(){
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $title=isset($_POST['title'])?strval($_POST['title']):'';
        $defaultnum=isset($_POST['defaultnum'])?intval($_POST['defaultnum']):0;
        $themeid=isset($_POST['themeid'])?intval($_POST['themeid']):0;
        $started_at=isset($_POST['started_at'])?strval($_POST['started_at']):'';
        $ended_at=isset($_POST['ended_at'])?strval($_POST['ended_at']):'';
        $showleftnum=isset($_POST['showleftnum'])?strval($_POST['showleftnum']):2;
        if(empty($title)){
            $returndata=['code'=>-1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        if($defaultnum<=0 || $themeid<=0 || empty( $started_at) || empty($ended_at)){
            $returndata=['code'=>-1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $started_at=strtotime($started_at);
        $ended_at=strtotime($ended_at);
        if($ended_at<=$started_at){
            $returndata=['code'=>-1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $data=[
            'id'=>$id,
            'title'=>$title,
            'defaultnum'=>$defaultnum,
            'themeid'=>$themeid,
            'started_at'=>$started_at,
            'ended_at'=>$ended_at,
            'showleftnum'=>$showleftnum
        ];
        $cj_model=new Choujiang_model();
        $result=$cj_model->save($data);
        
        if($result){
            //同步一下菜单
            if($id==0){
                $menu_api=new Api();
                //13是默认的刮刮卡icon位置
                $menu_api->addmenu('/Modules/module.php?m=choujiang&c=mobile&a=index&id='.$result,'刮刮卡',13,1,1);
            }
            $returndata=['code'=>1,'message'=>'保存成功'];
            echo json_encode($returndata);
            return;
        }else{
            $returndata=['code'=>-2,'message'=>'保存失败'];
            echo json_encode($returndata);
            return;
        }
    }
    //重置游戏
    public function ajax_act_reset_config(){
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        if($id<=0){
            $returndata=['code'=>-1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        // $data=['id'=>$id];
        $cj_model=new Choujiang_model();
        $result=$cj_model->resetConfig($id);
        if($result){
            $returndata=['code'=>1,'message'=>'数据已经清空'];
            echo json_encode($returndata);
            return;
        }
    }
    //删除游戏
    public function ajax_act_delete_config(){
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        if($id<=0){
            $returndata=['code'=>-1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $cj_model=new Choujiang_model();
        $result=$cj_model->delete($id);
        if($result==false){
            $returndata=['code'=>-2,'message'=>'删除失败'];
            echo json_encode($returndata);
            return;
        }else{
            $menu_api=new Api();
            $menu_api->delmenu('/Modules/module.php?m=choujiang&c=mobile&a=index&id='.$id);
            $returndata=['code'=>1,'message'=>'删除成功'];
            echo json_encode($returndata);
            return;
        }
    }
    private function _formatdata($config,$themes_hash){
        $config['started_at']=date('Y-m-d H:i:s',$config['started_at']);
        $config['ended_at']=date('Y-m-d H:i:s',$config['ended_at']);
        $config['themename']=$themes_hash[$config['themeid']]['themename'];
        return $config;
    }
}