<?php
namespace Modules\Ydj\Controllers;
use \Modules\Ydj\Models\Ydj_model;
class Api{
    public function __construct(){
    }
    //清空用户中奖数据,恢复奖品数量
    public function resetAllGames(){
        $ydj_model=new Ydj_model();
        $ydj_model->resetAllGames();
    }
}