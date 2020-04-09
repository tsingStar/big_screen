<?php
header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__) . '/../common/db.class.php';
require_once(dirname(__FILE__) . '/../common/session_helper.php');

$posts = $_POST;
foreach ($posts as $k => $v) {
    $posts[$k] = trim($v);
}


if (!isset($posts["userpwd"])|| !isset($posts["username"])) {
    echo "用户或密码错误";
    echo '<a href="login.php">点击这里返回</a>';
    $str = <<<eot
<script language="javascript" type="text/javascript">
setTimeout("javascript:location.href='login.php'", 3000);
</script>
eot;
    echo "$str";
    return;
}

$pwd =addslashes($posts["userpwd"]);
$username = addslashes($posts["username"]);
$admin_m=new M('admin');
$sql="`user` = '{$username}' AND  `pwd` =  '{$pwd}'";


$userinfo=$admin_m->find($sql);

if (!empty($userinfo)) {
    include_once '../common/session_helper.php';
    $_SESSION['admin'] = true;
    header("location:index.php");
} else {
    echo "用户或密码错误";
    echo '<a href="login.php">点击这里返回</a>';
    $str = <<<eot
<script language="javascript" type="text/javascript">
setTimeout("javascript:location.href='login.php'", 3000);
</script>
eot;
    echo "$str";
}
