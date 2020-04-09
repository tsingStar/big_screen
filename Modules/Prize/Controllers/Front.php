<?php
// namespace
require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Frontbase.php';
use \Modules\Prize\Models\User_Prize_model;
class Front extends Frontbase
{
    /**
     * 红包回调接口
     *
     * @return void
     */
    public function ajaxRedpacketNotify()
    {
        $data = $_POST;
        $user_prize_model=new User_Prize_model();
        $data['code'] = isset($data['code']) ? intval($data['code']) : 0;
        $log_m = new M('log');
        $log_m->add(['echostr' => json_encode($data)]);
        if ($data['code'] > 0) {
            if ($data['data']) {
                foreach ($data['data'] as $item) {
                    if ($item['result_code'] == "SUCCESS") {
                        //修改订单状态
                        $user_prize_model->updateUserRedpacket($item['orderno'],2);
                    } else {
                        if ($item['err_code'] == 'PROCESSING' || $item['err_code'] == 'SYSTEMERROR') {
                            $user_prize_model->updateUserRedpacket($item['orderno'],4,$item['err_code_des']);
                        } else {
                            $user_prize_model->updateUserRedpacket($item['orderno'],3,$item['err_code_des']);
                        }
                    }
                }
            }
            echo "OK";
        } else {
            echo "FAIL";
        }
    }
}
