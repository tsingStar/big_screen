<?php
namespace Modules\Game\Models\Themes;
require_once BASEPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'attachment_model.php';
/**
 * 赛龙舟
 */
class Yacht{
    var $_themeconfig;
    var $_attachment_model;
    var $_data=[
        'durationtype'=>1,
        'duration'=>60,
        'bgmusic'=>0,
        'bgmusic_switch'=>1,
        'mobilebg'=>0,
        'mobileimg'=>0,
        'avatar_0'=>0,
        'avatar_1'=>0,
        'avatar_2'=>0,
        'avatar_3'=>0,
        'avatar_4'=>0,
        'avatar_5'=>0,
        'avatar_6'=>0,
        'avatar_7'=>0,
        'avatar_8'=>0,
        'avatar_9'=>0,

    ];
    public function __construct(){
        $this->_attachment_model=new \Attachment_model();
        $this->setDefault();
    }
    private function setDefault(){
        $assetpath='/Modules/Game/templates/front/yacht/assets/';
        $this->_data['bgmusic_path']=$assetpath.'music/defaultmusic.mp3';
        $this->_data['mobilebg_path']=$assetpath.'images/mobilebg.jpg';
        $this->_data['mobileimg_path']=$assetpath.'images/mobileimg.png';
        $this->_data['avatar_0_path']=$assetpath.'images/user0.png';
        $this->_data['avatar_1_path']=$assetpath.'images/user1.png';
        $this->_data['avatar_2_path']=$assetpath.'images/user2.png';
        $this->_data['avatar_3_path']=$assetpath.'images/user3.png';
        $this->_data['avatar_4_path']=$assetpath.'images/user4.png';
        $this->_data['avatar_5_path']=$assetpath.'images/user5.png';
        $this->_data['avatar_6_path']=$assetpath.'images/user6.png';
        $this->_data['avatar_7_path']=$assetpath.'images/user7.png';
        $this->_data['avatar_8_path']=$assetpath.'images/user8.png';
        $this->_data['avatar_9_path']=$assetpath.'images/user9.png';
    }
    public function data($arr){
        if(empty($arr) || !is_array($arr)){
            return;
        }
        // echo var_export($arr);
        if(isset($arr['bgmusic']) ){
            $arr['bgmusic_path']=$this->_defaultImagePath(intval($arr['bgmusic']),$this->_data['bgmusic_path']);
        }
        if(isset($arr['mobilebg']) ){
            $arr['mobilebg_path']=$this->_defaultImagePath(intval($arr['mobilebg']),$this->_data['mobilebg_path']);
        }
        if(isset($arr['mobileimg']) ){
            $arr['mobileimg_path']=$this->_defaultImagePath(intval($arr['mobileimg']),$this->_data['mobileimg_path']);
        }

        if(isset($arr['avatar_0']) ){
            $arr['avatar_0_path']=$this->_defaultImagePath(intval($arr['avatar_0']),$this->_data['avatar_0_path']);
        }
        if(isset($arr['avatar_1']) ){
            $arr['avatar_1_path']=$this->_defaultImagePath(intval($arr['avatar_1']),$this->_data['avatar_1_path']);
        }
        if(isset($arr['avatar_2']) ){
            $arr['avatar_2_path']=$this->_defaultImagePath(intval($arr['avatar_2']),$this->_data['avatar_2_path']);
        }
        if(isset($arr['avatar_3']) ){
            $arr['avatar_3_path']=$this->_defaultImagePath(intval($arr['avatar_3']),$this->_data['avatar_3_path']);
        }
        if(isset($arr['avatar_4']) ){
            $arr['avatar_4_path']=$this->_defaultImagePath(intval($arr['avatar_4']),$this->_data['avatar_4_path']);
        }
        if(isset($arr['avatar_5']) ){
            $arr['avatar_5_path']=$this->_defaultImagePath(intval($arr['avatar_5']),$this->_data['avatar_5_path']);
        }
        if(isset($arr['avatar_6']) ){
            $arr['avatar_6_path']=$this->_defaultImagePath(intval($arr['avatar_6']),$this->_data['avatar_6_path']);
        }
        if(isset($arr['avatar_7']) ){
            $arr['avatar_7_path']=$this->_defaultImagePath(intval($arr['avatar_7']),$this->_data['avatar_7_path']);
        }
        if(isset($arr['avatar_8']) ){
            $arr['avatar_8_path']=$this->_defaultImagePath(intval($arr['avatar_8']),$this->_data['avatar_8_path']);
        }
        if(isset($arr['avatar_9']) ){
            $arr['avatar_9_path']=$this->_defaultImagePath(intval($arr['avatar_9']),$this->_data['avatar_9_path']);
        }

        foreach($this->_data as $k=>$v){
            $this->_data[$k]=isset($arr[$k])?$arr[$k]:$v;
        }
        
    }

    public function toArray(){
        return $this->_data;
    }
    public function toJson(){
        return json_encode($this->_data);
    }

    public function serialize(){
        unset($this->_data['bgmusic_path']);
        unset($this->_data['mobilebg_path']);
        unset($this->_data['mobileimg_path']);
        unset($this->_data['avatar_0_path']);
        unset($this->_data['avatar_1_path']);
        unset($this->_data['avatar_2_path']);
        unset($this->_data['avatar_3_path']);
        unset($this->_data['avatar_4_path']);
        unset($this->_data['avatar_5_path']);
        unset($this->_data['avatar_6_path']);
        unset($this->_data['avatar_7_path']);
        unset($this->_data['avatar_8_path']);
        unset($this->_data['avatar_9_path']);
        return serialize($this->_data);
    }

    private function _defaultImagePath($imgid,$defaultpath){
        if($imgid<=0){
            return $defaultpath;
        }else{
            $file=$this->_attachment_model->getById($imgid);
            if($file){
                return $file['filepath'];
            }else{
                return $defaultpath;
            }
        }
    }
}