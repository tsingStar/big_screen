<?php
require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Adminbase.php';
use Modules\Importlottery\Models\ImportlotteryConfig_model;
use Modules\Importlottery\Models\ImportlotteryThemes_model;
use Modules\Importlottery\Models\Importlottery_model;
use Modules\Prize\Controllers\Api;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;

class Admin extends Adminbase
{
    /**
     * 构造函数
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 导入信息抽奖设置
     *
     * @return void
     */
    public function index()
    {
        $this->setTitle('导入抽奖设置');
        $this->setDescription('');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $importlotteryconfig_model = new ImportlotteryConfig_model();
        $importlotterythemes_model = new ImportlotteryThemes_model();
        $data = $importlotteryconfig_model->getAll();
        $themes = $importlotterythemes_model->getAll();
        $importlotteryconfig = $importlotteryconfig_model->getById($id, true);
        $this->assign('importlotteryconfig', $importlotteryconfig);
        $this->assign('themes', $themes);
        $this->assign('data', $data);
        $this->assign('currentid', $importlotteryconfig['id']);
        $this->show("index.html");
    }

    public function ajaxSaveLotteryConfig()
    {
        $params = $_POST;
        $data = [];
        $data['id'] = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $data['title'] = isset($_POST['title']) ? strval($_POST['title']) : '';
        $data['themeid'] = isset($_POST['themeid']) ? intval($_POST['themeid']) : 1;

        if (empty($data['title'])) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }

        $importlotteryconfig_model = new ImportlotteryConfig_model();
        $result = $importlotteryconfig_model->save($data);
        if ($result !== false) {
            $returndata = ['code' => 1, 'message' => '保存成功', 'data' => ['id' => $result]];
            echo json_encode($returndata);
            return;
        }
        $returndata = ['code' => -2, 'message' => '保存失败'];
        echo json_encode($returndata);
        return;
    }

    //获取主题设置
    public function ajaxGetThemeSettings()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $lotteryconfig_model = new ImportlotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($id, true);
        $this->assign('settings', json_encode($lotteryconfig['themeconfig']));
        $this->show($lotteryconfig['themepath'] . "/snippet_themeconfig.html");
    }
    public function ajaxSaveThemeSettings()
    {
        $settings = [];
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id <= 0) {
            $returndata = ['code' => -2, "message" => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $lotteryconfig_model = new ImportlotteryConfig_model();
        $data = ['id' => $id, 'themeconfig' => $_POST];
        $return = $lotteryconfig_model->save($data);
        $returndata = ['code' => -1, 'message' => '保存失败'];
        if ($return) {
            $returndata = ['code' => 1, 'message' => '保存成功'];
        }
        echo json_encode($returndata);
        return;
    }

    public function ajaxSaveThemeConfigFile()
    {
        $id = isset($_POST['configid']) ? intval($_POST['configid']) : 0;
        $key = isset($_POST['key']) ? strval($_POST['key']) : '';
        $val = isset($_POST['val']) ? intval($_POST['val']) : 0;
        if ($id <= 0 || $key == '') {
            $returndata = ['code' => -2, "message" => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $lotteryconfig_model = new ImportlotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($id, true);
        if ($_FILES['file']) {
            if ($_FILES['file']['error'] == 1) {
                $returndata = ['code' => -3, 'message' => '文件太大了'];
                echo json_encode($returndata);
                return;
            }
            $allowtypes = 'image/jpg,image/jpeg,image/png,video/mp4,audio/mp3';
            $this->_load->model('Attachment_model');
            $file = $this->_load->attachment_model->saveFormFile($_FILES['file'], $allowtypes);
            if ($file == false) {
                $returndata = ['code' => -2, 'message' => '文件上传失败'];
                echo json_encode($returndata);
                return;
            }
            $lotteryconfig['themeconfig'][$key] = $file['id'];
        } else {
            $lotteryconfig['themeconfig'][$key] = 0;
        }
        $data = ['id' => $id, 'themeconfig' => $lotteryconfig['themeconfig']];
        $return = $lotteryconfig_model->save($data);
        $returndata = ['code' => -1, 'message' => '保存失败'];
        if ($return) {
            $returndata = ['code' => 1, 'message' => '保存成功'];
        }
        echo json_encode($returndata);
        return;
    }

    //获取奖品信息
    public function ajaxGetPrizes()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $lotteryconfig_model = new ImportlotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($id, false);
        $prize_api = new Api();
        $prizesdata = $prize_api->getprizes('importlottery', $lotteryconfig['id']);
        $prizes = [];
        if ($prizesdata['code'] > 0) {
            $prizes = $prizesdata['data'];
        }
        $this->assign('prizes', $prizes);
        $this->show("snippets/prizes.html");
    }

    public function ajaxGetImportData()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $this->show("snippets/importdata.html");
    }
    public function ajaxGetImportDataPage()
    {
        $activityid = isset($_GET['activityid']) ? intval($_GET['activityid']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
        $pagesize = 12;
        $lotteryconfig_model = new ImportlotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($activityid, false);
        $importlottery_model = new Importlottery_model();
        $data = $importlottery_model->getPagedData($activityid, $page, $pagesize);
        foreach ($data['data'] as $k => $v) {
            $data['data'][$k] = $this->dataContent($v, $lotteryconfig['metadata']);
        }
        $baseurl = '';
        $page = $page <= 0 ? 1 : $page;
        $pagehtml = $this->pagerhtml($page, $pagesize, $data['count']);
        $this->assign('pagehtml', $pagehtml);
        $this->assign('rows', $data['data']);
        $this->assign('page', $page);
        $this->show("snippets/_datalist.html");
    }
    private function dataContent($item, $meta)
    {
        $text = '';
        for ($i = 0, $l = count($meta); $i < $l; $i++) {
            $text .= $meta[$i]['name'] . ':' . $item['datarow'][$i] . ';';
        }
        $item['datarow'] = $text;
        return $item;
    }
    public function ajaxImportExcel()
    {

        $activityid = isset($_POST['activityid']) ? intval($_POST['activityid']) : 0;
        if ($activityid <= 0) {
            $returndata = ['code' => -4, 'message' => "数据格式错误"];
            echo json_encode($returndata);
            return;
        }
        $plugname = isset($_POST['plugname']) ? strval($_POST['plugname']) : '';
        if ($plugname != 'importlottery') {
            $returndata = ['code' => -4, 'message' => "数据格式错误"];
            echo json_encode($returndata);
            return;
        }

        $file = $_FILES['fileexcel'];
        $filepath = $file['tmp_name'];
        if (!file_exists($filepath)) {
            $returndata = ['code' => -1, 'message' => "文件上传出错，文件不存在"];
            echo json_encode($returndata);
            return;
        }
        $reader = IOFactory::createReader("Xlsx");
        $canread = $reader->canRead($filepath);
        if (!$canread) {
            $returndata = ['code' => -2, 'message' => "文件可能已经损坏了，无法读取"];
            echo json_encode($returndata);
            return;
        }
        $excel = $reader->load($filepath);
        $data = $excel->getActiveSheet()->toArray(null, false, false, false);
        // echo var_export($data);
        if (!is_array($data) || count($data) == 0) {
            $returndata = ['code' => -3, 'message' => "数据读取失败，可能没有数据"];
            echo json_encode($returndata);
            return;
        }
        $column_name = array_shift($data);
        // $column_num=0;

        //处理表格中的第一行 元数据
        $meta = [];
        //show=2 表示不显示 1表示显示 只取前3列
        for ($i = 0, $l = count($column_name); $i < $l; $i++) {
            if ($column_name[$i] == null) {
                break;
            }
            $meta[] = ['name' => $column_name[$i], 'show' => 1];
        }
        $importlotteryconfig_model = new ImportlotteryConfig_model();
        //保存元数据到配置表中
        $importlotteryconfig_model->save(['id' => $activityid, 'metadata' => serialize($meta)]);
        $importlottery_model = new Importlottery_model();
        //处理表格中的所有数据
        $rows = [];
        $column_num = count($meta);
        for ($i = 0, $l = count($data); $i < $l; $i++) {
            //保存数据到数据表中
            $rows[] = ['datarow' => serialize(array_slice($data[$i], 0, $column_num)), 'imgid' => 0, 'configid' => $activityid];
        }
        $importlottery_model->saveData($activityid, $rows);
        //导入名单之后需要清空之前的中奖记录，恢复奖品的数量
        $api = new Api();
        $api->restorePrize('importlottery', $activityid);

        $returndata = ['code' => 1, 'message' => "导入结束，请核对数据是否正确。"];
        echo json_encode($returndata);
        return;
    }
    //添加修改导入的单条数据
    public function ajaxSaveImportData()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $data = isset($_POST['col']) ? $_POST['col'] : [];
        $imageid = isset($_POST['imgid']) ? intval($_POST['imgid']) : 0;
        $activityid = isset($_POST['activityid']) ? intval($_POST['activityid']) : 0;
        $img = $_FILES['img'];
        $hasdata = false;
        for ($i = 0, $l = count($data); $i < $l; $i++) {
            if (!empty($data[$i])) {
                $hasdata = true;
            }
        }
        $hasdata = $hasdata || $imageid > 0 || $img != null;
        if (!$hasdata) {
            $returndata = ['code' => -1, 'message' => "数据格式错误。"];
            echo json_encode($returndata);
            return;
        }

        if (!empty($img['type'])) {
            //上传的文件
            $this->_load->model('Attachment_model');
            $file = $this->_load->attachment_model->saveFormFile($img);
            $imageid = $file['id'];
        }

        $importlottery_model = new Importlottery_model();
        $return = $importlottery_model->saveDataItem(['id' => $id, 'datarow' => serialize($data), 'imgid' => $imageid, 'configid' => $activityid]);

        $returndata = ['code' => -1, 'message' => '保存失败'];
        if ($return) {
            $returndata = ['code' => 1, 'message' => '保存成功'];
        }
        echo json_encode($returndata);
        return;
    }
    public function ajaxDelDetail()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $returndata = ['code' => -1, 'message' => "数据格式错误。"];
            echo json_encode($returndata);
            return;
        }
        $lotteryconfig_model = new Importlottery_model();
        $result = $lotteryconfig_model->deleteById($id);
        if ($result) {
            $returndata = ['code' => 1, 'message' => "删除成功。"];
            echo json_encode($returndata);
            return;
        } else {
            $returndata = ['code' => -2, 'message' => "删除失败。"];
            echo json_encode($returndata);
            return;
        }
    }
    public function ajaxGetDetail()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $activityid = isset($_GET['activityid']) ? intval($_GET['activityid']) : 0;
        if ($activityid <= 0) {
            $returndata = ['code' => -1, 'message' => "数据格式错误。"];
            echo json_encode($returndata);
            return;
        }
        $lotteryconfig_model = new ImportlotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($activityid, false);
        $data = [];
        if (!empty($lotteryconfig['metadata'])) {
            $data['meta'] = unserialize($lotteryconfig['metadata']);
        }

        $importlottery_model = new Importlottery_model();
        $row = $importlottery_model->getById($id);
        if ($row) {
            $data['row'] = $row;
        }
        echo json_encode(['code' => 1, 'data' => $data]);
        return;
    }
    public function ajaxClearActivityData()
    {
        $activityid = isset($_GET['activityid']) ? intval($_GET['activityid']) : 0;
        if ($activityid <= 0) {
            $returndata = ['code' => -1, 'message' => "数据格式错误。"];
            echo json_encode($returndata);
            return;
        }
        $importlottery_model = new Importlottery_model();
        $importlottery_model->clearRoundData($activityid);
        //恢复奖品的数量
        $api = new Api();
        $api->restorePrize('importlottery', $activityid);

        $returndata = ['code' => 1, 'message' => "数据已经清空"];
        echo json_encode($returndata);
        return;
    }

    public function ajaxGetWinners()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $lotteryconfig_model = new ImportlotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($id, false);
        $importlottery_model = new Importlottery_model();
        $winners = $importlottery_model->getAllWinners($lotteryconfig['id']);
        $prize_api = new Api();
        $prizesdata = $prize_api->getprizes('importlottery', $lotteryconfig['id']);
        // echo var_export($winners);
        $prizes = [];
        if ($prizesdata['code'] > 0) {
            $prizes = $prizesdata['data'];
        }

        // echo var_export($prizes);
        $this->assign('prizes', $prizes);
        $this->assign('winners', $winners);
        $this->show("snippets/winners.html");
    }

    public function ajaxGetDesignated()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $lotteryconfig_model = new ImportlotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($id, false);
        $prize_api = new Api();
        $designated = $prize_api->getdesignatedlist('importlottery', $lotteryconfig['id']);
        $prizesdata = $prize_api->getprizes('importlottery', $lotteryconfig['id']);

        $importlottery_model = new Importlottery_model();
        $data = $importlottery_model->getByConfigId($id);

        $prizes = [];
        if ($prizesdata['code'] > 0) {
            $prizes = $prizesdata['data'];
        }
        $this->assign('prizes', $prizes);

        $newdata = [];
        foreach ($designated as $k => $v) {
            $item = $this->_processdesignateddata($v, $data);
            $newdata[] = $item;
        }
        $this->assign('designated', $newdata);
        $this->show("snippets/designated.html");
    }

    public function ajaxSearchData()
    {
        $txt = isset($_GET['searchtext']) ? $_GET['searchtext'] : '';
        $configid = isset($_GET['configid']) ? intval($_GET['configid']) : 0;
        $importlottery_model = new Importlottery_model();
        $data = $importlottery_model->searchData($txt, $configid);

        $lotteryconfig_model = new ImportlotteryConfig_model();
        $lotteryconfig = $lotteryconfig_model->getById($id, false);

        $metadata = $lotteryconfig['metadata'];

        foreach ($data as $k => $v) {
            $v['datarow'] = unserialize($v['datarow']);
            $data[$k] = $this->dataContent($v, $metadata);
        }

        $returndata = ['code' => 1, 'data' => $data];
        echo json_encode($returndata);
        return;
    }
    public function exportData()
    {
        $plugname = 'importlottery';
        if ($plugname == '') {
            return;
        }
        $prize_api = new Api();
        $importlotteryconfig_model = new ImportlotteryConfig_model();
        $importlottery_model = new Importlottery_model();
        $prizes = $prize_api->getprizes($plugname, -1);
        $prizes=$prizes['data'];
        $data = $importlottery_model->getAllWinners();
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getProperties()->setCreator("金华迪加网络科技有限公司")
            ->setLastModifiedBy("金华迪加网络科技有限公司")
            ->setTitle("Office 2007 XLSX 现场活动大屏幕系统中奖结果")
            ->setSubject("Office 2007 XLSX 现场活动大屏幕系统中奖结果")
            ->setDescription("现场活动大屏幕系统中奖结果.")
            ->setKeywords("现场活动大屏幕系统中奖结果")
            ->setCategory("金华迪加网络科技有限公司程序导出文件");


        $sheetindex = 0;
        $oldactivityid = 0;
        $currentactivityid = 0;
        $rownum = 2;
        $activesheet = null;
        foreach ($data as $row) {
            $currentactivityid = $row['activityid'];
            if ($currentactivityid != $oldactivityid) {
                $oldactivityid = $currentactivityid;
                $config=$importlotteryconfig_model->getById($currentactivityid,true);
                $activesheet = $this->_createsheet($sheetindex, $oldactivityid,$config['metadata'], $objPHPExcel);
                $sheetindex++;
                $rownum = 2;
            }

            $this->_writedata($row, $rownum, $prizes, $activesheet);
            $rownum++;

        }
        $title = '现场活动大屏幕系统导入信息抽奖中奖结果';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        return;
    }

    private function _createsheet($index, $activityid,$metadata, $excelobj)
    {
        if ($index > 0) {
            $excelobj->createSheet();
        }
        $activesheet = $excelobj->setActiveSheetIndex($index);
        $col1=isset($metadata[0])?$metadata[0]['name']:'第1列';
        $col2=isset($metadata[1])?$metadata[1]['name']:'第2列';
        $col3=isset($metadata[2])?$metadata[2]['name']:'第3列';
        $activesheet->setCellValue('A1', $col1);
        $activesheet->setCellValue('B1', $col2);
        $activesheet->setCellValue('C1', $col3);
        $activesheet->setCellValue('D1', '奖品名称');
        $activesheet->setCellValue('E1', '状态');
        $activesheet->setCellValue('F1', '获奖时间');
        $activesheet->setCellValue('G1', '发奖时间');
        $title = '第' . $activityid . '轮游戏中奖结果';
        $excelobj->getActiveSheet()->setTitle($title);
        return $activesheet;
    }

    private function _writedata($row, $rownum, $prizes, $activesheet)
    {
        $statustext = ['', '', '未发', '已发'];
        // echo var_export($prizes[$row['prizeid']]['prizename']);
        $col1=isset($row['datarow'][0])?$row['datarow'][0]:'';
        $col2=isset($row['datarow'][1])?$row['datarow'][1]:'';
        $col3=isset($row['datarow'][2])?$row['datarow'][2]:'';
        $activesheet->setCellValue('A' . $rownum, $col1);
        $activesheet->setCellValue('B' . $rownum, $col2);
        $activesheet->setCellValue('C' . $rownum, $col3);
        $activesheet->setCellValue('D' . $rownum, $prizes[$row['prizeid']]['prizename']);
        $activesheet->setCellValue('E' . $rownum, $statustext[$row['status']]);
        $activesheet->setCellValue('F' . $rownum, date('Y-m-d H:i:s', $row['wintime']));
        $activesheet->setCellValue('G' . $rownum, empty($row['awardtime']) ? '' : date('Y-m-d H:i:s', $row['awardtime']));
    }

    private function _processdesignateddata($item, $importdata)
    {
        $data = $importdata[$item['userid']];
        $datarow = unserialize($importdata[$item['userid']]['datarow']);
        $item['datarow'] = $datarow;
        return $item;
    }
}
