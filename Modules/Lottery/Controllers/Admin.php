<?php
require_once MODULE_PATH.DIRECTORY_SEPARATOR.'Adminbase.php';
require_once BASEPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'flag_model.php';
use Modules\Lottery\Models\LotteryConfig_model;
use Modules\Lottery\Models\LotteryThemes_model;
use Modules\Prize\Controllers\Api;

class Admin extends Adminbase{
    protected $_flag_model;//=new Flag_model();
    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_flag_model=new Flag_model();
    }
    /**
     * 导入信息抽奖设置
     * 
     * @return void
     */
    public function index()
    {
        $this->setTitle('抽奖设置');
        $this->setDescription('');
        $lotteryconfig_model=new LotteryConfig_model();
        $data=$lotteryconfig_model->getAll();
        $lotterythemes_model=new LotteryThemes_model();
        $themes=$lotterythemes_model->getAll();
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        $lotteryconfig=$lotteryconfig_model->getById($id,true);
        $this->assign('lotteryconfig',$lotteryconfig);
        $this->assign('themes',$themes);
        $this->assign('data',$data);
        $this->assign('currentid',$lotteryconfig['id']);
        $this->show("index.html");
    }
    public function ajaxSaveLotteryConfig(){
        $params=$_POST;
        $data=[];
        $data['id']=isset($_POST['id'])?intval($_POST['id']):0;
        $data['title']=isset($_POST['title'])?strval($_POST['title']):'';
        $data['themeid']=isset($_POST['themeid'])?intval($_POST['themeid']):1;
        $data['winagain']=isset($_POST['winagain'])?intval($_POST['winagain']):1;
        $data['showtype']=isset($_POST['showtype'])?strval($_POST['showtype']):'nickname';
        
        if(empty($data['title'])){
            $returndata=['code'=> -1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        if(!in_array($data['winagain'],[1,2])){
            $returndata=['code'=> -1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        if(!in_array($data['showtype'],['nickname','signname','phone'])){
            $returndata=['code'=> -1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }

        $lotteryconfig_model=new LotteryConfig_model();
        $result=$lotteryconfig_model->save($data);
        if($result!==false){
            $returndata=['code'=>1,'message'=>'保存成功','data'=>['id'=>$result]];
            echo json_encode($returndata);
            return;
        }
        $returndata=['code'=>-2,'message'=>'保存失败'];
        echo json_encode($returndata);
        return;
    }
    //获取主题设置
    public function ajaxGetThemeSettings(){
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $lotteryconfig_model=new LotteryConfig_model();
        $lotteryconfig=$lotteryconfig_model->getById($id,true);
        $this->assign('settings',json_encode($lotteryconfig['themeconfig']));
        $this->show($lotteryconfig['themepath']."/snippet_themeconfig.html");
    }
    //保存主题的设置
    public function ajaxSaveThemeSettings(){
        $settings=[];
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        if($id<=0){
            $returndata=['code'=> -2,"message"=>'数据格式错误'];
            echo json_encode($returndata);
            return ;
        }
        $lotteryconfig_model=new LotteryConfig_model();
        $data=['id'=>$id,'themeconfig'=>$_POST];
        $return=$lotteryconfig_model->save($data);
        $returndata=['code'=> -1,'message'=>'保存失败'];
        if($return){
            $returndata=['code'=>1,'message'=>'保存成功'];
        }
        echo json_encode($returndata);
        return ;
    }
    public function ajaxSaveThemeConfigFile(){
        $id=isset($_POST['configid'])?intval($_POST['configid']):0;
        $key=isset($_POST['key'])?strval($_POST['key']):'';
        $val=isset($_POST['val'])?intval($_POST['val']):0;
        if($id<=0 || $key==''){
            $returndata=['code'=> -2,"message"=>'数据格式错误'];
            echo json_encode($returndata);
            return ;
        }
        $lotteryconfig_model=new LotteryConfig_model();
        $lotteryconfig=$lotteryconfig_model->getById($id,true);
        if($_FILES['file']){
            if($_FILES['file']['error']==1){
                $returndata=['code'=> -3,'message'=>'文件太大了'];
                echo json_encode($returndata);
                return ;
            }
            $allowtypes='image/jpg,image/jpeg,image/png,video/mp4,audio/mp3';
            $this->_load->model('Attachment_model');
            $file=$this->_load->attachment_model->saveFormFile($_FILES['file'],$allowtypes);
            if($file==false){
                $returndata=['code'=> -2,'message'=>'文件上传失败'];
                echo json_encode($returndata);
                return ;
            }
            $lotteryconfig['themeconfig'][$key]=$file['id'];
        }else{
            $lotteryconfig['themeconfig'][$key]=0;
        }
        $data=['id'=>$id,'themeconfig'=>$lotteryconfig['themeconfig']];
        $return=$lotteryconfig_model->save($data);
        $returndata=['code'=> -1,'message'=>'保存失败'];
        if($return){
            $returndata=['code'=>1,'message'=>'保存成功'];
        }
        echo json_encode($returndata);
        return ;
    }
    public function ajaxGetPrizes(){
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $lotteryconfig_model=new LotteryConfig_model();
        $lotteryconfig=$lotteryconfig_model->getById($id,false);
        $prize_api=new Api();
        $prizesdata=$prize_api->getprizes('lottery',$lotteryconfig['id']);
        $prizes=[];
        if($prizesdata['code']>0){
            $prizes=$prizesdata['data'];
        }
        $this->assign('prizes',$prizes);
        $this->show("snippets/prizes.html");
    }

    public function ajaxDelPrize(){
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $prize_api=new Api();
        $prizesdata=$prize_api->deletePrize($id);
        $returndata=['code'=> -1,'message'=>'保存失败'];
        if($prizesdata['code']>0){
            $returndata=['code'=>1,'message'=>'删除成功','data'=>['id'=>$id]];
        }
        echo json_encode($returndata);
        return;
    }
    
    public function ajaxGetWinners(){
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $lotteryconfig_model=new LotteryConfig_model();
        $lotteryconfig=$lotteryconfig_model->getById($id,false);
        $prize_api=new Api();
        $winnersdata=$prize_api->getwinners('lottery',$lotteryconfig['id']);
        $winners=[];
        if($winnersdata['code']>0){
            $winners=$winnersdata['data'];
        }
        $this->assign('winners',$winners);
        $this->show("snippets/winners.html");
    }
    public function ajaxGetDesignated(){
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $lotteryconfig_model=new LotteryConfig_model();
        $lotteryconfig=$lotteryconfig_model->getById($id,false);
        $prize_api=new Api();
        $designated=$prize_api->getdesignatedlist('lottery',$lotteryconfig['id']);
        $prizesdata=$prize_api->getprizes('lottery',$lotteryconfig['id']);
                

        $prizes=[];
        if($prizesdata['code']>0){
            $prizes=$prizesdata['data'];
        }
        $this->assign('prizes',$prizes);
        
        foreach($designated as $key=>$val){
            $designated[$key]=$this->_getUserInfo($val);
        }
        $this->assign('designated',$designated);
        $this->show("snippets/designated.html");
    }
    /**
     * 删除抽奖配置
     * 需要同时删除奖品设置 中奖名单等
     */
    public function ajaxDelLotteryConfig(){
        $params=$_POST;
        $data=[];
        $data['id']=isset($_POST['id'])?intval($_POST['id']):0;
        if($data<=0){
            $returndata=['code'=> -1,'message'=>'数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $lotteryconfig_model=new LotteryConfig_model();
        $result=$lotteryconfig_model->del($data['id']);
        $prize_api=new Api();
        //删除对应的奖项 ,删除对应的获奖及内定名单
        $prize_api->delUserPrizeByActivityId('lottery',$data['id']);
        if($result){
            $returndata=['code'=>1,'message'=>'删除成功'];
            echo json_encode($returndata);
            return;
        }else{
            $returndata=['code'=>-2,'message'=>'删除失败'];
            echo json_encode($returndata);
            return;
        }
    }
    private function _getUserInfo($item){
        $item['userinfo']=$this->_flag_model->getUserinfoById($item['userid']);
        return $item;
    }
}