<?php
/**
 * 模块后台页面基础类
 * PHP version 5.4+
 * 
 * @category Modules
 * 
 * @package Adminbase
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */
require_once BASEPATH .DIRECTORY_SEPARATOR. 'common'.DIRECTORY_SEPARATOR.'CacheFactory.php';
/**
 * 模块后台页面基础类
 * PHP version 5.4+
 * 
 * @category Modules
 * 
 * @package Adminbase
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */

class Adminbase
{
    var $_assets_basepath='';
    var $_common_templates_basepath='';
    var $_page_data=null;
    var $_smarty=null;
    var $_current_module_admin_template_path='';
    var $_load=null;
    var $_current_module_assets_path='';
    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        $m=ucfirst($_GET['m']);
        $this->_assets_basepath='/myadmin/assets';
        $this->_common_templates_basepath=BASEPATH.DIRECTORY_SEPARATOR.'myadmin'.DIRECTORY_SEPARATOR.'templates';
        $this->_current_module_assets_path='/Modules/'.$m.'/templates/assets';
        $this->_current_module_admin_template_path=MODULE_PATH.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'admin';
        $this->_smarty=new \Smarty;
        $this->_smarty->caching = false;
        $this->_smarty->compile_dir = BASEPATH.DIRECTORY_SEPARATOR.'data'.
            DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
        $this->_load=Loader::getInstance();
        $this->_load->setModuleName($m);
        $this->_setassetspath();
    }
    /**
     * 显示页面
     * 
     * @param string $path 模板路径,不需要
     * 
     * @return void
     */
    protected function show($path)
    {
        $this->_load->model('Wall_model');
        $wallconfig=$this->_load->wall_model->getConfig();
        $this->_smarty->assign('title', $this->getTitle());
        $this->_smarty->assign('description', $this->getDescription());
        $this->_smarty->assign('wall_config',$wallconfig);
        if(!$this->_isAjax()){
            $this->_htmlheader();
            $this->_htmlfooter();
            $this->_sidebar();
        }
        $pat='/[\/\\\]/';
        preg_match($pat, $path, $matches);
        $path=strtolower($path);
        $newpath=$this->_current_module_admin_template_path.DIRECTORY_SEPARATOR.$path;
        if (count($matches)>0) {
            if(file_exists($newpath)){
                $this->_smarty->display($newpath);
            }else{
                $this->_smarty->display($path);
            }
        } else {
            $this->_smarty->display($newpath);
        }
    }
    /**
     * 设置页面引入的资源的路径
     * 
     * @param string $varname 映射到模板的变量名
     * @param string $varval  映射到模板的变量值
     * 
     * @return void
     */
    protected function assign($varname,$varval)
    {
        $this->_smarty->assign($varname, $varval);
    }
    /**
     * 设置页面引入的资源的路径
     * 
     * @return void
     */
    private function _setassetspath()
    {
        $this->_smarty->assign('module_assets', $this->_current_module_assets_path);
        $this->_smarty->assign('assets', $this->_assets_basepath);
        $this->_smarty->assign('ace_path', $this->_assets_basepath.'/ace_v1.4');
        
    }
    /**
     * 页面header内容
     * 
     * @return void
     */
    private function _htmlheader()
    {
        $this->_smarty->assign(
            'html_header', $this->_smarty->fetch(
                $this->_common_templates_basepath.DIRECTORY_SEPARATOR.'module_html_header.html'
            )
        );
    }
    /**
     * 页面sidebar内容
     * 
     * @return void
     */
    private function _sidebar()
    {
        $this->_load->model('Plugs_model');
        $plugs=$this->_load->plugs_model->getPlugs(1);
        $modules_configs=array();
        foreach ($plugs as $k=>$v) {
            if ($v['ismodule']==1) {
                $configfilepath=BASEPATH.DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.ucfirst($v['name']).DIRECTORY_SEPARATOR.'config.php';
                if (file_exists($configfilepath)) {
                    include $configfilepath;
                    $modules_configs[$v['name']]['config']=$config;
                }
                
            }
        }
        $this->_smarty->assign('enabledplug_configs', $modules_configs);
        $this->_smarty->assign('plugs',$plugs);
        $this->_smarty->assign(
            'html_sidebar', $this->_smarty->fetch(
                $this->_common_templates_basepath.DIRECTORY_SEPARATOR.'module_html_sidebar.html'
            )
        );
        
    }
    private function _isAjax(){
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){ 
            // ajax 请求的处理方式 
            return true;
        }else{ 
            return false;
            // 正常请求的处理方式 
        };
    }
    /**
     * 页面footer内容
     * 
     * @return void
     */
    private function _htmlfooter()
    { 
        
        $this->_smarty->assign(
            'html_footercontent', $this->_smarty->fetch(
                $this->_common_templates_basepath.DIRECTORY_SEPARATOR.'module_html_footercontent.html'
            )
        );
        $this->_smarty->assign(
            'html_footer', $this->_smarty->fetch(
                $this->_common_templates_basepath.DIRECTORY_SEPARATOR.'module_html_footer.html'
            )
        );
        
    }

    var $_title='';
    var $_description='';
    /**
     * 获取页面标题
     * 
     * @return void
     */
    protected function getTitle()
    {
        return empty($this->_title)?'无标题':$this->_title;
    }

    /**
     * 设置页面标题
     * 
     * @param string $title 标题文字
     * 
     * @return void
     */
    protected function setTitle($title)
    {
        $this->_title=$title;
    }
    /**
     * 获取页面描述
     * 
     * @return void
     */
    protected function getDescription()
    {
        return empty($this->_description)?'':$this->_description;
    }

    /**
     * 设置页面描述
     * 
     * @param string $description 描述
     * 
     * @return void
     */
    protected function setDescription($description)
    {
        $this->_description=$description;
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
    public function pagerhtml($page,$pagesize,$rowcount,$url='')
    {
        if ($rowcount==0) {
            return '';
        }
        if($url!=''){
            $url=strpos($url, '?')===false?$url.'?page=':$url.'&page=';
        }
        
        $html='<div class="widget-toolbox  clearfix"><ul class="pagination" style="margin:10px 0px;">';
        $pagenum=ceil($rowcount/$pagesize);
        $pagehtml='';
        for ($i=1;$i<=$pagenum;$i++) {
            $class=$i==$page?'class="active"':'';
            $pagebtnclass=$i==$page?'':'class="pagebtn"';
            $link=$url==''?'###':($url.$i);
            $link=$pagebtnclass=$i==$page?'###':$link;
            $pagehtml.='<li '.$class.'><a href="'.$link.'" '.$pagebtnclass.' pageno="'.$i.'">'.$i.'</a></li>';
        }
        $firstpagehtml='';
        if ($page==1) {
            $firstpagehtml='<li class="disabled"><a href="#"><i class="ace-icon fa fa-angle-double-left"></i></a></li>';
        } else {
            $link=$url==''?'###':($url.'1');
            $firstpagehtml='<li ><a href="'.$link.'" class="pagebtn" pageno="1"><i class="ace-icon fa fa-angle-double-left"></i></a></li>';
        }
        $lastpagehtml='';
        if ($page==$pagenum) {
            $lastpagehtml='<li class="disabled"><a href="#"><i class="ace-icon fa fa-angle-double-right"></i></a></li>';
        } else {
            $link=$url==''?'###':($url.$pagenum);
            $lastpagehtml='<li ><a href="'.$link.'" class="pagebtn" pageno="'.$pagenum.'"><i class="ace-icon fa fa-angle-double-right"></i></a></li>';
        }
        $html.=$firstpagehtml.$pagehtml.$lastpagehtml;
        $html.='<li><a href="###">共 '.$rowcount.'条数据</a></li></ul></div>';
        return $html;
    }


}