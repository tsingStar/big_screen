<?php
require_once('Page.php');
class QiandaoSettings extends Page{
	function show(){

		$this->_load->model('System_Config_model');

		$data=$this->_load->system_config_model->get("signnameshowstyle");
		$signnameshowstyle=intval($data['configvalue']);

		$data=$this->_load->system_config_model->get("qiandaosignname");
		$qiandaosignname=intval($data['configvalue']);

		$data=$this->_load->system_config_model->get("qiandaophone");
		$qiandaophone=intval($data['configvalue']);

		$data=$this->_load->system_config_model->get("qiandaoshenhe");
		$qiandaoshenhe=intval($data['configvalue']);

		$this->_load->model('Flag_model');
		$extentioncolumns=$this->_load->flag_model->getExtentionColumns();
		$coltypetext_arr=array('select'=>'选择框','text'=>'文本');
		$ismust_arr=array('1'=>'选填','2'=>'必填');

		foreach($extentioncolumns as $k=>$v){
			$extentioncolumns[$k]['coltypetext']=$coltypetext_arr[$v['coltype']];
			$extentioncolumns[$k]['ismusttext']=$ismust_arr[$v['ismust']];
			if($v['coltype']=='select'){
				$options=unserialize($v['options']);
				foreach($options as $v){
					$extentioncolumns[$k]['optionstext'].=$v.'<br>';
				}
			}
		}
		$flagconfig=$this->_load->flag_model->getConfig();

		$reservedinfomation=$this->_load->flag_model->getAllReservedInfomation();
		//是否需要手动审核签到 1表示不需要审核2需要审核
		$this->assign('flagconfig',$flagconfig);
		//是否需要手动审核签到 1表示不需要审核2需要审核
		$this->assign('qiandaoshenhe',$qiandaoshenhe);
		//签到是否需要填写姓名 1必须2不需要
		$this->assign('qiandaosignname',$qiandaosignname);
		//签到是否需要填写手机号 1必须2不需要
		$this->assign('qiandaophone',$qiandaophone);
		//签到人名显示方式 1昵称2姓名3手机号
		$this->assign('signnameshowstyle',$signnameshowstyle);
		$this->assign('reservedinfomation',$reservedinfomation);
		//扩展字段
		$this->assign('extentioncolumns',$extentioncolumns);
		$this->display('templates/qiandaosettings.html');
	}
}
$page=new QiandaoSettings();
$page->setTitle('签到设置');
$page->show();
