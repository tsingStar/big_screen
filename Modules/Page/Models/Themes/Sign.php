<?php
/**
 * 开盘摇号主题
 */
namespace Modules\Page\Models\Themes;
require_once BASEPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'attachment_model.php';
class Sign implements ThemeInterface{
    var $_data=[
        'img'=>0,'img_path'=>'',
        'bg'=>0,'bg_path'=>'/wall/themes/meepo/assets/images/defaultbg.jpg','bgmusic'=>0,'bgmusic_path'=>'/wall/themes/meepo/assets/music/Radetzky_Marsch.mp3','bgmusic_switch'=>1,
        'signs'=>[['pos'=>['x'=>0,'y'=>0,'w'=>5,'h'=>5],'img'=>0,'path'=>''],['pos'=>['x'=>0,'y'=>0,'w'=>5,'h'=>5],'img'=>0,'path'=>'']]
    ];
    //selectall 1表示抽取一个  2表示默认抽取全部
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
        if(isset($arr['signnum'])){
            $arr['signs']=array_slice($arr['signs'],0,$arr['signnum']);
            $default=['pos'=>['x'=>0,'y'=>0,'w'=>5,'h'=>5],'img'=>0,'path'=>''];
            for($i=0,$l=$arr['signnum'];$i<$l;$i++){
                if(!isset($arr['signs'][$i])){
                    $arr['signs'][$i]=$default;
                }else{
                    if($arr['signs'][$i]['img']>0){
                        $arr['signs'][$i]['path']=$this->_defaultImagePath(intval($arr['signs'][$i]['img']),'');
                    }
                }
            }
            unset($arr['signnum']);
        }else{
            for($i=0,$l=count($arr['signs']);$i<$l;$i++){
                if($arr['signs'][$i]['img']>0){
                    $arr['signs'][$i]['path']=$this->_defaultImagePath(intval($arr['signs'][$i]['img']),'');
                }
            }
        }
        
        if(isset($arr['img'])){
            $arr['img_path']=$this->_defaultImagePath(intval($arr['img']),$this->_data['img_path']);
        }
        if(isset($arr['bg'])){
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