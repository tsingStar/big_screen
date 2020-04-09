<?php
/**
 * 现场活动大屏幕后台页面基类
 * PHP version 5.4+
 * 
 * @category Myadmin
 * 
 * @package Page
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
@header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'db.class.php';
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'Loader.php';
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'CacheFactory.php';
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'session_helper.php';

/**
 * 现场活动大屏幕后台页面基类
 * PHP version 5.4+
 * 
 * @category Myadmin
 * 
 * @package Page
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
class Page
{
    var $_wall_config;
    var $_admin;
    var $_smarty;
    var $_load=null;
    /**
     * 构造函数
     * 
     * @return void
     */
    function __construct()
    {
        $this->_checkprivilege();
        $this->_load=Loader::getInstance();

        $admin_m=new M('admin');
        $this->_admin=$admin_m->find('1 limit 1');
        
        $this->_load->model('Wall_model');
        $this->_wall_config=$this->_load->wall_model->getConfig();// getWallConf();

        $this->_smarty=new Smarty;
        $this->_smarty->caching = false;
        
        $apppath=str_replace(DIRECTORY_SEPARATOR.'myadmin', '', dirname(__FILE__));
        
        $this->_smarty->compile_dir = $apppath.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
        $webpath=$_SERVER['HTTP_HOST'];
        $this->assign('admin', $this->_admin);
        $this->assign('wall_config', $this->_wall_config);
        $this->assign('domain', $webpath);
        $this->_load->model('Plugs_model');
        $plugs=$this->_load->plugs_model->getPlugs(1);
        $modules_configs=array();
        foreach ($plugs as $k=>$v) {
            if ($v['ismodule']==1) {
                $configfilepath=$apppath.DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.ucfirst($v['name']) .DIRECTORY_SEPARATOR.'config.php';
                if (file_exists($configfilepath)) {
                    include $configfilepath;
                    $modules_configs[$v['name']]['config']=$config;
                }
            }
        }
        $this->assign('plugs',$plugs);
        $this->assign('enabledplug_configs', $modules_configs);

    }

    // function 
    /**
     * 设置页面标题
     * 
     * @param text $title 页面标题
     * 
     * @return void
     */
    function setTitle($title)
    {
        $this->assign('title', $title);
    }

    /**
     * 设置页面标题
     * 
     * @return void
     */
    private function _checkprivilege()
    {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
            $_SESSION['admin'] = false;
            echo "<script>window.location='login.php';</script>";
            exit();
        }
    }

    /**
     * 传递参与到模板
     * 
     * @param text  $varname 参与名称
     * @param mixed $varval  参数值
     * 
     * @return void
     */
    public function assign($varname,$varval)
    {
        // if()
        $this->_smarty->assign($varname, $varval);
    }
    /**
     * 显示页面
     * 
     * @param text $path 模板文件的路径
     * 
     * @return void
     */
    public function display($path)
    {
        $this->showdiyad();
        $this->_smarty->display($path);
    }
    /**
     * 是否传入定制广告
     * 
     * @return void
     */
    public function showdiyad()
    {
        $html='';
        $servername=$_SERVER['HTTP_HOST'];
        $isshow=strpos($servername, 'fangying.com')!==false || strpos($servername, 'vdcom.cn')!==false || strpos($servername, 'wxmiao.com')!==false;
        if ($isshow) {
            $html=<<<EOF
        <div class="alert alert-danger">
        <i class="fa fa-external-link"></i>
        <a href="https://item.taobao.com/item.htm?id=571761346181" target="_blank"> <strong>点此联系设计师定制 </strong></a>
    </div>
EOF;
        }
        
        $this->_smarty->assign('diyad', $html);
    }
    /**
     * 未定
     * 
     * @return void
     */
    public function show()
    {
        
    }
    /**
     * 左侧菜单
     * 
     * @param array $plugs 组件清单
     * 
     * @return void
     */
    public function sidebar($plugs)
    {

    }
    /**
     * 生成分页代码
     * 
     * @param int  $page     页码
     * @param int  $pagesize 每页记录数
     * @param int  $rowcount 总记录条数
     * @param text $url      页面链接
     * 
     * @return void
     */
    public function pagerhtml($page,$pagesize,$rowcount,$url)
    {
        if ($rowcount==0) {
            return '';
        }
        $url=strpos($url, '?')===false?$url.'?page=':$url.'&page=';
        $html='<div class="widget-toolbox  clearfix"><ul class="pagination" style="margin:10px 0px;">';
        $pagenum=ceil($rowcount/$pagesize);
        $pagehtml='';
        for ($i=1;$i<=$pagenum;$i++) {
            $class=$i==$page?'class="active"':'';
            $pagehtml.='<li '.$class.'><a href="'.$url.$i.'">'.$i.'</a></li>';
        }
        $firstpagehtml='';
        if ($page==1) {
            $firstpagehtml='<li class="disabled"><a href="#"><i class="ace-icon fa fa-angle-double-left"></i></a></li>';
        } else {
            $firstpagehtml='<li ><a href="'.$url.'1"><i class="ace-icon fa fa-angle-double-left"></i></a></li>';
        }
        $lastpagehtml='';
        if ($page==$pagenum) {
            $lastpagehtml='<li class="disabled"><a href="#"><i class="ace-icon fa fa-angle-double-right"></i></a></li>';
        } else {
            $lastpagehtml='<li ><a href="'.$url.$pagenum.'"><i class="ace-icon fa fa-angle-double-right"></i></a></li>';
        }
        $html.=$firstpagehtml.$pagehtml.$lastpagehtml;
        $html.='<li><a href="###">共 '.$rowcount.'条数据</a></li></ul></div>';
        return $html;
    }
}