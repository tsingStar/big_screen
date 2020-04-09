<?php
namespace Modules\Choujiang\Controllers;
use \Modules\Choujiang\Models\Choujiang_model;
class Api{
    public function __construct(){
    }
    //清空用户中奖数据,恢复奖品数量
    public function resetAllGames(){
        $cj_model=new Choujiang_model();
        $cj_model->resetAllGames();
    }
}