<?php
namespace Modules\Lottery\Models\Themes;
interface ThemeInterface{
    //传入数组数据
    public function data($data);
    //获取json格式的数据
    public function toJson();
    //获取数组格式的数据
    public function toArray();
    /**
     * 获取序列化的数据,会去掉一些数据,比如文件路劲之类的
     */
    public function serialize();
}