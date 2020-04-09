<?php
/**
 * 抽奖箱
 */
namespace Modules\Lottery\Models\Themes;
require BASEPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'attachment_model.php';
class Cjx implements ThemeInterface{

    var $_data=['prizefontcolor' => '#ffffff', 'fontcolor' => '#ffffff', 
        'winnerfontcolor' => '#ffffff', 'leftcolor' => 'rgba(0,0,0,.5)', 
        'winnercolor' => 'rgba(237, 15, 5, 0.38)',
        'bg'=>0,'bg_path'=>'/wall/themes/meepo/assets/images/defaultbg.jpg','bgmusic'=>0,'bgmusic_path'=>'/wall/themes/meepo/assets/music/Radetzky_Marsch.mp3','bgmusic_switch'=>1
    ];
    public function __construct(){
        $this->_attachment_model=new \Attachment_model();
    }
    /**
     *必须传入themeconfig的全部值,不然会导致没有传入值被初始化成默认值
     *   
     **/
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