<?php
class Background_model{
    var $_backgroundimage_m=null;
    public function __construct(){
        $this->_background_m=new M('background');
    }
    //设置背景图
    public function setBackground($data){
        $imageid=$data['attachmentid'];
        $plugname=$data['plugname'];
        $type=isset($data['bgtype'])?intval($data['bgtype']):1;
        $result=$this->_background_m->update('plugname="'.$plugname.'"',array('attachmentid'=>$imageid,'bgtype'=>$type));
        return $result;
    }
    public function getAll(){
        $images=$this->_background_m->select('1');
        foreach($images as $key=>$val){
            $images[$key]['attachmentpath']=$this->_setbackgroundpath($val['attachmentid']);
            $images[$key]['bgtype']=$val['bgtype'];
        }
        return $images;
    }

    public function getBackgroundJson(){
        $image_arr=array();
        $data=$this->getAll();
        foreach($data as $val){
            $image_arr[$val['plugname']]=array("path"=>$val['attachmentpath'],'bgtype'=>$val['bgtype']);
        }
        return json_encode($image_arr);
    }
    //设置音乐路径
    private function _setbackgroundpath($attachmentid=0,$bgtype=1){
        if(empty($attachmentid)){
            return '/wall/themes/meepo/assets/images/defaultbg.jpg';
        }
        $load=Loader::getInstance();
        $load->model('Attachment_model');
        $attachmentinfo=$load->attachment_model->getById($attachmentid);
        return $attachmentinfo['filepath'];
    }
}