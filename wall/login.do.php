<?php
@header("Content-type: text/html; charset=utf-8");
include(dirname(__FILE__) . '/../common/db.class.php');
require_once(dirname(__FILE__) . '/../common/CacheFactory.php');

$load->model('Wall_model');
$wall_config=$load->wall_model->getConfig();

$password=$_GET['password'];
if($password==$wall_config['screenpaw']){
	// require_once('../common/session_helper.php');
	// $_SESSION['views']= true;
	echo '{"code":1}';
}else{
	echo '{"code":-1}';
}
