<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once('../common/db.class.php');
require_once('../common/CacheFactory.php');
require_once('../common/session_helper.php');
require_once('../library/emoji/emoji.php');
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
    $_SESSION['admin'] = false;
    echo "<script>window.location='login.php';</script>";
    exit();
}

$load->model('Flag_model');
$data=$load->flag_model->getAllUsers();

$extentsioncolumns=$load->flag_model->getExtentionColumns();
// echo var_export($extentsioncolumns);
$columns=array(
    'A'=>array('title'=>'昵称','columnname'=>'nickname'),
    'B'=>array('title'=>'姓名','columnname'=>'signname'),
    'C'=>array('title'=>'手机号','columnname'=>'phone'),
    'D'=>array('title'=>'头像路径','columnname'=>'avatar')
);
if(!empty($extentsioncolumns)){
    $i=3;
    foreach($extentsioncolumns as $k=>$v){
        $i++;
        $zimu=getColumnNum($i);
        $columns[$zimu]=array('title'=>$v['title'],'columnname'=>'column_'.$v['id']);
    }
}
//计算excel表格的列名
function getColumnNum($index,$str=''){
    $zimu=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $j=floor($index/26);
    if($j>0){
        $i=$index%26;
        $str=$str.$zimu[$i];
        return getColumnNum($j-1,$str);
    }
    $i=$index%26;
    $str=$zimu[$i].$str;
    return $str;
}


// Create new PHPExcel object
$objPHPExcel = new Spreadsheet();

// Set document properties
$objPHPExcel->getProperties()->setCreator("微赢科技")
							 ->setLastModifiedBy("微赢科技")
							 ->setTitle("Office 2007 XLSX 现场活动大屏幕系统签到用户列表")
							 ->setSubject("Office 2007 XLSX 现场活动大屏幕系统签到用户列表")
							 ->setDescription("现场活动大屏幕系统签到用户列表.")
							 ->setKeywords("现场活动大屏幕系统签到用户列表")
							 ->setCategory("微赢科技程序导出文件");

// Add some data
$activesheet=$objPHPExcel->setActiveSheetIndex(0);
foreach($columns as $k=>$col){
    $activesheet->setCellValue($k.'1', $col['title']);
}

$i=2;
// echo var_export($data);
foreach($data as $q){
    $q['nickname'] = pack('H*', $q['nickname']);
    $q['nickname'] = emoji_unified_to_html(emoji_softbank_to_unified($q['nickname']));
    $ci=0;
    
    foreach($columns as $k=>$col){
        $ci++;
        if($ci<=4){
            $activesheet->setCellValue($k.$i, $q[$col['columnname']]);
        }else{
            $activesheet->setCellValue($k.$i, $q['extentions'][$col['columnname']]['val']);
        }
    }
    $i++;
}
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$i, '总签到人数：'.($i-2).'人');


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('现场活动大屏幕系统签到用户列表');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="现场活动大屏幕系统签到用户列表.xlsx"');
// header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
$objWriter->save('php://output');
exit;
