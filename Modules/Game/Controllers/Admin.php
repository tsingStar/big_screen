<?php
/**
 * 模块后台页面
 * PHP version 5.5+
 *
 * @category Modules
 *
 * @package ShakeGame
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */

require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Adminbase.php';
/**
 * 模块后台页面
 * PHP version 5.5+
 *
 * @category Modules
 *
 * @package ShakeGame
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
use Modules\Game\Models\GameConfig_model;
use Modules\Game\Models\GameThemes_model;
use Modules\Game\Models\Game_model;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;
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
     * 游戏轮次设置
     *
     * @return void
     */
    public function index()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->setTitle('游戏管理');
        $this->setDescription('赛车，摇一摇，猴子爬树等等');
        $gameconfig_model = new GameConfig_model();
        $data = $gameconfig_model->getAll();
        $gamethemes_model = new GameThemes_model();
        $gamethemes = $gamethemes_model->getAll();
        $currentconfig = $gameconfig_model->getById($id, true, true);
        $this->assign('configs', $data);
        $this->assign('themes', $gamethemes);
        $this->assign('currentconfig', $currentconfig);
        $this->show("index.html");
    }
    //保存配置信息
    public function ajaxSaveGameConfig()
    {
        $data = [];
        $data['id'] = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $data['toprank'] = isset($_POST['toprank']) ? intval($_POST['toprank']) : 0;
        $data['themeid'] = isset($_POST['themeid']) ? intval($_POST['themeid']) : 0;
        $data['winagain'] = isset($_POST['winagain']) ? intval($_POST['winagain']) : 1;
        $data['showtype'] = isset($_POST['showtype']) ? strval($_POST['showtype']) : 'nickname';
        if ($data['themeid'] == 0) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        if($data['toprank']>200 || $data['toprank']<0){
            $returndata = ['code' => -1, 'message' => '最多只能设置前200名中奖'];
            echo json_encode($returndata);
            return;
        }
        $gameconfig_model = new GameConfig_model();
        $return = $gameconfig_model->save($data);
        if ($return) {
            $returndata = ['code' => 1, 'message' => '保存成功'];
            echo json_encode($returndata);
            return;
        }
    }
    public function ajaxGetWinners(){
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        $gameconfig_model = new GameConfig_model();
        $config = $gameconfig_model->getById($id, true, true);
        $game_model = new Game_model();
        $users = $game_model->getWinners($id, $config['showtype']);
        $this->assign('winners',$users);
        $this->show("snippets/winners.html");
    }
    //删除游戏配置
    public function ajaxDelGameConfig(){
        $id=isset($_POST['id'])?intval($_POST['id']):0;
        if($id<=0){
            $returndata=['code'=>-1,"message"=>'数据格式错误'];
            echo json_encode($returndata);
            return ;
        }
        $game_model = new Game_model();
        $return=$game_model->delete($id);
        $returndata=['code'=>1,"message"=>'游戏已经删除'];
        echo json_encode($returndata);
        return ;
    }
    public function ajaxDeleteWinner(){
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        if($id<=0 ){
            $returndata = ['code' => -1, 'message' => '删除失败'];
            echo json_encode($returndata);
            return;
        }

        $game_model = new Game_model();
        $result=$game_model->delete($id);
        if($result){
            $returndata = ['code' => 1, 'message' => '删除成功'];
            echo json_encode($returndata);
            return;
        }else{
            $returndata = ['code' => -1, 'message' => '删除失败'];
            echo json_encode($returndata);
            return;
        }
    }

    //获取主题设置
    public function ajaxGetThemeConfig()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $gameconfig_model = new GameConfig_model();
        $config = $gameconfig_model->getById($id, true, true);
        $gamethemes_model = new GameThemes_model();
        $themeinfo = $gamethemes_model->getById($config['themeid']);

        //读取设置
        $themeconfig = $config['themeconfig'];
        $this->assign('config', json_encode($themeconfig));
        $this->show(strtolower($themeinfo['themepath']). "/snippet_themeconfig.html");
    }
    //保存主题的设置
    public function ajaxSaveThemeConfig()
    {
        $settings = [];
        $id = isset($_POST['configid']) ? intval($_POST['configid']) : 0;
        $key = isset($_POST['key']) ? strval($_POST['key']) : '';
        $val = isset($_POST['val']) ? strval($_POST['val']) : '';
        if ($id == 0 || $key == '') {
            $returndata = ['code' => -1, "message" => "数据错误"];
            echo json_encode($returndata);
            return;
        }
        $data = [];
        $data['id'] = $id;
        //合并默认值和现有值之后,再修改json配置信息
        //
        // $config=$gameconfig_model->getById($id,true,true);
        $gamethemes_model = new GameThemes_model();
        $themeinfo = $gamethemes_model->getById($id);
        // $themeconfig=$config['themeconfig'];
        $themeconfig[$key] = $val;
        if ($_FILES['file']) {
            require_once BASEPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'attachment_model.php';
            $allowtypes = 'image/jpg,image/jpeg,image/png,video/mp4,audio/mp3';
            $attachment_model = new \Attachment_model();
            $file = $attachment_model->saveFormFile($_FILES['file'], $allowtypes);
            $themeconfig[$key] = $file['id'];
        }
        $data['themeconfig'] = $themeconfig;
        $gameconfig_model = new GameConfig_model();
        $return = $gameconfig_model->save($data);
        $returndata = ['code' => -1, "message" => "保存失败"];
        if ($return) {
            $returndata = ['code' => 1, "message" => "保存成功"];
        }
        echo json_encode($returndata);
        return;
    }
    public function ajaxResetGame()
    {
        $id = isset($_POST['configid']) ? intval($_POST['configid']) : 0;
        $game_model = new Game_model();
        $game_model->clearData($id);
        $returndata = ['code' => 1, "message" => "重置完成"];
        echo json_encode($returndata);
        return;
    }

    public function exportData(){
        $game_model = new Game_model();
        $data=$game_model->getCompleteWinners();
        $companyname="金华迪加网络科技有限公司";
        $title='现场活动大屏幕系统游戏结果';
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getProperties()->setCreator($companyname)
                                    ->setLastModifiedBy($companyname)
                                    ->setTitle($title)
                                    ->setSubject($title)
                                    ->setDescription($title)
                                    ->setKeywords($title)
                                    ->setCategory($companyname.$title."导出文件");
        $sheetindex=0;
        // $oldactivityid=0;
        // $currentactivityid=0;
        $oldgameid=0;
        $rownum=2;
        $activesheet=null;
        foreach($data as $row){
            // $currentactivityid=$row['activityid'];
            if($row['gameid']!=$oldgameid){
                $oldgameid=$row['gameid'];
                $activesheet=$this->_createsheet($sheetindex,$objPHPExcel);
                $sheetindex++;
                $rownum=2;
            }
            $this->_writedata($row,$rownum,$prizes,$activesheet);
            $rownum++;
        }

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
    }

    private function _createsheet($index,$excelobj){
        if($index>0){
            $excelobj->createSheet();
        }
        $activesheet=$excelobj->setActiveSheetIndex($index);
        $activesheet->setCellValue('A1', '昵称');
        $activesheet->setCellValue('B1', '姓名');
        $activesheet->setCellValue('C1', '手机号');
        $activesheet->setCellValue('D1', '成绩');
        $activesheet->setCellValue('E1', '时间');
        $title='第'.($index+1).'轮游戏结果';
        $excelobj->getActiveSheet()->setTitle($title);
        return $activesheet;
    }

    private function _writedata($row,$rownum,$prizes,$activesheet){
        $statustext=['','','未发','已发'];
        $activesheet->setCellValue('A'.$rownum, $row['nickname']);
        $activesheet->setCellValue('B'.$rownum, $row['signname']);
        $activesheet->setCellValue('C'.$rownum, $row['phone']);
        $activesheet->setCellValue('D'.$rownum, $row['points']);
        $activesheet->setCellValue('E'.$rownum, date('Y-m-d H:i:s',$row['created_at']));
    }  
}
