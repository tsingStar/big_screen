<?php
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'Loader.php';
class Basemodel
{
    protected $_load=null;
    public function __construct(){
        $this->_load=Loader::getInstance();
    }
}