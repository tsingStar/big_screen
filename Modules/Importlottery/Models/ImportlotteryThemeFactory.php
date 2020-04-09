<?php
/**
 * 这里是工厂模式,策略模式也可以实现这个
 */
namespace Modules\Importlottery\Models;
class ImportlotteryThemeFactory{
    public static function create($name){
        $classname='Modules\\Importlottery\\Models\\Themes\\'.ucfirst($name);
        return new $classname();
    }
}