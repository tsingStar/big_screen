<?php
@header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/CacheFactory.php');
require_once(dirname(__FILE__) . '/../common/function.php');
require_once(dirname(__FILE__) . '/../models/system_config_model.php');
require_once(dirname(__FILE__) . '/../common/session_helper.php');
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
	$_SESSION['admin'] = false;
	$returndata = array('code'=>-100,"message"=>"您的登录已经过期，请重新登录");
	echo json_encode($returndata);
	exit();
}
$action=$_GET['action'];
switch ($action){
	case 'setconfig':
		setconfig();
		break;
	case 'switchname':
		switchname();
		break;
	case 'switchphone':
		switchphone();
		break;
	case 'saveextentioncolumn':
		saveextentioncolumn();
		break;
	case 'getextentioncolumn':
		getextentioncolumn();
		break;
	case 'delextentioncolumn':
		delextentioncolumn();
		break;
	case 'saveflagconfig':
		saveflagconfig();
		break;
	case 'clearreservedinfomation':
		clear_reservedinfomation();
		break;
}

function clear_reservedinfomation(){
	$load=Loader::getInstance();
	$load->model('Flag_model');
	$load->flag_model->clearReservedInfomation();
	$returndata=['code'=>1,'message'=>"导入名单数据已经清空"];
	echo json_encode($returndata);
	return;
}
//保存签到配置信息
function saveflagconfig(){
	$params=array();
	$params['reserved_infomation_match']=isset($_POST['reserved_infomation_match'])?intval($_POST['reserved_infomation_match']):1;
	$params['reserved_infomation_verify']=isset($_POST['reserved_infomation_verify'])?intval($_POST['reserved_infomation_verify']):1;
	$params['reserved_infomation_csv_attachmentid']=isset($_POST['reserved_infomation_csv_attachmentid'])?intval($_POST['reserved_infomation_csv_attachmentid']):0;
	$load=Loader::getInstance();
	$load->model('Flag_model');
	if (!empty($_FILES['filecsv']['type'])) {
		$file=$_FILES['filecsv'];
		$filepath=$file['tmp_name'];
		if(!file_exists($filepath)){
			$returndata=['code'=>-1,'message'=>"文件上传出错，文件不存在"];
			echo json_encode($returndata);
			return;
		}
		$reader=IOFactory::createReader("Xlsx");
		$canread=$reader->canRead($filepath);
		if(!$canread){
			$returndata=['code'=>-2,'message'=>"文件可能已经损坏了，无法读取"];
			echo json_encode($returndata);
			return;
		}
		$excel=$reader->load($filepath);
		$data=$excel->getActiveSheet()->toArray(null,false,false,false);
		if(!is_array($data) || count($data)==0){
			$returndata=['code'=>-3,'message'=>"数据读取失败，可能没有数据"];
			echo json_encode($returndata);
			return;
		}
		$load->flag_model->clearReservedInfomation();
		for($i=0,$l=count($data);$i<$l;$i++){
			if($i==0)continue;
			$reservedinfomation['realname']=empty($data[$i][0])?'':$data[$i][0];
			$reservedinfomation['phone']=empty($data[$i][1])?'':$data[$i][1];
			$reservedinfomation['info']=empty($data[$i][2])?'':$data[$i][2];
			$load->flag_model->addReservedInfomation($reservedinfomation);
		}
	}

	$return=$load->flag_model->setConfig($params);
	if($return){
		echo returnmsg(1,'导入信息设置修改完成');
		return;
	}
	echo returnmsg(-1,'导入信息设置修改失败');
	return;
}

//删除一个扩展字段
function delextentioncolumn(){
	$id=isset($_GET['id'])?intval($_GET['id']):0;
	if($id<=0){
		echo returnmsg(-1,'数据格式错误');
		return;
	}
	$load=Loader::getInstance();
	$load->model('Flag_model');
	$result=$load->flag_model->deleteExtentionColumnsById($id);
	if($result){
		echo returnmsg(1,'删除成功');
		return;
	}else{
		echo returnmsg(-2,'删除失败');
		return;
	}
}
//获取一个扩展字段设置
function getextentioncolumn(){
	$id=isset($_GET['id'])?intval($_GET['id']):0;
	if($id<=0){
		echo returnmsg(-1,'数据格式错误');
		return;
	}
	$load=Loader::getInstance();
	$load->model('Flag_model');
	$extentioncolumninfo=$load->flag_model->getExtentionColumnsById($id);
	if(!$extentioncolumninfo){
		echo returnmsg(-2,'没有数据');
		return;
	}else{
		if($extentioncolumninfo['coltype']=='select'){
			$options=unserialize($extentioncolumninfo['options']);
			foreach($options as $v){
				$extentioncolumninfo['optionstext'].=$v."\n";
			}
			$extentioncolumninfo['optionstext']=trim($extentioncolumninfo['optionstext']);
		}
		echo returnmsg(1,'',$extentioncolumninfo);
		return;
	}
}
//保存扩展字段
function saveextentioncolumn(){
	$params=array();
	$params['id']=isset($_POST['id'])?intval($_POST['id']):0;
	$params['ordernum']=isset($_POST['ordernum'])?intval($_POST['ordernum']):1;
	$params['title']=isset($_POST['title'])?strval($_POST['title']):'';
	$params['coltype']=isset($_POST['coltype'])?strval($_POST['coltype']):'text';
	$params['placeholder']=isset($_POST['placeholder'])?strval($_POST['placeholder']):'';
	$options=isset($_POST['options'])?strval($_POST['options']):'';
	
	$params['defaultvalue']=isset($_POST['defaultvalue'])?strval($_POST['defaultvalue']):'';
	$params['ismust']=isset($_POST['ismust'])?intval($_POST['ismust']):1;
	

	if(empty($params['title'])){
		echo returnmsg(-1,'字段标题必须填写');
		return;
	}
	if($params['coltype']=='select' and empty($options)){
		echo returnmsg(-2,'类型为选择框时，必须有选项才能正常运行');
		return;
	}

	$options_arr=explode("\n",$options);
	$new_options_arr=array();
	foreach($options_arr as $k=>$v){
		$v=trim($v);
		if(!empty($v)){
			$new_options_arr[]=$v;
		}
	}
	$params['options']=serialize($new_options_arr);
	$params['defaultvalue']=trim($params['defaultvalue']);
	$load=Loader::getInstance();
	$load->model('Flag_model');
	//保存自定义字段
	$return=$load->flag_model->saveExtentionColumn($params);
	if($return){
		echo returnmsg(1,'保存成功');
		return;
	}
	echo returnmsg(-3,'保存失败');
	return;
}
//修改是否填写姓名
function switchname(){
	$status=isset($_POST['status'])?intval($_POST['status']):1;
	$system_config_model=new System_Config_model();
	$result=$system_config_model->set('qiandaosignname',$status);
	if($result){
		$resultdata=array('code'=>1,'message'=>'修改成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-1,'message'=>'修改失败');
		echo json_encode($resultdata);
		return;
	}
}
//修改是否填写手机号
function switchphone(){
	$status=isset($_POST['status'])?intval($_POST['status']):1;
	$system_config_model=new System_Config_model();
	$result=$system_config_model->set('qiandaophone',$status);
	if($result){
		$resultdata=array('code'=>1,'message'=>'修改成功');
		echo json_encode($resultdata);
		return;
	}else{
		$resultdata=array('code'=>-1,'message'=>'修改失败');
		echo json_encode($resultdata);
		return;
	}
}
//签到是否需要审核
function setconfig(){
	$key=isset($_GET['key'])?$_GET['key']:'';
	$val=isset($_GET['val'])?intval($_GET['val']):1;
	if(!in_array($key,array('signnameshowstyle','qiandaoshenhe'))){
		echo '{"code":-1,"message":"数据格式错误"}';
		return ;
	}
	$val=($val>3 ||$val<1)?1:$val;
	$system_config_model=new System_Config_model();
	$return=$system_config_model->set($key,$val);
	if($return){
		echo '{"code":1,"message":"修改成功"}';
		return ;
	}else{
		echo '{"code":-2,"message":"修改失败"}';
		return ;
	}
}