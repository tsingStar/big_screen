<?php
/**
 * 模块后台页面
 * PHP version 5.4+
 * 
 * @category Modules
 * 
 * @package Ydj
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
use \Modules\Ydj\Models\Ydj_model;
// echo dirname(__FILE__);
/**
 * 模块后台页面
 * PHP version 5.4+
 * 
 * @category Modules
 * 
 * @package Ydj
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
        $this->setTitle('摇大奖轮次设置');
        $this->setDescription('设置摇大奖的轮次');
        $ydj_model=new Ydj_model;
        $configs=$ydj_model->getAllConfig();
        $configs=$this->_formatconfigdata($configs);
        $themes=$ydj_model->getThemes();
        $this->assign('themes', $themes);
        $this->assign('configs', $configs);
        $this->show("index.html");
    }
    /**
     * 游戏结果
     * 
     * @return void
     */
    public function gamerecords()
    {
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $this->setTitle('摇大奖轮次编号'.$id.'的游戏结果');
        $this->setDescription('摇大奖游戏结果');
    }
    /**
     * 摇大奖主题设置
     * 
     * @return void
     */
    public function themes()
    {
        $this->setTitle('摇大奖主题列表');
        $this->setDescription('设置摇大奖的自定义主题');
        $ydj_model=new Ydj_model;
        // $this->_load->model('Ydj_model');
        $themes=$ydj_model->getThemes();
        $this->assign('themes', $themes);
        $this->show("themes.html");
    }
    /**
     * 主题详情
     * 
     * @return void
     */
    public function themedetail()
    {
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        $theme=array();
        if ($id>0) {
            // $this->_load->model('Ydj_model');
            $ydj_model=new Ydj_model;
            $theme=$ydj_model->getThemeById($id);
        }
        $this->setTitle('摇大奖主题设置');
        $this->setDescription('设置摇大奖的自定义主题');
        $this->assign('theme', $theme);
        $this->assign('themejson',json_encode($theme));
        $this->show("themedetail.html");
    }
    /**
     * 保存主题信息
     */
    public function savethemedetail(){
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        $themename=isset($_POST['themename'])?strval($_POST['themename']):'';
        //如果是添加的话必须要先加主题名称
        if($id==0 && $themename==''){
            $returndata=array('code'=>-3,'msg'=>"主题名称必须填写");
            echo json_encode($returndata);
            return;
        }
        $ydj_model=new Ydj_model();
        //添加一个主题
        if($id>=0 && $themename!=''){
            $themeinfo=array('themename'=>$themename,'id'=>$id);
            $result=$ydj_model->saveTheme($themeinfo);
            if($result){
                $returndata=array('code'=>1,'msg'=>"主题名称保存成功",'data'=>array('id'=>$result));
                echo json_encode($returndata);
                return;
            }
            $returndata=array('code'=>-1,'msg'=>"保存主题名称失败");
            echo json_encode($returndata);
            return;
        }

        if($id>0 && isset($_POST['mobileshakeimage'])){
            $themeinfo=array('id'=>$id,'themedata'=>'');
            $result=$ydj_model->saveTheme($themeinfo);
            if($result){
                $returndata=array('code'=>2,'msg'=>"手机端摇晃图片保存成功",'data'=>array('id'=>$result));
                echo json_encode($returndata);
                return;
            }
            $returndata=array('code'=>-2,'msg'=>"手机端摇晃图片保存失败");
            echo json_encode($returndata);
            return;
        }
        
        if($id>0 && !empty($_FILES['mobileshakeimage']['type'])){
            $imageid=$this->_uploadfile($_FILES['mobileshakeimage']);
            $themedata=array('mobileshakeimageid'=>$imageid);
            $themeinfo=array('id'=>$id,'themedata'=>serialize($themedata));
            $result=$ydj_model->saveTheme($themeinfo);
            if($result){
                $returndata=array('code'=>2,'msg'=>"手机端摇晃图片保存成功",'data'=>array('id'=>$result));
                echo json_encode($returndata);
                return;
            }
            $returndata=array('code'=>-2,'msg'=>"手机端摇晃图片保存失败");
            echo json_encode($returndata);
            return;
        }
        $returndata=array('code'=>-4,'msg'=>"");
        echo json_encode($returndata);
        return;
        
    }
    /**
 * 上传文件
 * 
 * @param file $file       上传的文件
 * @param text $allowtypes 可以上传的文件的mimetype类型
 * 
 * @return mixed 失败返回false，成功返回id
 */
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

    /**
     * 奖品列表
     * 
     * @return void
     */
    public function prizes()
    {
        
    }
    /**
     * 重置游戏
     * 
     * @return void
     */
    public function ajax_act_reset_config()
    {
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        if ($id<=0) {
            $returndata=array('code'=>-1,'message'=>"数据不存在");
            echo json_encode($returndata);
            return;
        }
        $ydj_model=new Ydj_model();
        $result=$ydj_model->resetGame($id);
        $returndata=array('code'=>1,'message'=>"重置完成");
        echo json_encode($returndata);
        return;
    }
    /**
     * 删除游戏
     * 
     * @return void
     */
    public function ajax_act_delete_config()
    {
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        if ($id<=0) {
            $returndata=array('code'=>-1,'message'=>"数据不存在");
            echo json_encode($returndata);
            return;
        }
        // $this->_load->model('Ydj_model');
        $ydj_model=new Ydj_model();
        $result1=$ydj_model->deleteRecords($id);
        $result2=$ydj_model->deleteConfig($id);
        $returndata=array('code'=>1,'message'=>"删除成功");
        echo json_encode($returndata);
        return;

    }
    /**
     * 获取摇大奖轮次配置信息
     * 
     * @return void
     */
    public function ajax_act_get_config()
    {
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $returndata=array();
        if ($id<=0) {
            $returndata=array('code'=>-1,'message'=>"数据不存在");
        } else {
            // $this->_load->model('Ydj_model');
            $ydj_model=new Ydj_model();
            $config=$ydj_model->getConfigById($id);
            if (!empty($config)) {
                $returndata=array('code'=>1,'message'=>"获得配置数据","data"=>$config);
            } else {
                $returndata=array('code'=>-1,'message'=>"数据不存在");
                
            }
        }
        
        echo json_encode($returndata);
        return;
        
    }
    /**
     * 保存轮次配置信息
     * 
     * @return void
     */
    public function ajax_act_save_config()
    {
        $config=array();
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        if ($id>0) {
            $config['id']=$id;
        }
        $config['duration']=isset($_POST['duration'])?intval($_POST['duration']):60;
        $config['duration']=$config['duration']<20?20:$config['duration'];
        $config['winningagain']
            = isset($_POST['winningagain'])?intval($_POST['winningagain']):1;
        $config['joinagain']
            = isset($_POST['joinagain'])?intval($_POST['joinagain']):2;
        $config['showstyle']
            = isset($_POST['showstyle'])?intval($_POST['showstyle']):1;
        $config['themeid']=1;

        $ydj_model=new Ydj_model();
        $id=$ydj_model->saveConfig($config);
        
        if ($id>0) {
            $returndata=array('code'=>1,'message'=>"保存成功");
        } else {
            $returndata=array('code'=>-1,'message'=>"保存失败");
        }
        echo json_encode($returndata);
        return;
    }
    /**
     * 格式化配置数据
     * 
     * @param array $configs 未格式的话数据
     * 
     * @return array 格式化后的数据
     */
    private function _formatconfigdata($configs)
    {
        $showconfigs=array();
        $statustext_arr=array('',"未开始","进行中","结束");
        $shakeshowstyletext_arr=array('','昵称','姓名','手机号');
        foreach ($configs as $k=>$v) {
            $showconfigs[$k]=$v;
            $showconfigs[$k]['statustext']=$statustext_arr[$v['status']];
            $showconfigs[$k]['showstyletext'] 
                = $shakeshowstyletext_arr[$v['showstyle']];
        }
        return $showconfigs;
    }
}