<?php
$config=[
    "admin"=>[
        'menu'=>[
            "name"=>"导入抽奖设置",
            "link"=>"/Modules/module.php?m=importlottery&c=admin&a=index"
        ]
    ],
    "front"=>[
        "menu"=>[
            "name"=>"导入抽奖",
            "link"=>"/Modules/module.php?m=importlottery&c=front&a=index",
            "icon"=>"/Modules/Importlottery/templates/assets/images/icon.png","shortcut"=>"ctrl+m"
        ]
    ]
];

// array(
//     "admin"=>array('menu'=>array(
//         "name"=>"导入抽奖设置",
//         "linke"=>"/Modules/module.php?m=importlottery&c=admin&a=index"
//     )),
//     // "admin"=>array('menu'=>array(
//     //     "name"=>"导入抽奖设置","submenu"=>array(
//     //         array(
//     //             "name"=>"导入抽奖信息", 
//     //             "link"=>"/Modules/module.php?m=importlottery&c=admin&a=index"
//     //         ),
//     //         array(
//     //             'name'=>"奖品设置",
//     //             'link'=>"/Modules/module.php?m=prize&c=admin&a=index&plugname=importlottery&activityid=1"
//     //         ),
//     //         array(
//     //             'name'=>"内定设置",
//     //             'link'=>'/Modules/module.php?m=importlottery&c=admin&a=designated',
//     //         ),
//     //         array(
//     //             'name'=>'中奖信息',
//     //             'link'=>'/Modules/module.php?m=importlottery&c=admin&a=zjlist',
//     //         )
//     //     )
//     // )),
//     "front"=>array("menu"=>array(
//         "name"=>"导入抽奖","link"=>"/Modules/module.php?m=importlottery&c=front&a=index","icon"=>"/Modules/Importlottery/templates/assets/images/icon.png","shortcut"=>"ctrl+m"
//     ))
// );