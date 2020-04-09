<?php
/**
 * 这里是工厂模式,策略模式也可以实现这个
 */
namespace Modules\Lottery\Models;
class LotteryThemeFactory{
    public static function create($name){
        $classname='Modules\\Lottery\\Models\\Themes\\'.ucfirst($name);
        return new $classname();
    }
}