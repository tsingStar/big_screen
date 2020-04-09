<?php
namespace Modules\Game\Models;

class GameThemes_model{
    var $_gamethemes_m=null;
    public function __construct(){
        $this->_gamethemes_m=new \M('game_themes');
    }

    public function getAll(){
        $data=$this->_gamethemes_m->select('1');
        return $data;
    }
    public function getById($id){
        return $this->_gamethemes_m->find('id='.$id);
    }
}