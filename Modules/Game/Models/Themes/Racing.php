<?php
namespace Modules\Game\Models\Themes;
require_once BASEPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'attachment_model.php';


class Racing{
    var $_themeconfig;
    var $_attachment_model;
    var $_data=[
        'durationtype'=>1,
        'duration'=>60,
        'bg'=>0,
        'bgmusic'=>0,
        'bgmusic_switch'=>1,
        // 'mobilebg'=>0,
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
        'startline'=>0,
        'endline'=>0,
        'oddtrack'=>0,
        'eventrack'=>0,
        'slogan'=>'',
    ];
    public function __construct(){
        $this->_attachment_model=new \Attachment_model();
        $this->setDefault();
    }
    private function setDefault(){
        $assetpath='/Modules/Game/templates/front/racing/assets/';
        $this->_data['bg_path']=$assetpath.'images/defaultbg.jpg';
        $this->_data['bgmusic_path']=$assetpath.'images/defaultmusic.mp3';
        // $this->_data['mobilebg_path']='';
        $this->_data['mobileimg_path']=$assetpath.'images/mobileimg.png';
        $this->_data['avatar_0_path']=$assetpath.'images/avatar1.png';
        $this->_data['avatar_1_path']=$assetpath.'images/avatar2.png';
        $this->_data['avatar_2_path']=$assetpath.'images/avatar3.png';
        $this->_data['avatar_3_path']=$assetpath.'images/avatar4.png';
        $this->_data['avatar_4_path']=$assetpath.'images/avatar5.png';
        $this->_data['avatar_5_path']=$assetpath.'images/avatar6.png';
        $this->_data['avatar_6_path']=$assetpath.'images/avatar7.png';
        $this->_data['avatar_7_path']=$assetpath.'images/avatar8.png';
        $this->_data['avatar_8_path']=$assetpath.'images/avatar9.png';
        $this->_data['avatar_9_path']=$assetpath.'images/avatar10.png';
        $this->_data['startline_path']=$assetpath.'images/xxx.png';
        $this->_data['endline_path']=$assetpath.'images/zd.png';
        $this->_data['oddtrack_path']=$assetpath.'images/oddtrack.png';
        $this->_data['eventrack_path']=$assetpath.'images/eventrack.png';
        $this->_data['slogan']=['加油!','加油!加油!','大力摇!大力摇!'];
    }
    public function data($arr){
        if(empty($arr) || !is_array($arr)){
            return;
        }
        if(isset($arr['bg']) ){
            $arr['bg_path']=$this->_defaultImagePath(intval($arr['bg']),$this->_data['bg_path']);
        }
        if(isset($arr['bgmusic']) ){
            $arr['bgmusic_path']=$this->_defaultImagePath(intval($arr['bgmusic']),$this->_data['bgmusic_path']);
        }
        // if(isset($arr['mobilebg']) ){
        //     $arr['mobilebg_path']=$this->_defaultImagePath(intval($arr['mobilebg']),$this->_data['mobilebg_path']);
        // }
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
        if(isset($arr['startline']) ){
            $arr['startline_path']=$this->_defaultImagePath(intval($arr['startline']),$this->_data['startline_path']);
        }
        if(isset($arr['endline']) ){
            $arr['endline_path']=$this->_defaultImagePath(intval($arr['endline']),$this->_data['endline_path']);
        }
        if(isset($arr['oddtrack']) ){
            $arr['oddtrack_path']=$this->_defaultImagePath(intval($arr['oddtrack']),$this->_data['oddtrack_path']);
        }
        if(isset($arr['eventrack']) ){
            $arr['eventrack_path']=$this->_defaultImagePath(intval($arr['eventrack']),$this->_data['eventrack_path']);
        }
        if(isset($arr['slogan'])){
            if(!is_array($arr['slogan'])){
                $arr['slogan']=trim($arr['slogan']);
                $arr['slogan']=explode("\n",$arr['slogan']);
            }
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
        unset($this->_data['bg_path']);
        unset($this->_data['bgmusic_path']);
        // unset($this->_data['mobilebg_path']);
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
        unset($this->_data['startline_path']);
        unset($this->_data['endline_path']);
        unset($this->_data['oddtrack_path']);
        unset($this->_data['eventrack_path']);
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