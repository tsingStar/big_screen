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
require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Frontbase.php';
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
require_once BASEPATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'function.php';
use \Modules\Page\Models\Pages_model;

class Front extends Frontbase
{

    /**
     * 摇大奖界面
     *
     * @return void
     */
    public function index()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $pages_model = new Pages_model();
        $currentdata = null;
        if ($id == 0) {
            $currentdata = $pages_model->getFirstPcPage();
        } else {
            $currentdata = $pages_model->getById($id);
        }
        if ($currentdata['type'] == 2) {
            $currentdata = $pages_model->getFirstPcPage();
        }
        $data = $pages_model->getPcPages();
        $this->assign('currentdata', $currentdata);
        
        $this->assign('configs', json_encode($data));
        $this->assign('title', $currentdata['title']);
        $path = '';
        if ($currentdata['type'] == 1) {
            $path = 'pc/';
        }
        if ($currentdata['type'] == 3) {
            $path = 'sign/';
        }
        $this->show($path . 'index.html', true);
    }
    public function ipad()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $pages_model = new Pages_model();
        $currentdata = $pages_model->getById($id);
        $this->assign('currentdata', $currentdata);
        $path = '';
        if ($currentdata['type'] == 3) {
            $path = 'sign/';
        }
        $this->show($path . 'ipad.html', true);
    }
    public function ajaxSavePos()
    {
        //保存位置信息
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $index = isset($_POST['index']) ? intval($_POST['index']) : 0;
        $pos = $_POST['pos'];
        if ($id <= 0 || $index < 0) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $pos['x'] = is_numeric($pos['x']) ? $pos['x'] : -1;
        $pos['y'] = is_numeric($pos['y']) ? $pos['y'] : -1;
        $pos['w'] = is_numeric($pos['w']) ? $pos['w'] : -1;
        $pos['h'] = is_numeric($pos['h']) ? $pos['h'] : -1;
        if ($pos['x'] < 0 || $pos['y'] < 0 || $pos['w'] < 0 || $pos['h'] < 0) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $pages_model = new Pages_model();
        $currentdata = $pages_model->getById($id);
        $currentdata['pagedata']['signs'][$index]['pos']['x'] = $_POST['pos']['x'];
        $currentdata['pagedata']['signs'][$index]['pos']['y'] = $_POST['pos']['y'];
        $currentdata['pagedata']['signs'][$index]['pos']['w'] = $_POST['pos']['w'];
        $currentdata['pagedata']['signs'][$index]['pos']['h'] = $_POST['pos']['h'];
        $pages_model->save($currentdata);
        $returndata = ['code' => 1, 'message' => '更新成功'];
        echo json_encode($returndata);
        return;
    }
    public function ajaxSaveSign()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $index = isset($_POST['index']) ? intval($_POST['index']) : 0;
        if ($id <= 0 || $index < 0) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $pages_model = new Pages_model();
        $currentdata = $pages_model->getById($id);
        // echo var_export($currentdata);
        $base64 = isset($_POST['base64']) ? strval($_POST['base64']) : '';
        $base64 = str_replace('data:image/png;base64,', '', $base64);
        $file = base64_decode($base64);
        include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'FileUploadFactory.php';
        // include_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'FileUploadFactory.php';
        $fuf = new FileUploadFactory(SAVEFILEMODE);
        $extension = 'png';
        $attachement_data = $fuf->SaveFile($file, $extension);
        if (!$attachement_data) {
            $returndata = ['code' => -2, 'message' => '保存失败'];
            echo json_encode($returndata);
            return;
        }
        $currentdata['pagedata']['signs'][$index]['img'] = $attachement_data['id'];
        
        $pages_model->save($currentdata);
        $returndata = ['code' => 1, 'message' => '保存成功'];
        echo json_encode($returndata);
        return;
    }
    public function ajaxGetSign(){
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0 ) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $pages_model = new Pages_model();
        $currentdata = $pages_model->getById($id);
        $returndata=['code'=>1,'data'=>$currentdata];
        echo json_encode($returndata);
        return;
    }
}
