<?php
/**
 * 模块后台页面
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

require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Adminbase.php';
require_once MODULE_PATH . DIRECTORY_SEPARATOR . '../common/url_helper.php';
use \Modules\Page\Models\Pages_model;

// echo dirname(__FILE__);
/**
 * 模块后台页面
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
     * 单页设置功能
     *
     * @return void
     */
    public function index()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $this->setTitle('单页设置');
        $this->setDescription('设置一个页面，用来做类似开幕墙，闭幕墙，会议议程，现场签约等等');
        $pages_model = new Pages_model();
        $data = $pages_model->getAll();
        $currentdata = null;
        if ($id == 0) {
            $currentdata = $pages_model->getFirst();
        } else {
            $currentdata = $pages_model->getById($id);
        }

        $this->assign('currentdata', $currentdata);
        $this->assign('configs', $data);
        $templatepath = $this->_current_module_admin_template_path . DIRECTORY_SEPARATOR . 'pc' . DIRECTORY_SEPARATOR . 'snippet_themeconfig.html';
        if ($currentdata['type'] == 2) {
            $this->_load->model('Wall_model');
            $wallconfig = $this->_load->wall_model->getConfig();
            $templatepath = $this->_current_module_admin_template_path . DIRECTORY_SEPARATOR . 'mobile' . DIRECTORY_SEPARATOR . 'snippet_themeconfig.html';
            $pageurl = request_scheme() . '://' . $_SERVER['HTTP_HOST'] . '/Modules/module.php?m=page&c=mobile&a=index&id=' . $currentdata['id'] . '&vcode=' . $wallconfig['verifycode'];
            $this->assign('pageurl', $pageurl);
        }
        if ($currentdata['type'] == 3) {
            $signnum = count($currentdata['pagedata']['signs']);
            $this->assign('signnum', $signnum);
            $templatepath = $this->_current_module_admin_template_path . DIRECTORY_SEPARATOR . 'sign' . DIRECTORY_SEPARATOR . 'snippet_themeconfig.html';
            $url = request_scheme() . '://' . $_SERVER['HTTP_HOST'] . '/Modules/module.php?m=page&c=front&a=ipad&id=' . $currentdata['id'];// . '&vcode=' . $wallconfig['verifycode'];
            $this->assign('signurl',$url);
        }

        $this->_smarty->assign('snippet', $this->_smarty->fetch($templatepath));
        $this->show("index.html");
    }
    /**
     * 保存单页的基本配置
     *
     * @return void
     */
    public function ajaxSavePage()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $title = isset($_POST['title']) ? strval($_POST['title']) : '';
        $type = isset($_POST['type']) ? intval($_POST['type']) : 1;
        $pages_model = new Pages_model();
        $result = $pages_model->save(['id' => $id, 'title' => $title, 'type' => $type]);
        if ($result) {
            $returndata = ['code' => 1, 'message' => '保存成功', 'data' => ['id' => $result]];
        } else {
            $returndata = ['code' => -1, 'message' => '保存失败'];
        }
        echo json_encode($returndata);
        return;
    }
    public function ajaxSavePagedata2()
    {
        $id = isset($_POST['configid']) ? intval($_POST['configid']) : 0;
        $bgmusic_switch = isset($_POST['bgmusic_switch']) ? intval($_POST['bgmusic_switch']) : 0;
        $signnum = isset($_POST['signnum']) ? intval($_POST['signnum']) : 0;
        if ($id <= 0 || $signnum <= 0 || $bgmusic_switch <= 0) {
            $returndata = ['code' => -2, "message" => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $pages_model = new Pages_model();
        $data = $pages_model->getById($id);
        $data['pagedata']['bgmusic_switch'] = $bgmusic_switch;
        $data['pagedata']['signnum'] = $signnum;
        $data = ['id' => $id, 'pagedata' => $data['pagedata']];
        $return = $pages_model->save($data);
        $returndata = ['code' => -1, 'message' => '保存失败'];
        if ($return) {
            $returndata = ['code' => 1, 'message' => '保存成功'];
        }
        echo json_encode($returndata);
        return;
    }
    public function ajaxSavePagedata()
    {
        $id = isset($_POST['configid']) ? intval($_POST['configid']) : 0;
        $key = isset($_POST['key']) ? strval($_POST['key']) : '';
        $val = isset($_POST['val']) ? intval($_POST['val']) : 0;
        if ($id <= 0 || $key == '') {
            $returndata = ['code' => -2, "message" => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $pages_model = new Pages_model();
        $data = $pages_model->getById($id);
        $data['pagedata'][$key] = 0;
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
            $data['pagedata'][$key] = $file['id'];
        } else {
            $data['pagedata'][$key] = $val;
        }
        $data = ['id' => $id, 'pagedata' => $data['pagedata']];

        $return = $pages_model->save($data);
        $returndata = ['code' => -1, 'message' => '保存失败'];
        if ($return) {
            $returndata = ['code' => 1, 'message' => '保存成功'];
        }
        echo json_encode($returndata);
        return;
    }

    public function ajaxDelPage()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $pages_model = new Pages_model();
        $return = $pages_model->delById($id);
        $returndata = ['code' => -2, 'message' => '删除失败'];
        if ($return) {
            $returndata = ['code' => 1, 'message' => '删除成功'];

        }
        echo json_encode($returndata);
        return;
    }
    //清除签名图片信息
    public function ajaxClearSign()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }

        $pages_model = new Pages_model();
        $data = $pages_model->getById($id);
        for ($i = 0, $l = count($data['pagedata']['signs']); $i < $l; $i++) {
            $data['pagedata']['signs'][$i]['img'] = 0;
        }
        $return = $pages_model->save($data);
        if ($return) {
            $returndata = ['code' => 1, 'message' => '签名已经清空'];
            echo json_encode($returndata);
            return;
        }
        $returndata = ['code' => -2, 'message' => '签名清空失败'];
        echo json_encode($returndata);
        return;
    }

    public function exportSign()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $returndata = ['code' => -1, 'message' => '数据格式错误'];
            echo json_encode($returndata);
            return;
        }
        $pages_model = new Pages_model();
        $data = $pages_model->getById($id);
        include_once SYSTEMPATH . 'models' . DIRECTORY_SEPARATOR . 'attachment_model.php';
        $attachment_model = new Attachment_model();
        $image = $attachment_model->getById($data['pagedata']['img']);
        $imagepath = $this->getFilePath($image);
        $imagesizeinfo = getimagesize($imagepath);
        // echo var_export($imagesizeinfo);
        $imagehandler = $this->getImageHander($imagepath, $imagesizeinfo['mime']);
        $signsimagehandlers = [];
        $signsimageinfos = [];
        $length = count($data['pagedata']['signs']);
        if ($length > 0) {
            for ($i = 0, $l = count($data['pagedata']['signs']); $i < $l; $i++) {
                if ($data['pagedata']['signs'][$i]['img'] > 0) {
                    $signimage = $attachment_model->getById($data['pagedata']['signs'][$i]['img']);
                    $signimagepath = $this->getFilePath($signimage);
                    $signsimageinfos[$i] = getimagesize($signimagepath);
                    $signsimagehandlers[$i] = $this->getImageHander($signimagepath, $signsimageinfos[$i]['mime']);
                    imagesavealpha($signsimagehandlers[$i], true);
                    //框的坐标和宽高
                    $x = $imagesizeinfo[0] * $data['pagedata']['signs'][$i]['pos']['x'] / 100;
                    $y = $imagesizeinfo[1] * $data['pagedata']['signs'][$i]['pos']['y'] / 100;
                    $w = $imagesizeinfo[0] * $data['pagedata']['signs'][$i]['pos']['w'] / 100;
                    $h = $imagesizeinfo[1] * $data['pagedata']['signs'][$i]['pos']['h'] / 100;

                    //图片的宽高比
                    $ratio = $signsimageinfos[$i][0] / $signsimageinfos[$i][1];
                    //框的宽高比
                    // $boxratio = $data['pagedata']['signs'][$i]['pos']['w']*$imagesizeinfo[0] / $data['pagedata']['signs'][$i]['pos']['h']*$imagesizeinfo[1];
                    $boxratio = $data['pagedata']['signs'][$i]['pos']['w'] / $data['pagedata']['signs'][$i]['pos']['h'];
                    //图片更宽
                    $imagew = 0;
                    $imageh = 0;
                    if ($ratio > $boxratio) {
                        $scale = $w / $signsimageinfos[$i][0];
                        $imagew = $w;
                        $imageh = $signsimageinfos[$i][1] * $scale;
                    } else {
                        //框更宽
                        $scale = $h / $signsimageinfos[$i][1];
                        $imagew = $signsimageinfos[$i][0] * $scale;
                        $imageh = $h;
                    }
                    imagecopyresampled($imagehandler, $signsimagehandlers[$i], $x, $y, 0, 0, $imagew, $imageh, $signsimageinfos[$i][0], $signsimageinfos[$i][1]);
                }
            }

        }
        header("Content-Disposition: attachment; filename=" . $data['title'] . ".png");
        header('Content-type:' . $imagesizeinfo);
        imagejpeg($imagehandler, null, 100);
        imagedestroy($imagejpeg);
    }

    private function getFilePath($image)
    {
        $imagepath = '';
        if ($image['type'] == 1) {
            $imagepath = SYSTEMPATH . ltrim($image['filepath'], '/');
        } else {
            $imagepath = $image['filepath'];
        }
        return $imagepath;
    }
    private function getImageHander($imagepath, $mime)
    {
        if (trim($mime) == 'image/jpeg') {
            return imagecreatefromjpeg($imagepath);
        }
        if ($mime == 'image/png') {
            return imagecreatefrompng($imagepath);
        }
    }
}
