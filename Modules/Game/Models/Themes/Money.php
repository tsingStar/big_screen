<?php
namespace Modules\Game\Models\Themes;
require_once BASEPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'attachment_model.php';
class Money{
    var $_themeconfig;
    var $_attachment_model;
    var $_data=[
        'duration'=>60,
        'bg'=>0,
        'bgmusic'=>0,
        'bgmusic_switch'=>1,
    ];
    public function __construct(){
        $this->_attachment_model=new \Attachment_model();
        $this->setDefault();
    }
    private function setDefault(){
        $assetpath='/Modules/Game/templates/front/money/assets/';
        $this->_data['bg_path']=$assetpath.'images/money_bg.jpg';
        $this->_data['bgmusic_path']=$assetpath.'music/defaultmusic.mp3';
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