<?php
namespace Modules\Game\Models\Themes;
require_once BASEPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'attachment_model.php';
/**
 * 赛龙舟
 */
class Horse{
    var $_themeconfig;
    var $_attachment_model;
    var $_data=[
        'durationtype'=>1,
        'duration'=>60,
        'bgmusic'=>0,
        'bgmusic_switch'=>1,
        'mobilebg'=>0,
        'mobileimg'=>0,
    ];
    public function __construct(){
        $this->_attachment_model=new \Attachment_model();
        $this->setDefault();
    }
    private function setDefault(){
        $assetpath='/Modules/Game/templates/front/horse/assets/';
        $this->_data['bgmusic_path']=$assetpath.'music/defaultmusic.mp3';
        $this->_data['mobilebg_path']=$assetpath.'images/mobilebg.jpg';
        $this->_data['mobileimg_path']=$assetpath.'images/mobileimg.png';
    }
    public function data($arr){
        if(empty($arr) || !is_array($arr)){
            return;
        }
        if(isset($arr['bgmusic']) ){
            $arr['bgmusic_path']=$this->_defaultImagePath(intval($arr['bgmusic']),$this->_data['bgmusic_path']);
        }
        if(isset($arr['mobilebg']) ){
            $arr['mobilebg_path']=$this->_defaultImagePath(intval($arr['mobilebg']),$this->_data['mobilebg_path']);
        }
        if(isset($arr['mobileimg']) ){
            $arr['mobileimg_path']=$this->_defaultImagePath(intval($arr['mobileimg']),$this->_data['mobileimg_path']);
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