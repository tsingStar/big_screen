<?php
namespace Modules\Game\Models;
class ThemeFactory{
    public static function create($name){
        $classname='Modules\\Game\\Models\\Themes\\'.ucfirst($name);
        return new $classname();
    }
}