<?php
/**
 * 模块前台页面
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
require_once MODULE_PATH.DIRECTORY_SEPARATOR.'Frontbase.php';
require_once BASEPATH.DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."function.php";
/**
 *  模块前台页面
 *  PHP version 5.4+
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
require_once BASEPATH .DIRECTORY_SEPARATOR. 'common'.DIRECTORY_SEPARATOR.'function.php';
use \Modules\Importlottery\Models\Importlottery_model;
use \Modules\Importlottery\Models\ImportlotteryConfig_model;
use \Modules\Prize\Controllers\Api;

class Front extends Frontbase
{
    var $_importlottery_model=null;
    public function __construct(){
        parent::__construct();
        $this->_importlottery_model=new Importlottery_model();
    }
    /**
     * 摇大奖界面
     * 
     * @return void
     */
    public function index()
    {   
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $importlotteryconfig_model = new ImportlotteryConfig_model();
        if ($id <= 0) {
            $id = $importlotteryconfig_model->getCurrent();
        }
        $importlotteryconfig= $importlotteryconfig_model->getById($id, true, true);
        $importlotteryconfig_model->setCurrent($importlotteryconfig['id']);
        $lotteryconfigs=$importlotteryconfig_model->getAll('id,title,themeid');
        $prize_api=new Api();
        $prizes=$prize_api->getprizes('importlottery',$importlotteryconfig['id']);
        $prizesdata=[];
        if($prizes['code']>0){
            foreach($prizes['data'] as $v){
                $prizesdata[]=$v;
            }
        }
        $this->assign('prizesjson',json_encode($prizesdata));
        $this->assign('configs',json_encode($lotteryconfigs));
        $this->assign('config',$importlotteryconfig);
        $this->assign('title', '导入抽奖');
        $this->show($importlotteryconfig['themepath'].DIRECTORY_SEPARATOR.'/index.html', true);
    }
    public function ajaxGetRandData(){
        $importlotteryconfig_model = new ImportlotteryConfig_model();
        $configid = $importlotteryconfig_model->getCurrent();
        $data=$this->_importlottery_model->getRandData(20,$configid);
        if(!$data){
            $returndata=array('code'=> -1,'message'=>'没有导入的数据，请先到后台导入数据');
            echo json_encode($returndata);
            return;
        }
        $returndata=array('code'=> 1,'message'=>'','data'=>$data);
        echo json_encode($returndata);
        return;
    }
    public function ajax_act_get_ready(){
        $prizeid=isset($_GET['prizeid'])?intval($_GET['prizeid']):0;
        if($prizeid<=0){
            $returndata=array('code'=>-1,'message'=>'信息错误');
            echo json_encode($returndata);
            return;
        }
        $prize_api=new Api();
        $prizeinfo=$prize_api->getprizeinfo($prizeid);
        $count=$this->_importlottery_model->getLeftDataCount($prizeid,$prizeinfo['data']['activityid']);
        $winners=$this->_importlottery_model->getWinners($prizeid);
        $returndata=array('code'=>1,'message'=>'','data'=>array('count'=>$count,'prizenum'=>$prizeinfo['data']['freezenum']+$prizeinfo['data']['leftnum'],'winners'=>$winners));
        echo json_encode($returndata);
        return;
    }

    public function ajax_act_get_result(){
        $num=isset($_GET['num'])?intval($_GET['num']):0;
        $prizeid=isset($_GET['prizeid'])?intval($_GET['prizeid']):0;
        if($num<=0){
            $returndata=array('code'=>-1,'message'=>'信息错误');
            echo json_encode($returndata);
            return;
        }
        if($prizeid<=0){
            $returndata=array('code'=>-2,'message'=>'信息错误');
            echo json_encode($returndata);
            return;
        }
        $importlotteryconfig_model = new ImportlotteryConfig_model();
        $configid = $importlotteryconfig_model->getCurrent();
        $prize_api=new Api();
        $prizeinfo=$prize_api->getprizeinfo($prizeid);
        if($prizeinfo['code']<0){
            $returndata=['code'=>-2,'message'=>'奖品信息有误'];
            echo json_encode($returndata);
            return;
        }else{
            $left=$prizeinfo['data']['freezenum']+$prizeinfo['data']['leftnum'];
            if($num>$left){
                $returndata=['code'=>-3,'message'=>'奖品数量不足'];
                echo json_encode($returndata);
                return;
            }
        }
        
        $data=$this->_importlottery_model->getRandZjlist($num,$prizeid,$configid);
        $winners=[];
        foreach($data as $k=>$v){
            $winners[]=$v;
        }
        $result=$prize_api->winprizebatch('importlottery',$configid,$data,$prizeid,'导入抽奖');
        $returndata=['code'=> -1,'message'=>'失败'];
        if($result['code']>0){
            $returndata=['code'=>1,'message'=>'','data'=>$winners];
        }
        echo json_encode($returndata);
        return;
    } 
    public function ajaxGetAllResult(){
        $importlotteryconfig_model = new ImportlotteryConfig_model();
        $configid = $importlotteryconfig_model->getCurrent();
        $importlottery_model=new Importlottery_model();
        $data=$importlottery_model->getAllWinners($configid);
        $returndata=['code'=>-1,'message'=>''];
        if($data){
            $returndata=['code'=>1,'message'=>'','data'=>$data];
        }
        echo json_encode($returndata);
        return;
    }
}