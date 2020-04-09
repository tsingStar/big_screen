<?php
/**
 * 这里是工厂模式,策略模式也可以实现这个
 */
namespace Modules\Page\Models;
class PageThemeFactory{
    public static function create($type){
        $type_text=['','Pc','Mobile','Sign'];
        $classname=isset($type_text[$type])?$type_text[$type]:'';
        if($classname==''){
            throw new Exception('页面类型错误');
        }
        
        $classname='Modules\\Page\\Models\\Themes\\'.$classname;
        return new $classname();
    }
}