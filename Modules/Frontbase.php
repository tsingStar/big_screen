<?php
/**
 * 模块后台页面基础类
 * PHP version 5.4+
 *
 * @category Modules
 *
 * @package Frontbase
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
require_once BASEPATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'CacheFactory.php';
/**
 * 模块后台页面基础类
 * PHP version 5.4+
 *
 * @category Modules
 *
 * @package Frontbase
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
class Frontbase
{
    public $_smarty = null;
    public $_current_module_front_template_path = '';

    public $_assets_basepath = '';
    public $_common_templates_basepath = '';
    public $_page_data = null;
    public $_load = null;
    public $_current_module_assets_path = '';
    public $_current_module_front_path = '';
    /**
     * 构造函数
     *
     * @return void
     */
    public function __construct()
    {
        $this->_load = Loader::getInstance();
        $m = ucfirst($_GET['m']);
        $this->_load->setModuleName($m);
        if (!$this->_isAjax()) {
            $this->_current_module_front_path = '/Modules/' . $m . '/templates/front';
            $this->_assets_basepath = '/wall/themes/meepo/assets';
            $this->_current_module_front_template_path = MODULE_PATH . DIRECTORY_SEPARATOR . $m . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'front';
            $this->_current_module_assets_path = '/Modules/' . $m . '/templates/assets';
            $this->_smarty = new Smarty;
            $this->_smarty->caching = false;
            $this->_smarty->compile_dir = BASEPATH . DIRECTORY_SEPARATOR . 'data' .
                DIRECTORY_SEPARATOR . 'templates_c' . DIRECTORY_SEPARATOR;
            $this->_setassetspath();
        }
    }
    /**
     * 检查是否为ajax请求
     *
     * @return bool true表示是ajax请求 false表示不是ajax请求
     */
    private function _isAjax()
    {
        $action = $_GET['a'];
        if (strpos(strtolower($action), 'ajax_') === false) {
            return false;
        }
        return true;
    }

    /**
     * 显示页面
     *
     * @param string $path 模板路径,不需要
     *
     * @return void
     */
    protected function show($path, $custom = false)
    {
        if ($custom == false) {
            $this->_htmlheader();
            $this->_htmlfooter();
        }
        // $this->_sidebar();
        $pat = '/[\/\\\]/';
        preg_match($pat, $path, $matches);
        $path = strtolower($path);
        $newpath = $this->_current_module_front_template_path . DIRECTORY_SEPARATOR . $path;
        // echo $newpath;
        if (count($matches) > 0) {
            if (file_exists($newpath)) {
                $this->_smarty->display($newpath);
            } else {
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
    protected function assign($varname, $varval)
    {
        $this->_smarty->assign($varname, $varval);
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
                $this->_current_module_front_template_path . DIRECTORY_SEPARATOR . 'header.html'
            )
        );
    }
    /**
     * 页面footer内容
     *
     * @return void
     */
    private function _htmlfooter()
    {
        $this->_smarty->assign(
            'html_footer', $this->_smarty->fetch(
                $this->_current_module_front_template_path . DIRECTORY_SEPARATOR . 'footer.html'
            )
        );
    }
    /**
     * 设置页面引入的资源的路径
     *
     * @return void
     */
    private function _setassetspath()
    {
        $this->_smarty->assign('module_front_path', $this->_current_module_front_path);
        $this->_smarty->assign('module_assets', $this->_current_module_assets_path);
        $this->_smarty->assign('assets', $this->_assets_basepath);
        // $this->_smarty->assign('ace_path', $this->_assets_basepath.'/ace_v1.4');
    }
}
