<?php
@header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__) . '/../common/db.class.php';
require_once dirname(__FILE__) . '/../common/function.php';
require_once dirname(__FILE__) . '/../common/session_helper.php';
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
    $_SESSION['admin'] = false;
    $returndata = array('code' => -100, "message" => "您的登录已经过期，请重新登录");
    echo json_encode($returndata);
    exit();
}
$action = $_GET['action'];
switch ($action) {
    case 'seterweima':
        seterweima();
        break;
    case 'qrcodetoptext':
        setqrcodetoptext();
        break;
    case 'menucolor':
        setmenucolor();
        break;
    case 'showcountsign':
        showcountsign();
        break;
    case 'setmobilemenufontcolor':
        setmobilemenufontcolor();
        break;
}

function showcountsign()
{
    $load = Loader::getInstance();
    $val = isset($_GET['showcountsign']) ? intval($_GET['showcountsign']) : 1;
    $load->model('System_Config_model');
    $return = $load->system_config_model->set('showcountsign', $val);
    // echo 'test';
    if ($return) {
        echo '{"code":1,"message":"修改成功"}';
        return;
    } else {
        echo '{"code":-2,"message":"修改失败"}';
        return;
    }
}
//调整页面底部菜单颜色
function setmenucolor()
{
    $load = Loader::getInstance();
    $val = isset($_GET['menucolor']) ? $_GET['menucolor'] : '#fff';
    $load->model('System_Config_model');
    $return = $load->system_config_model->set('menucolor', $val);
    if ($return) {
        echo '{"code":1,"message":"修改成功"}';
        return;
    } else {
        echo '{"code":-2,"message":"修改失败"}';
        return;
    }

}
//设置手机端签到页面的菜单文字颜色
function setmobilemenufontcolor()
{
    $load = Loader::getInstance();
    $val = isset($_GET['mobilemenufontcolor']) ? $_GET['mobilemenufontcolor'] : '#fff';
    $load->model('System_Config_model');
    $return = $load->system_config_model->set('mobilemenufontcolor', $val);
    if ($return) {
        echo '{"code":1,"message":"修改成功"}';
        return;
    } else {
        echo '{"code":-2,"message":"修改失败"}';
        return;
    }
}

//设置活动二维码
function seterweima()
{
    $imgpath=isset($_POST['imgpath'])?strval($_POST['imgpath']):'';
    if($imgpath!=''){
        echo "<script>alert('二维码已经配置成功！');history.go(-1);</script>";
        return;
    }
    $load = Loader::getInstance();
    $load->model('Weixin_model');
    $data = array('erweima' => 0);
    if (!empty($_FILES['erweima']['type'])) {
        $load->model('Attachment_model');
        $file = $load->attachment_model->saveFormFile($_FILES['erweima']);

        $data = array('erweima' => $file['id']);
    }
    $result = $load->weixin_model->setConfig($data);
    echo "<script>alert('二维码已经配置成功！');history.go(-1);</script>";
}

//设置二维码上面的文字
function setqrcodetoptext()
{
    $text = isset($_POST['qrcodetoptext']) ? strval($_POST['qrcodetoptext']) : '';
    $load = Loader::getInstance();
    $load->model('Wall_model');
    $data = array('qrcodetoptext' => $text);
    $result = $load->wall_model->setConfig($data);
    if ($result) {
        $resultdata = array('code' => 1, 'message' => '修改成功');
        echo json_encode($resultdata);
        return;
    } else {
        $resultdata = array('code' => -1, 'message' => '修改失败');
        echo json_encode($resultdata);
        return;
    }
}
