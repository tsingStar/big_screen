<?php
/**
 * 摇大奖模块配置
 * PHP version 5.5+
 * 
 * @category ShakeGame
 * 
 * @package ShakeGame
 * 
 * @author fy <jhfangying@qq.com>
 * 
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 * 
 * @link link('官网','http://www.deeja.top');
 * */

$config=[
    "admin"=>[
        'menu'=>[
        "name"=>"游戏设置",
        "link"=>"/Modules/module.php?m=game&c=admin&a=index"
        ]
    ],
    "front"=>[
        "menu"=>[
            "name"=>"游戏","link"=>"/Modules/module.php?m=game&c=front&a=index",
            "icon"=>"/wall/themes/meepo/assets/images/icon/game.png",
            "shortcut"=>"ctrl+x"
        ]
    ],
    "mobile"=>[
        "menu"=>[
            "name"=>"游戏",
            "link"=>"/Modules/module.php?m=game&c=mobile&a=index",
            "icon"=>""
        ]
    ]
];