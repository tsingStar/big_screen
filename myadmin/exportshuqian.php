<?php
require_once('../common/db.class.php');
require_once('../common/CacheFactory.php');
require_once('../common/session_helper.php');
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
    $_SESSION['admin'] = false;
    echo "<script>window.location='login.php';</script>";
    exit();
}

$id=isset($_GET['id'])?intval($_GET['id']):0;
if($id<0){
    return ;
}

$objPHPExcel = new Spreadsheet();
$objPHPExcel->getProperties()->setCreator("金华迪加网络科技有限公司")
							 ->setLastModifiedBy("金华迪加网络科技有限公司")
							 ->setTitle("Office 2007 XLSX 现场活动大屏幕系统数钱结果")
							 ->setSubject("Office 2007 XLSX 现场活动大屏幕系统数钱结果")
							 ->setDescription("现场活动大屏幕系统数钱结果.")
							 ->setKeywords("现场活动大屏幕系统数钱结果")
                             ->setCategory("金华迪加网络科技有限公司程序导出文件");
$load->model('Shuqian_model');
$sheetindex=0;
if($id==0){
    $configs=$load->shuqian_model->getAllConfig();
    foreach($configs as $config){
        if($config['status']==3){
            $shake_record=$load->shuqian_model->getRecord($config['id']);
            if(empty($shake_record)){
                continue;
            }
            genSheet($sheetindex,$shake_record,$config['id'],$objPHPExcel);
            $sheetindex++;
        }
    }
}else{
    $shake_record=$load->shuqian_model->getRecord($id);
    if(empty($shake_record)){
        return;
    }
    genSheet($sheetindex,$shake_record,$id,$objPHPExcel);
}


$title='现场活动大屏幕系统数钱结果';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
$objWriter->save('php://output');
return;



function genSheet($index,$data,$roundid,$excelobj){
    if($index>0){
        $excelobj->createSheet();
    }
    $activesheet=$excelobj->setActiveSheetIndex($index);
    $activesheet->setCellValue('A1', '昵称');
    $activesheet->setCellValue('B1', '姓名');
    $activesheet->setCellValue('C1', '手机号');
    $activesheet->setCellValue('D1', '头像路径');
    $activesheet->setCellValue('E1', '分数');

    $rownum=2;
    foreach($data as $row){
        $activesheet->setCellValue('A'.$rownum, $row['nickname']);
        $activesheet->setCellValue('B'.$rownum, $row['signname']);
        $activesheet->setCellValue('C'.$rownum, $row['phone']);
        $activesheet->setCellValue('D'.$rownum, $row['avatar']);
        $activesheet->setCellValue('E'.$rownum, $row['point']);
        $rownum++;
    }
    $title='第'.$roundid.'轮游戏结果';
    $excelobj->getActiveSheet()->setTitle($title);
}