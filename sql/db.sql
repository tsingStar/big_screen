DROP TABLE IF EXISTS `weixin_admin`;
CREATE TABLE `weixin_admin` (
  `user` text NOT NULL,
  `pwd` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `weixin_aliyunoss`;
CREATE TABLE `weixin_aliyunoss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OSS_ACCESS_ID` varchar(255) DEFAULT NULL COMMENT 'ACCESS_ID',
  `OSS_ACCESS_KEY` varchar(255) DEFAULT NULL COMMENT 'ACCESS_KEY',
  `ALI_LOG` tinyint(1) DEFAULT '1' COMMENT '1不记录日志2记录日志',
  `ALI_LOG_PATH` varchar(255) DEFAULT NULL COMMENT '日志存放路径',
  `ALI_DISPLAY_LOG` tinyint(1) DEFAULT '1' COMMENT '是否显示日志输出1不显示2显示',
  `BUCKET_NAME` varchar(255) DEFAULT NULL COMMENT 'bucket名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='阿里云oss配置表';

DROP TABLE IF EXISTS `weixin_attachments`;
CREATE TABLE `weixin_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filepath` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `extension` varchar(10) DEFAULT NULL COMMENT '扩展名',
  `type` tinyint(1) DEFAULT NULL COMMENT '1本地文件2阿里云3新浪云',
  `filemd5` varchar(32) DEFAULT NULL COMMENT '文件名和文件大小组合的md5值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='附件表';

INSERT INTO `weixin_attachments` VALUES (1, '/wall/themes/meepo/assets/images/shake/worldcup/football1.gif', 'gif', 1, '5aa0060a952e110c494530036fd5c5b7');
INSERT INTO `weixin_attachments` VALUES (2, '/wall/themes/meepo/assets/images/shake/worldcup/football2.gif', 'gif', 1, 'a8c965007337f76590b8dfdf2827a7a6');
INSERT INTO `weixin_attachments` VALUES (3, '/wall/themes/meepo/assets/images/shake/worldcup/football3.gif', 'gif', 1, '0fd0c04960211d1a55328cdda9e57551');
INSERT INTO `weixin_attachments` VALUES (4, '/wall/themes/meepo/assets/images/shake/worldcup/football4.gif', 'gif', 1, 'c860c8a5b63b76d6b73b4e2dbe6302d6');
INSERT INTO `weixin_attachments` VALUES (5, '/wall/themes/meepo/assets/images/shake/worldcup/football5.gif', 'gif', 1, 'c377de39abfdb7c0f73640f63a4892bf');
INSERT INTO `weixin_attachments` VALUES (6, '/wall/themes/meepo/assets/images/shake/worldcup/football6.gif', 'gif', 1, '32752496889ac4ee5b86137d4a8cfbd2');
INSERT INTO `weixin_attachments` VALUES (7, '/wall/themes/meepo/assets/images/shake/worldcup/football7.gif', 'gif', 1, 'c7e222195ede65f1717a2183f69ec09f');
INSERT INTO `weixin_attachments` VALUES (8, '/wall/themes/meepo/assets/images/shake/worldcup/football8.gif', 'gif', 1, '1c3e027d25b720a4368a39c6957b2ab8');
INSERT INTO `weixin_attachments` VALUES (9, '/wall/themes/meepo/assets/images/shake/worldcup/football9.gif', 'gif', 1, 'e7f176ee2343b1f99873d833fea8fb8a');
INSERT INTO `weixin_attachments` VALUES (10, '/wall/themes/meepo/assets/images/shake/worldcup/football10.gif', 'gif', 1, '8b08e371aa58e2b65f879ad9fc590b02');
INSERT INTO `weixin_attachments` VALUES (11, '/wall/themes/meepo/assets/images/shake/worldcup/bg.mp4', 'mp4', 1, 'a87fbbee00734ea2f134c9d87f936207');
INSERT INTO `weixin_attachments` VALUES (12, '/mobile/template/app/images/shake/shake2.png', 'png', 1, '0342d812ad0da3bbcea87abaf93617d9');
INSERT INTO `weixin_attachments` VALUES (13, '/Modules/Choujiang/templates/mobile/guaguaka/assets/images/icon.png', 'png', 1, '0e491cae8fd78b87542c36eba4bbf35c');
INSERT INTO `weixin_attachments` VALUES (14, '/wall/themes/meepo/assets/images/shake/pig/pig.gif', 'gif', 1, '874636879e8a6062e7321987fcd77d38');
INSERT INTO `weixin_attachments` VALUES (15, '/wall/themes/meepo/assets/images/shake/pig/shake0.png', 'png', 1, '7e7235957843aa42b10d312ceb05f265');


DROP TABLE IF EXISTS `weixin_background`;
CREATE TABLE `weixin_background`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attachmentid` int(11) NULL DEFAULT NULL COMMENT '背景图id',
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `plugname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关联的组件名',
  `bgtype` tinyint(1) NULL DEFAULT NULL COMMENT '1图片2视频',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

INSERT INTO `weixin_background` VALUES (1, NULL, '签到墙背景', 'qdq', 1);
INSERT INTO `weixin_background` VALUES (2, NULL, '微信上墙背景', 'wall', 1);
INSERT INTO `weixin_background` VALUES (3, NULL, '对对碰背景', 'ddp', 1);
INSERT INTO `weixin_background` VALUES (4, NULL, '投票背景', 'vote', 1);
INSERT INTO `weixin_background` VALUES (5, NULL, '幸运手机号背景', 'xysjh', 1);
INSERT INTO `weixin_background` VALUES (6, NULL, '幸运号码背景', 'xyh', 1);
INSERT INTO `weixin_background` VALUES (7, NULL, '相册背景', 'xiangce', 1);
INSERT INTO `weixin_background` VALUES (8, NULL, '红包雨背景', 'redpacket', 1);
INSERT INTO `weixin_background` VALUES (9, NULL, '摇大奖背景', 'ydj', 1);
INSERT INTO `weixin_background` VALUES (10, NULL, '3D签到背景图', 'threedimensionalsign', 1);


DROP TABLE IF EXISTS `weixin_cookie`;
CREATE TABLE `weixin_cookie` (
  `cookie` text NOT NULL,
  `cookies` text NOT NULL,
  `token` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `weixin_choujiang_config`;
CREATE TABLE `weixin_choujiang_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `themeid` int(11) DEFAULT NULL COMMENT '主题id',
  `showleftnum` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1表示显示剩余数量2表示不显示剩余数量',
  `defaultnum` tinyint(3) DEFAULT NULL COMMENT '默认的抽取次数',
  `description` text COMMENT '游戏说明',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `started_at` int(11) DEFAULT NULL COMMENT '开始时间',
  `ended_at` int(11) DEFAULT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抽奖配置表';

DROP TABLE IF EXISTS `weixin_choujiang_themes`;
CREATE TABLE `weixin_choujiang_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `themepath` varchar(255) DEFAULT NULL COMMENT '主题路径',
  `themename` varchar(255) DEFAULT NULL COMMENT '主题名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='抽奖主题';

INSERT INTO `weixin_choujiang_themes` VALUES (1, 'guaguaka', '刮刮卡');

DROP TABLE IF EXISTS `weixin_choujiang_users`;
CREATE TABLE `weixin_choujiang_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `choujiangid` int(11) DEFAULT NULL COMMENT '抽奖配置id',
  `userid` int(11) DEFAULT NULL,
  `cjtimes` tinyint(3) DEFAULT NULL COMMENT '抽取的次数',
  `lefttimes` tinyint(3) DEFAULT NULL COMMENT '剩余次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设置每个人可以抽几次奖，如果没有记录则使用抽奖配置中的默认次数';

DROP TABLE IF EXISTS `weixin_danmu_config`;
CREATE TABLE `weixin_danmu_config` (
  `id` int(11) NOT NULL COMMENT 'id',
  `danmuswitch` tinyint(1) DEFAULT '1' COMMENT '1表示关2表示开',
  `textcolor` varchar(7) CHARACTER SET utf8 DEFAULT NULL COMMENT '16进制颜色值',
  `looptime` int(3) DEFAULT NULL COMMENT '消息显示的时间间隔，单位是秒',
  `isloop` tinyint(1) DEFAULT NULL COMMENT '1表示不循环2表示循环',
  `historynum` int(3) DEFAULT NULL COMMENT '循环时使用的历史记录条数',
  `positionmode` tinyint(1) DEFAULT NULL COMMENT '1表示上三分之一2表示中间三分之一3表示下三分之一4表示全屏随机',
  `showname` tinyint(1) DEFAULT NULL COMMENT '1不显示昵称2显示昵称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `weixin_danmu_config`(`id`, `danmuswitch`, `textcolor`, `looptime`, `isloop`, `historynum`, `positionmode`, `showname`) VALUES (1, 1, '#b7e692', 3, 2, 30, 4, 2);


DROP TABLE IF EXISTS `weixin_flag`;
CREATE TABLE `weixin_flag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `flag` int(11) DEFAULT NULL COMMENT '1表示未签到2表示签到成功',
  `nickname` varchar(255) DEFAULT NULL COMMENT '微信昵称',
  `avatar` text COMMENT '微信头像',
  `sex` varchar(255) DEFAULT NULL COMMENT '性别',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常2禁用（禁用状态不能使用任何功能）',
  `datetime` int(10) DEFAULT NULL,
  `fromtype` varchar(25) DEFAULT NULL COMMENT '签到来源weixin',
  `rentopenid` varchar(28) DEFAULT NULL COMMENT '借用来openid',
  `signname` varchar(32) NOT NULL DEFAULT '' COMMENT '签到记录的姓名',
  `phone` varchar(11) DEFAULT NULL COMMENT '电话',
  `signorder` int(11) DEFAULT NULL COMMENT '签到顺序',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rentopenid` (`rentopenid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户微信信息表';

DROP TABLE IF EXISTS `weixin_flag_config`;
CREATE TABLE `weixin_flag_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reserved_infomation_match` tinyint(1) DEFAULT '1' COMMENT '1表示不完全匹配2表示完全匹配',
  `reserved_infomation_verify` tinyint(1) DEFAULT '1' COMMENT '1表示通过2表示不通过审核',
  `reserved_infomation_csv_attachmentid` int(11) DEFAULT '0' COMMENT '上传的csv位置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到用户配置';

INSERT INTO `weixin_flag_config`(`id`, `reserved_infomation_match`, `reserved_infomation_verify`, `reserved_infomation_csv_attachmentid`) VALUES (1, 1, 1, 0);

DROP TABLE IF EXISTS `weixin_flag_reserved_infomation`;
CREATE TABLE `weixin_flag_reserved_infomation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `realname` varchar(30) DEFAULT NULL COMMENT '姓名',
  `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
  `info` varchar(255) DEFAULT NULL COMMENT '预留信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到用户预留信息表，在用户签到时如果匹配到其中的数据，就会把这个数据显示到用户的签到界面上';

DROP TABLE IF EXISTS `weixin_flag_extention_column_type`;
CREATE TABLE `weixin_flag_extention_column_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordernum` int(11) DEFAULT NULL COMMENT '排序号',
  `coltype` varchar(20) DEFAULT NULL COMMENT '字段类型',
  `title` varchar(50) DEFAULT NULL COMMENT '字段名称',
  `placeholder` varchar(255) DEFAULT NULL COMMENT '占位内容',
  `options` text COMMENT '选项内容',
  `defaultvalue` text COMMENT '默认内容',
  `ismust` tinyint(1) DEFAULT NULL COMMENT '1不是必填2必填',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `weixin_flag_extention_data`;
CREATE TABLE `weixin_flag_extention_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL COMMENT '用户id',
  `datastr` text COMMENT '内容',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `userid_index`(`userid`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `weixin_game_config`;
CREATE TABLE `weixin_game_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toprank` int(5) DEFAULT '3' COMMENT '前几名获奖',
  `winagain` tinyint(1) DEFAULT '1' COMMENT '1表示不能重复2表示可以重复获奖，默认是1',
  `status` tinyint(1) DEFAULT '1' COMMENT '1表示未开始，2进行中，3表示结束',
  `showtype` varchar(32) DEFAULT NULL COMMENT '默认是nickname',
  `themeid` int(11) DEFAULT NULL COMMENT '主题id',
  `themeconfig` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='摇一摇游戏配置表';


INSERT INTO `weixin_game_config` (`id`, `toprank`, `winagain`, `status`, `showtype`, `themeid`, `themeconfig`) VALUES (1, 10, 1, 1, 'nickname', 13, '');
INSERT INTO `weixin_game_config` (`id`, `toprank`, `winagain`, `status`, `showtype`, `themeid`, `themeconfig`) VALUES (2, 10, 1, 1, 'nickname', 1, '');
INSERT INTO `weixin_game_config` (`id`, `toprank`, `winagain`, `status`, `showtype`, `themeid`, `themeconfig`) VALUES (3, 10, 1, 1, 'nickname', 2, '');
INSERT INTO `weixin_game_config` (`id`, `toprank`, `winagain`, `status`, `showtype`, `themeid`, `themeconfig`) VALUES (4, 10, 1, 1, 'nickname', 3, '');
INSERT INTO `weixin_game_config` (`id`, `toprank`, `winagain`, `status`, `showtype`, `themeid`, `themeconfig`) VALUES (5, 10, 1, 1, 'nickname', 4, '');
INSERT INTO `weixin_game_config` (`id`, `toprank`, `winagain`, `status`, `showtype`, `themeid`, `themeconfig`) VALUES (6, 10, 1, 1, 'nickname', 5, '');
INSERT INTO `weixin_game_config` (`id`, `toprank`, `winagain`, `status`, `showtype`, `themeid`, `themeconfig`) VALUES (7, 10, 1, 1, 'nickname', 6, '');
INSERT INTO `weixin_game_config` (`id`, `toprank`, `winagain`, `status`, `showtype`, `themeid`, `themeconfig`) VALUES (8, 10, 1, 1, 'nickname', 7, '');
INSERT INTO `weixin_game_config` (`id`, `toprank`, `winagain`, `status`, `showtype`, `themeid`, `themeconfig`) VALUES (9, 10, 1, 1, 'nickname', 8, '');

DROP TABLE IF EXISTS `weixin_game_records`;
CREATE TABLE `weixin_game_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `gameid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏记录';

DROP TABLE IF EXISTS `weixin_game_themes`;
CREATE TABLE `weixin_game_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `themename` varchar(32) DEFAULT NULL COMMENT '主题名称',
  `themepath` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `weixin_game_themes` VALUES (1, '默认汽车主题', 'Racing');
INSERT INTO `weixin_game_themes` VALUES (2, '猴子爬树', 'Monkey');
INSERT INTO `weixin_game_themes` VALUES (3, '数钱游戏',  'Money');
INSERT INTO `weixin_game_themes` VALUES (4, '金猪送福',  'Pig');
INSERT INTO `weixin_game_themes` VALUES (5, '赛跑',  'Runner');
INSERT INTO `weixin_game_themes` VALUES (6, '赛龙舟',  'DragonBoat');
INSERT INTO `weixin_game_themes` VALUES (7, '赛车',  'Car');
INSERT INTO `weixin_game_themes` VALUES (8, '赛马',  'Horse');
INSERT INTO `weixin_game_themes` VALUES (9, '游艇',  'Yacht');
INSERT INTO `weixin_game_themes` VALUES (10, '丘比特之箭',  'Qiubite');
INSERT INTO `weixin_game_themes` VALUES (11, '欢乐六一',  'Happy61');
INSERT INTO `weixin_game_themes` VALUES (12, '爱在七夕',  'QiXi');
INSERT INTO `weixin_game_themes` VALUES (13, '浓情中秋',  'ZhongQiu');


DROP TABLE IF EXISTS `weixin_importlottery`;
CREATE TABLE `weixin_importlottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datarow` text,
  `imgid` int(11) DEFAULT NULL,
  `configid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `weixin_importlottery_config`;
CREATE TABLE `weixin_importlottery_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `themeid` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `themeconfig` text NOT NULL,
  `metadata` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `weixin_importlottery_themes`;
CREATE TABLE `weixin_importlottery_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `themename` varchar(255) NOT NULL,
  `themepath` varchar(255) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='导入抽奖主题';

INSERT INTO `weixin_importlottery_themes` (`id`, `themename`, `themepath`, `created`) VALUES
(1, '3D形式', 'ThreeDimensional', 1),
(2, '开盘摇号', 'OpeningRoll', 1);




DROP TABLE IF EXISTS `weixin_lottery_config`;
CREATE TABLE `weixin_lottery_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '主题名称',
  `themeid` int(11) DEFAULT NULL COMMENT '主题id',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `winagain` tinyint(1) DEFAULT NULL COMMENT '之前得过奖的还能再次参与 1表示不可以，2表示可以',
  `showtype` varchar(255) DEFAULT '' COMMENT '默认是nickname',
  `themeconfig` text COMMENT '主题的配置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='抽奖配置表';


INSERT INTO `weixin_lottery_config` VALUES (1, '第1轮活动', '4', '1', '1', 'nickname', '');
INSERT INTO `weixin_lottery_config` VALUES (2, '第2轮活动', '1', '1', '1', 'nickname', '');
INSERT INTO `weixin_lottery_config` VALUES (3, '第3轮活动', '2', '1', '1', 'nickname', '');
INSERT INTO `weixin_lottery_config` VALUES (4, '第4轮活动', '3', '1', '1', 'nickname', '');

DROP TABLE IF EXISTS `weixin_lottery_themes`;
CREATE TABLE `weixin_lottery_themes` (
  `id` int(11) NOT NULL,
  `themename` varchar(255) DEFAULT NULL COMMENT '主题名称',
  `themepath` varchar(255) DEFAULT NULL COMMENT '主题文件存放的目录',
  `created_at` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `weixin_lottery_themes` VALUES (1, '3D抽奖', 'ThreeDimensional', '1');
INSERT INTO `weixin_lottery_themes` VALUES (2, '砸金蛋', 'Zjd', '2');
INSERT INTO `weixin_lottery_themes` VALUES (3, '抽奖箱', 'Cjx', '3');
INSERT INTO `weixin_lottery_themes` VALUES (4, '巨幕抽奖', 'New3D', '4');

DROP TABLE IF EXISTS `weixin_plugs`;
CREATE TABLE `weixin_plugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '模块名',
  `title` varchar(255) DEFAULT NULL COMMENT '模块中文名',
  `switch` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0' COMMENT '1表示开2表示关',
  `url` varchar(255) DEFAULT NULL COMMENT 'url',
  `img` varchar(255) DEFAULT NULL COMMENT '图标',
  `ordernum` tinyint(3) unsigned zerofill DEFAULT '000' COMMENT '排序号',
  `choujiang` tinyint(1) unsigned zerofill DEFAULT '0' COMMENT '0表示不是抽奖项目1表示不能重复中奖，2表示可以重复中奖',
  `hotkey` varchar(10) DEFAULT NULL COMMENT '快捷键',
  `ismodule` tinyint(1) DEFAULT '2' COMMENT '1表示是组件2表示不是，默认为2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


INSERT INTO `weixin_plugs` VALUES (1, 'qdq', '签到墙', 1, '/wall/index.php', 'themes/meepo/assets/images/icon/ico005.png', 001, 0, 'ctrl+1', 2);
INSERT INTO `weixin_plugs` VALUES (2, 'ddp', '对对碰', 1, '/wall/ddp.php', 'themes/meepo/assets/images/icon/ico006.png', 010, 0, 'ctrl+8', 2);
INSERT INTO `weixin_plugs` VALUES (3, 'vote', '投票', 1, '/wall/vote.php', 'themes/meepo/assets/images/icon/ico004.png', 005, 0, 'ctrl+5', 2);
INSERT INTO `weixin_plugs` VALUES (4, 'xysjh', '幸运手机号', 1, '/wall/xysjh.php', 'themes/meepo/assets/images/icon/ico019.png', 008, 0, 'ctrl+7', 2);
INSERT INTO `weixin_plugs` VALUES (5, 'xyh', '幸运号码', 1, '/wall/xyh.php', 'themes/meepo/assets/images/icon/ico016.png', 007, 0, 'ctrl+6', 2);
INSERT INTO `weixin_plugs` VALUES (6, 'threedimensionalsign', '3D签到', 1, '/wall/3dsign.php', 'themes/meepo/assets/images/icon/ico013.png', 002, 0, 'ctrl+2', 2);
INSERT INTO `weixin_plugs` VALUES (7, 'wall', '微信上墙', 1, '/wall/wall.php', 'themes/meepo/assets/images/icon/ico009.png', 003, 0, 'ctrl+3', 2);
INSERT INTO `weixin_plugs` VALUES (8, 'xiangce', '相册', 1, '/wall/xiangce.php', 'themes/meepo/assets/images/icon/ico003.png', 012, 0, 'ctrl+9', 2);
INSERT INTO `weixin_plugs` VALUES (9, 'redpacket', '红包雨', 1, '/wall/redpacket.php', 'themes/meepo/assets/images/icon/redpack3.png', 016, 0, 'ctrl+r', 2);
INSERT INTO `weixin_plugs` VALUES (10, 'ydj', '摇大奖', 1, NULL, NULL, 020, 1, 'ctrl+y', 1);
INSERT INTO `weixin_plugs` VALUES (11, 'choujiang', '手机端抽奖', 1, NULL, NULL, 021, 1, '', 1);
INSERT INTO `weixin_plugs` VALUES (12, 'importlottery', '导入抽奖', 1, NULL, NULL, 022, 1, '', 1);
INSERT INTO `weixin_plugs` VALUES (13, 'lottery', '抽奖', '1', null, null, '023', 1, null, 1);
INSERT INTO `weixin_plugs` VALUES (14, 'game', '游戏', '1', null, null, '024', 1, null, 1);
INSERT INTO `weixin_plugs` VALUES (15, 'page', '单页', '1', null, null, '024', 0, null, 1);

DROP TABLE IF EXISTS `weixin_pages`;
CREATE TABLE `weixin_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagedata` text NOT NULL,
  `title` varchar(32) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='单页配置表';


DROP TABLE IF EXISTS `weixin_redpacket_config`;
CREATE TABLE `weixin_redpacket_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule` text COMMENT '抢红包规则',
  `tips` text COMMENT '提示语',
  `sendname` varchar(32) DEFAULT NULL COMMENT '红包发送者名称',
   `wishing` varchar(128) DEFAULT NULL COMMENT '祝福语',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='红包配置信息';

INSERT INTO `weixin_redpacket_config` VALUES (1,'1.用户打开微信扫描大屏幕上的二维码进入等待抢红包页面\n2.主持人说开始后，大屏幕和手机页面同时落下红包雨\n3.用户随机选择落下的红包，并拆开红包。\n4.如果倒计时还在继续，那么无论用户是否抢到了，都可以继续抢 直到倒计时完成。','大屏幕倒计时开始，\n红包将从大屏幕降落到手机，此时\n手指戳红包即可参与\n抢红包游戏','','');

DROP TABLE IF EXISTS `weixin_redpacket_order_return`;
CREATE TABLE `weixin_redpacket_order_return` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `return_code` varchar(16) DEFAULT NULL COMMENT '返回状态吗',
  `return_msg` varchar(128) DEFAULT NULL COMMENT '返回信息表',
  `sign` varchar(32) DEFAULT NULL COMMENT '签名信息',
  `result_code` varchar(16) DEFAULT NULL COMMENT '业务结果',
  `err_code` varchar(32) DEFAULT NULL COMMENT '错误代码',
  `err_code_des` varchar(128) DEFAULT NULL COMMENT '错误代码描述',
  `mch_billno` varchar(28) DEFAULT NULL COMMENT '商户订单号',
  `mch_id` varchar(32) DEFAULT NULL COMMENT '商户号',
  `wxappid` varchar(32) DEFAULT NULL COMMENT '公众号appid',
  `re_openid` varchar(32) DEFAULT NULL COMMENT '收红包用户的openid',
  `total_amount` int(11) DEFAULT NULL COMMENT '付款金额',
  `send_listid` varchar(32) DEFAULT NULL COMMENT '微信单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发红包返回信息表';

DROP TABLE IF EXISTS `weixin_redpacket_orders`;
CREATE TABLE `weixin_redpacket_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `mch_billno` varchar(28) DEFAULT NULL COMMENT '商户订单号',
  `mch_id` varchar(32) DEFAULT NULL COMMENT '商户号',
  `wxappid` varchar(32) DEFAULT NULL COMMENT '公众号appid',
  `send_name` varchar(32) DEFAULT NULL COMMENT '红包发送者名称',
  `re_openid` varchar(32) DEFAULT NULL COMMENT '接受红包的openid',
  `total_num` int(11) DEFAULT '1',
  `wishing` varchar(128) DEFAULT NULL COMMENT '祝福语',
  `client_ip` varchar(15) DEFAULT NULL COMMENT '调用接口机器的ip',
  `act_name` varchar(32) DEFAULT NULL COMMENT '活动名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `scene_id` varchar(32) DEFAULT NULL COMMENT '场景id',
  `risk_info` varchar(128) DEFAULT NULL COMMENT '活动信息',
  `consume_mch_id` varchar(32) DEFAULT NULL COMMENT '资金授权商户号',
  `nonce_str` varchar(32) DEFAULT NULL COMMENT '随机字符串',
  `sign` varchar(32) DEFAULT NULL COMMENT '数据签名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='红包订单表';

DROP TABLE IF EXISTS `weixin_redpacket_round`;
CREATE TABLE `weixin_redpacket_round` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '1未开始2进行中3结束',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '1普通红包2随机红包',
  `amount` int(8) unsigned DEFAULT '0' COMMENT '红包金额 单位是分',
  `num` int(4) unsigned DEFAULT '1' COMMENT '红包个数大于1',
  `numperperson` tinyint(3) unsigned DEFAULT '1' COMMENT '每个用户此轮可抢的红包数量，默认为1个',
  `chance` int(4) unsigned DEFAULT '0' COMMENT '红包获得概率，单位是千分之1',
  `lefttime` int(11) unsigned DEFAULT '30' COMMENT '活动持续时间，单位是秒',
  `minamount` int(8) unsigned DEFAULT '0' COMMENT '随机红包最小金额大于100，单位是分',
  `maxamount` int(8) unsigned DEFAULT '0' COMMENT '随机红包的最大金额',
  `started_at` int(11) DEFAULT NULL COMMENT '轮次开始时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='红包轮次配置';

DROP TABLE IF EXISTS `weixin_redpacket_users`;
CREATE TABLE `weixin_redpacket_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL COMMENT '用户id',
  `roundid` int(11) DEFAULT NULL COMMENT '轮次id',
  `amount` int(11) DEFAULT NULL COMMENT '红包金额，单位是分',
  `created_at` int(11) DEFAULT NULL COMMENT '红包领取时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '发放完成时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '1表示未发2表示发放中3已发4发放失败',
  `orderno` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='红包雨中奖用户数据';

DROP TABLE IF EXISTS  `weixin_redpacket_deeja_orders`;
CREATE TABLE `weixin_redpacket_deeja_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(32) DEFAULT NULL,
  `openid` varchar(28) DEFAULT NULL,
  `money` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1未完成2支付成功3失败',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `weixin_sessions`;
CREATE TABLE `weixin_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '0',
  `user_agent` varchar(200) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `weixin_shake_themes`;
CREATE TABLE `weixin_shake_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `themename` varchar(32) DEFAULT NULL COMMENT '主题名称',
  `themedata` text COMMENT '主题的数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `weixin_shake_themes` VALUES (1, '默认横向汽车主题', 'a:2:{s:12:\"ishorizontal\";s:1:\"1\";s:4:\"tips\";a:8:{i:0;s:12:\"再大力！\";i:1;s:22:\"再大力,再大力！\";i:2;s:32:\"再大力,再大力,再大力！\";i:3;s:15:\"摇，大力摇\";i:4;s:24:\"快点摇啊，别停！\";i:5;s:24:\"摇啊，摇啊，摇啊\";i:6;s:45:\"小心手机，别飞出去伤到花花草草\";i:7;s:18:\"看灰机～～～\";}}');
INSERT INTO `weixin_shake_themes` VALUES (2, '默认纵向气球主题', 'a:2:{s:12:\"ishorizontal\";s:1:\"2\";s:4:\"tips\";a:8:{i:0;s:12:\"再大力！\";i:1;s:22:\"再大力,再大力！\";i:2;s:32:\"再大力,再大力,再大力！\";i:3;s:15:\"摇，大力摇\";i:4;s:24:\"快点摇啊，别停！\";i:5;s:24:\"摇啊，摇啊，摇啊\";i:6;s:45:\"小心手机，别飞出去伤到花花草草\";i:7;s:18:\"看灰机～～～\";}}');
INSERT INTO `weixin_shake_themes` VALUES (3, '横向足球主题', 'a:18:{s:12:\"ishorizontal\";i:1;s:8:\"avatar_1\";i:1;s:8:\"avatar_2\";i:2;s:8:\"avatar_3\";i:3;s:8:\"avatar_4\";i:4;s:8:\"avatar_5\";i:5;s:8:\"avatar_6\";i:6;s:8:\"avatar_7\";i:7;s:8:\"avatar_8\";i:8;s:8:\"avatar_9\";i:9;s:9:\"avatar_10\";i:10;s:9:\"startline\";i:0;s:7:\"endline\";i:0;s:8:\"trackodd\";i:0;s:9:\"trackeven\";i:0;s:2:\"bg\";i:11;s:9:\"mobileimg\";i:12;s:4:\"tips\";a:8:{i:0;s:12:\"再大力！\";i:1;s:22:\"再大力,再大力！\";i:2;s:32:\"再大力,再大力,再大力！\";i:3;s:15:\"摇，大力摇\";i:4;s:24:\"快点摇啊，别停！\";i:5;s:24:\"摇啊，摇啊，摇啊\";i:6;s:45:\"小心手机，别飞出去伤到花花草草\";i:7;s:18:\"看灰机～～～\";}}');
INSERT INTO `weixin_shake_themes` VALUES (4, '猪年主题', 'a:18:{s:8:\"avatar_1\";s:2:\"14\";s:8:\"avatar_2\";s:2:\"14\";s:8:\"avatar_3\";s:2:\"14\";s:8:\"avatar_4\";s:2:\"14\";s:8:\"avatar_5\";s:2:\"14\";s:8:\"avatar_6\";s:2:\"14\";s:8:\"avatar_7\";s:2:\"14\";s:8:\"avatar_8\";s:2:\"14\";s:8:\"avatar_9\";s:2:\"14\";s:9:\"avatar_10\";s:2:\"14\";s:9:\"startline\";i:0;s:7:\"endline\";i:0;s:8:\"trackodd\";i:0;s:9:\"trackeven\";i:0;s:2:\"bg\";i:0;s:9:\"mobileimg\";s:2:\"15\";s:12:\"ishorizontal\";s:1:\"1\";s:4:\"tips\";a:8:{i:0;s:12:\"再大力！\";i:1;s:22:\"再大力,再大力！\";i:2;s:32:\"再大力,再大力,再大力！\";i:3;s:15:\"摇，大力摇\";i:4;s:24:\"快点摇啊，别停！\";i:5;s:24:\"摇啊，摇啊，摇啊\";i:6;s:45:\"小心手机，别飞出去伤到花花草草\";i:7;s:18:\"看灰机～～～\";}}');

DROP TABLE IF EXISTS `weixin_shake_config`;
CREATE TABLE `weixin_shake_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `duration` int(11) NOT NULL DEFAULT '1' COMMENT '持续条件（次/秒）',
  `durationtype` tinyint(1) DEFAULT '1' COMMENT '1表示按时间，2表示按次数',
  `toprank` int(5) DEFAULT '3' COMMENT '前几名获奖',
  `winningagain` tinyint(1) DEFAULT '1' COMMENT '1表示不能重复2表示可以重复获奖，默认是1',
  `status` tinyint(1) DEFAULT '1' COMMENT '1表示未开始，2进行中，3表示结束',
  `maxplayers` int(11) unsigned DEFAULT '200' COMMENT '最大参与人数，默认200',
  `showstyle` tinyint(1) DEFAULT '1' COMMENT '1昵称2姓名3手机号',
  `currentshow` tinyint(1) DEFAULT '1' COMMENT '1不是当前活动2当前活动',
  `themeid` int(11) DEFAULT NULL COMMENT '主题id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='摇一摇游戏配置表';

INSERT INTO `weixin_shake_config` VALUES (1, 100, 2, 3, 1, 1, 200, 1, 2, 1);
INSERT INTO `weixin_shake_config` VALUES (2, 100, 2, 3, 1, 1, 200, 1, 1, 2);
INSERT INTO `weixin_shake_config` VALUES (3, 100, 2, 3, 1, 1, 200, 1, 1, 3);
INSERT INTO `weixin_shake_config` VALUES (4, 100, 2, 3, 1, 1, 200, 1, 1, 1);
INSERT INTO `weixin_shake_config` VALUES (5, 100, 2, 3, 1, 1, 200, 1, 1, 2);
INSERT INTO `weixin_shake_config` VALUES (6, 100, 2, 3, 1, 1, 200, 1, 1, 3);
INSERT INTO `weixin_shake_config` VALUES (7, 100, 2, 3, 1, 1, 200, 1, 1, 1);
INSERT INTO `weixin_shake_config` VALUES (8, 100, 2, 3, 1, 1, 200, 1, 1, 2);
INSERT INTO `weixin_shake_config` VALUES (9, 100, 2, 3, 1, 1, 200, 1, 1, 3);
INSERT INTO `weixin_shake_config` VALUES (10, 100, 2, 3, 1, 1, 200, 1, 1, 1);


DROP TABLE IF EXISTS `weixin_shake_record`;
CREATE TABLE `weixin_shake_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `point` int(11) DEFAULT NULL COMMENT '数量',
  `userid` int(11) DEFAULT NULL COMMENT '用户id',
  `configid` int(11) DEFAULT NULL COMMENT '配置id',
  `iswinner` tinyint(1) DEFAULT NULL COMMENT '1不是2是中奖用户',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid_configid_index` (`userid`,`configid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='摇一摇游戏记录';

DROP TABLE IF EXISTS `weixin_system_config`;
CREATE TABLE `weixin_system_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `configkey` varchar(255) DEFAULT NULL COMMENT '配置名称',
  `configvalue` varchar(255) DEFAULT NULL COMMENT '配置值',
  `configname` varchar(255) DEFAULT NULL COMMENT '配置中文名称',
  `configcomment` text COMMENT '配置备注说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置表';

INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (1, 'SAVEFILEMODE', 'file', '文件保存模式', 'file表示文件保存，aliyunoss表示阿里云oss保存图片');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (2, 'mobileqiandaobg', '0', '手机端签到页面背景', '手机签到页面的背景图，默认0是现在的星空背景');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (3, 'wallnameshowstyle', '1', '上墙消息显示', '1昵称2姓名3手机号');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (4, 'signnameshowstyle', '1', '签到显示', '1昵称2姓名3手机号');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (5, 'danmushowstyle', '1', '弹幕显示', '1昵称2姓名3手机号');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (6, 'ddpshowstyle', '1', '对对碰显示', '1昵称2姓名3手机号');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (7, 'qiandaoshenhe', '1', '签到审核', '1表示不需要审核2需要审核');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (8, 'qiandaosignname', '1', '签到填写姓名', '1必须2不需要');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (9, 'qiandaophone', '1', '签到填写手机号', '1必须2不需要');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (10, 'menucolor', '#dbb902', '菜单颜色', '16进制颜色代码');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (11, 'showcountsign', '2', '显示签到人数', '1表示不显示签到2显示签到人数3前是通过审核的人数');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (12, 'qrcodepos', '', '二维码位置', '二维码坐标和大小信息');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (13, 'mobilemenufontcolor', '#ffffff', '签到菜单文字颜色', '颜色值,16进制值');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (14,'deeja_appid','','迪加AppId','迪加Appid');
INSERT INTO `weixin_system_config`(`id`, `configkey`, `configvalue`, `configname`, `configcomment`) VALUES (15,'deeja_appsecret','','迪加AppSecret','迪加Appsecret');

DROP TABLE IF EXISTS `weixin_threedimensional`;
CREATE TABLE `weixin_threedimensional` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `avatarnum` tinyint(3) unsigned DEFAULT '0',
  `datastr` text,
  `avatarsize` tinyint(3) DEFAULT NULL,
  `avatargap` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='3d签到动画设置';
INSERT INTO `weixin_threedimensional` VALUES (1,30,'#earth|你好2019|#torus',7,15);

DROP TABLE IF EXISTS `weixin_wall`;
CREATE TABLE `weixin_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  `nickname` text,
  `avatar` text,
  `ret` tinyint(1) DEFAULT NULL COMMENT '0待审核1审核通过2审核不通过',
  `fromtype` varchar(255) DEFAULT NULL,
  `image` int(11) DEFAULT NULL COMMENT '图片路径id',
  `datetime` int(10) DEFAULT NULL,
  `openid` varchar(32) DEFAULT NULL COMMENT '发送人的openid',
  `shenhetime` int(11) DEFAULT '0' COMMENT '按照审核的时间顺序来',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `weixin_wall_config`;
CREATE TABLE `weixin_wall_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `success` text NOT NULL COMMENT '消息发送成功但是没有审核时的提醒信息，自由手动审核才用这句',
  `shenghe` int(11) NOT NULL COMMENT '0自动审核1手动审核',
  `cjreplay` tinyint(4) NOT NULL DEFAULT '0' COMMENT '中奖是否需要回复',
  `timeinterval` int(3) NOT NULL DEFAULT '0' COMMENT '观众发送消息的频率，单位秒',
  `shakeopen` tinyint(4) NOT NULL DEFAULT '1' COMMENT '摇一摇开关',
  `voteopen` tinyint(4) NOT NULL DEFAULT '1' COMMENT '投票开关1打开2关闭',
  `votetitle` text NOT NULL COMMENT '投票标题',
  `votefresht` tinyint(4) NOT NULL COMMENT '投票结果刷新时间',
  `circulation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否循环播放1循环0不循环',
  `refreshtime` tinyint(2) NOT NULL DEFAULT '0' COMMENT '前台刷新时间，单位秒',
  `voteshowway` tinyint(1) DEFAULT '1' COMMENT '投票结果显示方式',
  `votecannum` varchar(255) DEFAULT '1' COMMENT '每个人可以投几票',
  `black_word` text COMMENT '屏蔽关键字',
  `screenpaw` varchar(255) NOT NULL COMMENT '开场密码',
  `rentweixin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不借用其他微信号获取用户信息1借用其他微信服务号获取用户信息',
  `copyright` varchar(32) DEFAULT NULL COMMENT '版权',
  `copyrightlink` varchar(500) DEFAULT NULL COMMENT '版权连接',
  `msg_showstyle` tinyint(1) DEFAULT '0' COMMENT '消息显示方式 0滚动1反转',
  `msg_historynum` int(3) DEFAULT '30' COMMENT '循环播放时，循环显示的历史消息数量',
  `msg_showbig` tinyint(1) DEFAULT '0' COMMENT '图片消息是否放大显示0关闭1开启',
  `msg_showbigtime` tinyint(3) DEFAULT '5' COMMENT '开启显示放大图片消息时，显示放大图片的时间，单位是秒',
  `verifycode` varchar(255) DEFAULT NULL COMMENT '活动签到连接校验码',
  `maxplayers` int(11) unsigned DEFAULT '0' COMMENT '0表示不限，大于0表示限制n人数',
  `msg_color` varchar(7) DEFAULT '#4B9E09' COMMENT '16进制颜色值',
  `nickname_color` varchar(7) DEFAULT '#4B9E09' COMMENT '昵称颜色',
  `qrcodetoptext` varchar(255) DEFAULT '扫描下面的二维码参与签到' COMMENT '大二维码顶部文字',
  `msg_num` tinyint(1) DEFAULT '3' COMMENT '微信上墙消息数量 3，4，5，6可选',
  `isclosed`  tinyint(1) DEFAULT '1' COMMENT '1开启2关闭',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
		
INSERT INTO `weixin_wall_config`(`id`, `success`,  `shenghe`, `cjreplay`, `timeinterval`, `shakeopen`, `voteopen`, `votetitle`, `votefresht`, `circulation`, `refreshtime`, `voteshowway`, `votecannum`, `black_word`, `screenpaw`, `rentweixin`,  `copyright`, `copyrightlink`, `msg_showstyle`, `msg_historynum`, `msg_showbig`, `msg_showbigtime`, `verifycode`, `maxplayers`, `msg_color`, `nickname_color`, `qrcodetoptext`, `msg_num`) VALUES (1, '你已经成功发送，等待审核通过即可上墙了',  0, 0, 3, 1, 1, '你最喜欢微信墙的哪个功能？', 3, 1, 3, 1, '1', '操,sb,傻逼,艹,日你妈,干你妹,老子,bitch,婊子', 'admin',2,   '迪加网络科技', 'http://www.deeja.top/',  0, 30, 1, 5, '', 0, '#4B9E09', '#4B9E09', '扫描下面的二维码参与签到', 3);

DROP TABLE IF EXISTS `weixin_weixin_config`;
CREATE TABLE `weixin_weixin_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) NOT NULL COMMENT '微信名称',
  `erweima` int(11) NOT NULL DEFAULT '0' COMMENT '二维码id',
  `appid` varchar(64) DEFAULT NULL COMMENT '微信appid',
  `appsecret` varchar(128) DEFAULT NULL COMMENT '微信appsecret',
  `mch_id` varchar(255) DEFAULT NULL,
  `mchsecret` varchar(255) DEFAULT NULL,
  `apiclient_cert` text,
  `apiclient_key` text,
  `rootca` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `weixin_weixin_config` VALUES (1,'微信',0,'','',NULL,NULL,NULL,NULL,NULL);

DROP TABLE IF EXISTS `weixin_xiangce`;
CREATE TABLE `weixin_xiangce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagepath` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='相册';

DROP TABLE IF EXISTS `weixin_xingyunhaoma`;
CREATE TABLE `weixin_xingyunhaoma` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `lucknum` int(11) DEFAULT NULL COMMENT '幸运号码',
  `designated` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1表示普通2表示必中3标识不会中',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `ordernum` int(11) NOT NULL COMMENT '第几个抽执行，如果是必中，那就是第几个会出现这个数字，如果是不会中，那就是第几个数字不会出现这个数字',
  `status` tinyint(1) DEFAULT NULL COMMENT '1未中2已中',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='幸运号码记录表';

DROP TABLE IF EXISTS `weixin_xingyunhaoma_config`;
CREATE TABLE `weixin_xingyunhaoma_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `minnum` int(11) NOT NULL DEFAULT '1' COMMENT '幸运号码最小值',
  `maxnum` int(11) NOT NULL DEFAULT '2000' COMMENT '幸运号码最大值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='幸运号码配置表';
INSERT INTO `weixin_xingyunhaoma_config` VALUES (1, 1, 1000);



DROP TABLE IF EXISTS `weixin_xingyunshoujihao`;
CREATE TABLE `weixin_xingyunshoujihao`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `openid` varbinary(255) DEFAULT NULL COMMENT 'openid',
  `designated` tinyint(1) DEFAULT NULL COMMENT '1表示普通2表示必中3标识不会中',
  `ordernum` int(11) DEFAULT NULL COMMENT '第几个抽执行，如果是必中，那就是第几个会出现这个数字，如果是不会中，那就是第几个数字不会出现这个数字',
  `status` tinyint(1) DEFAULT NULL COMMENT '1未中2已中',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '幸运手机号数据及内定信息表';

DROP TABLE IF EXISTS `weixin_menu`;
CREATE TABLE `weixin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `icon` int(11) DEFAULT NULL COMMENT '按钮图标',
  `link` text COMMENT '链接',
  `ordernum` int(11) DEFAULT NULL COMMENT '排序',
  `type` tinyint(1) DEFAULT '1' COMMENT '1手机端2pc端',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `weixin_music`;
CREATE TABLE `weixin_music` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bgmusic` int(11) DEFAULT NULL COMMENT '背景音乐id',
  `bgmusicstatus` tinyint(1) DEFAULT NULL COMMENT '1开2关',
  `name` varchar(32) DEFAULT NULL COMMENT '名称',
  `plugname` varchar(32) DEFAULT NULL COMMENT '关联的组件名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (1, NULL, 2, '签到墙背景乐', 'qdq');
INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (2, NULL, 2, '对对碰背景乐', 'ddp');
INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (3, NULL, 2, '投票背景乐', 'vote');
INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (4, NULL, 2, '幸运手机号背景乐', 'xysjh');
INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (5, NULL, 2, '幸运号码背景乐', 'xyh');
INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (6, NULL, 2, '3D签到背景乐', 'threedimensionalsign');
INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (7, NULL, 2, '微信上墙背景乐', 'wall');
INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (8, NULL, 2, '相册背景乐', 'xiangce');
INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (9, NULL, 2, '红包雨背景乐', 'redpacket');
INSERT INTO `weixin_music`(`id`, `bgmusic`, `bgmusicstatus`, `name`, `plugname`) VALUES (10, NULL, 2, '摇大奖树背景乐', 'ydj');

DROP TABLE IF EXISTS `weixin_vote_config`;
CREATE TABLE `weixin_vote_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `votetitle` varchar(255) DEFAULT NULL COMMENT '投票主题',
  `status` tinyint(1) DEFAULT NULL COMMENT '1开始2结束',
  `created_at` int(11) DEFAULT NULL COMMENT '添加时间',
  `currentshow` tinyint(1) DEFAULT NULL COMMENT '1表示当前大屏幕显示的，2表示不是当前显示的',
  `showtype` int(1) DEFAULT NULL COMMENT '1横向2纵向3图片形式',
  `votenum` int(5) DEFAULT NULL COMMENT '可选几项（多选，默认1是单选）',
  `editable` tinyint(1) DEFAULT NULL COMMENT '1投完之后无法更改，2表示可以修改',
  `refreshtime` tinyint(2) unsigned DEFAULT NULL COMMENT '单位是秒，默认是3秒',
  `votemode` tinyint(1) DEFAULT '1' COMMENT '1表示最大投票数，2表示固定投票数，3表示最少投票数',
  `unit` varchar(255) DEFAULT NULL COMMENT '单位',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `weixin_vote_config`(`id`, `votetitle`, `status`, `created_at`, `currentshow`, `showtype`, `votenum`, `editable`, `refreshtime`,`votemode`,`unit`) VALUES (1, '您最喜欢的节目', 1, 1520227302, 1, 2, 1, 1, 3,1,'票');

DROP TABLE IF EXISTS `weixin_vote_items`;
CREATE TABLE `weixin_vote_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voteconfigid` int(11) DEFAULT NULL COMMENT '投票主题id',
  `voteitem` varchar(255) DEFAULT NULL COMMENT '投票项名称',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `imageid` int(11) DEFAULT NULL COMMENT '如果有图片那么图片id',
  `votecount` int(11) DEFAULT '0' COMMENT '获得票数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (1, 1, '签到墙', 1526136006, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (2, 1, '微信上墙', 1526136018, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (3, 1, '摇一摇', 1526136024, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (4, 1, '数钱游戏', 1526136030, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (5, 1, '投票', 1526136040, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (6, 1, '抽奖', 1526136054, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (7, 1, '3D抽奖', 1526136077, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (8, 1, '抽奖箱', 1526136082, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (9, 1, '砸金蛋', 1526136087, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (10, 1, '幸运号码', 1526136095, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (11, 1, '幸运手机号', 1526136101, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (12, 1, '相册', 1526136110, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (13, 1, '开幕墙', 1526136114, NULL, 0);
INSERT INTO `weixin_vote_items`(`id`, `voteconfigid`, `voteitem`, `created_at`, `imageid`, `votecount`) VALUES (14, 1, '闭幕墙', 1526136121, NULL, 0);

DROP TABLE IF EXISTS `weixin_vote_record`;
CREATE TABLE `weixin_vote_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voteconfigid` int(11) DEFAULT NULL COMMENT '投票主题id',
  `openid` varchar(32) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `voteitemid` int(11) DEFAULT NULL COMMENT '投票项id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `weixin_shuqian_config`;
CREATE TABLE `weixin_shuqian_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `duration` int(4) DEFAULT '30' COMMENT '游戏持续时间',
  `toprank` int(5) DEFAULT '3' COMMENT '前几名获奖',
  `winningagain` tinyint(1) DEFAULT '1' COMMENT '1表示不能重复2表示可以重复获奖，默认是1',
  `status` tinyint(1) DEFAULT '1' COMMENT '1表示未开始，2进行中，3表示结束',
  `maxplayers` int(11) unsigned DEFAULT '200' COMMENT '最大参与人数，默认200',
  `showstyle` tinyint(1) DEFAULT '1' COMMENT '1昵称2姓名3手机号',
  `currentshow` tinyint(1) DEFAULT '1' COMMENT '1不是当前活动2当前活动',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (1, 30, 3, 1, 1, 200, 1, 1);
INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (2, 30, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (3, 30, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (4, 30, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (5, 30, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (6, 30, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (7, 30, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (8, 30, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (9, 30, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_shuqian_config`(`id`, `duration`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (10, 30, 3, 1, 1, 200, 1, 2);

DROP TABLE IF EXISTS `weixin_shuqian_record`;
CREATE TABLE `weixin_shuqian_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `point` int(11) DEFAULT NULL COMMENT '数量',
  `openid` varchar(255) DEFAULT NULL COMMENT 'openid',
  `configid` int(11) DEFAULT NULL COMMENT '配置id',
  `iswinner` tinyint(1) DEFAULT NULL COMMENT '1不是2是中奖用户',
  PRIMARY KEY (`id`),
  KEY `openid_configid_idx` (`openid`,`configid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `weixin_pashu_config`;
CREATE TABLE `weixin_pashu_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `times` int(11) NOT NULL DEFAULT '0',
  `toprank` int(5) DEFAULT '3' COMMENT '前几名获奖',
  `winningagain` tinyint(1) DEFAULT '1' COMMENT '1表示不能重复2表示可以重复获奖，默认是1',
  `status` tinyint(1) DEFAULT '1' COMMENT '1表示未开始，2进行中，3表示结束',
  `maxplayers` int(11) unsigned DEFAULT '200' COMMENT '最大参与人数，默认200',
  `showstyle` tinyint(1) DEFAULT '1' COMMENT '1昵称2姓名3手机号',
  `currentshow` tinyint(1) DEFAULT '1' COMMENT '1不是当前活动2当前活动',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='猴子爬树配置表';

INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (1, 100, 3, 1, 1, 200, 1, 1);
INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (2, 100, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (3, 100, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (4, 100, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (5, 100, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (6, 100, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (7, 100, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (8, 100, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (9, 100, 3, 1, 1, 200, 1, 2);
INSERT INTO `weixin_pashu_config`(`id`, `times`, `toprank`, `winningagain`, `status`, `maxplayers`, `showstyle`, `currentshow`) VALUES (10, 100, 3, 1, 1, 200, 1, 2);


DROP TABLE IF EXISTS `weixin_pashu_record`;
CREATE TABLE `weixin_pashu_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `point` int(11) DEFAULT NULL COMMENT '数量',
  `openid` varchar(255) DEFAULT NULL COMMENT 'openid',
  `configid` int(11) DEFAULT NULL COMMENT '配置id',
  `iswinner` tinyint(1) DEFAULT NULL COMMENT '1不是2是中奖用户',
  PRIMARY KEY (`id`),
  KEY `openid_configid_idx` (`openid`,`configid`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='猴子爬树游戏记录';

DROP TABLE IF EXISTS `weixin_prizes`;
CREATE TABLE `weixin_prizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `prizename` varchar(255) DEFAULT NULL COMMENT '奖品名称',
  `type` tinyint(1) DEFAULT NULL COMMENT '奖品类型1普通奖品2微信卡券3微信红包4微信零钱5虚拟卡密',
  `num` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '奖品数量',
  `freezenum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '冻结的数量',
  `leftnum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '剩余数量（不包含冻结的数量）',
  `prizedata` text COMMENT '序列化的奖品数据',
  `plugname` varchar(64) DEFAULT NULL COMMENT '组件名称',
  `activityid` int(11) unsigned DEFAULT '0' COMMENT '活动编号，没有就是0，有就是id',
  `isdel` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1表示正常2表示已删除',
  `rate` int(11) unsigned NOT NULL DEFAULT '1000000' COMMENT '中奖概率，百万分之一',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='奖品表';

INSERT INTO `weixin_prizes` VALUES ('1', '奖品1', '1', '10', '0', '10', 'a:1:{s:7:\"imageid\";i:0;}', 'ydj', '1', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('2', '奖品2', '1', '10', '0', '10', 'a:1:{s:7:\"imageid\";i:0;}', 'ydj', '1', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('3', '奖品3', '1', '10', '0', '10', 'a:1:{s:7:\"imageid\";i:0;}', 'ydj', '1', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('4', '奖品1', '1', '10', '0', '10', 'a:1:{s:7:\"imageid\";i:0;}', 'ydj', '2', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('5', '奖品2', '1', '10', '0', '10', 'a:1:{s:7:\"imageid\";i:0;}', 'ydj', '2', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('6', '奖品3', '1', '10', '0', '10', 'a:1:{s:7:\"imageid\";i:0;}', 'ydj', '2', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('7', '奖品1', '1', '10', '0', '10', 'a:1:{s:7:\"imageid\";i:0;}', 'ydj', '3', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('8', '奖品2', '1', '10', '0', '10', 'a:1:{s:7:\"imageid\";i:0;}', 'ydj', '3', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('9', '奖品3', '1', '10', '0', '10', 'a:1:{s:7:\"imageid\";i:0;}', 'ydj', '3', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('10', '奖品1', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'importlottery', '1', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('11', '奖品2', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'importlottery', '1', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('12', '奖品3', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'importlottery', '1', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('13', '一等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '1', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('14', '二等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '1', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('15', '三等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '1', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('16', '一等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '2', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('17', '二等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '2', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('18', '三等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '2', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('19', '一等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '3', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('20', '二等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '3', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('21', '三等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '3', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('22', '一等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '4', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('23', '二等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '4', '1',1000000);
INSERT INTO `weixin_prizes` VALUES ('24', '三等奖', '1', '100', '0', '100', 'a:1:{s:7:\"imageid\";i:0;}', 'lottery', '4', '1',1000000);

DROP TABLE IF EXISTS `weixin_prize_redpackets`;
CREATE TABLE `weixin_prize_redpackets` (
  `orderno` varchar(32) NOT NULL,
  `money` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1开始发2发放完成3失败4等待中',
  `userprizeid` int(11) NOT NULL,
  `openid` varchar(32) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`orderno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `weixin_user_prize`;
CREATE TABLE `weixin_user_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prizeid` int(11) DEFAULT NULL COMMENT '奖品id',
  `plugname` varchar(64) DEFAULT NULL COMMENT '组件名称',
  `activityid` int(11) DEFAULT '0' COMMENT '活动轮次id，类似摇一摇的有这个编号，抽奖没有，没有就是0',
  `userid` int(11) DEFAULT NULL COMMENT '用户id',
  `designated` tinyint(1) DEFAULT NULL COMMENT '内定状态1是普通状态2表示内定3表示不会中这个奖',
  `status` tinyint(1) DEFAULT NULL COMMENT '中奖状态1表示未中2表示中奖3表示已发奖4取消',
  `verifycode` varchar(64) DEFAULT NULL COMMENT '兑换码',
  `created_at` int(11) DEFAULT NULL COMMENT '记录创建时间',
  `wintime` int(11) DEFAULT NULL COMMENT '得奖时间',
  `awardtime` int(11) DEFAULT NULL COMMENT '发奖时间',
  `isdel` tinyint(1) DEFAULT NULL COMMENT '1正常2删除',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT  COMMENT='获奖名单';

DROP TABLE IF EXISTS `weixin_ydj_themes`;
CREATE TABLE `weixin_ydj_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `themename` varchar(32) DEFAULT NULL COMMENT '主题名称',
  `themedata` text COMMENT '主题的数据',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='摇大奖游戏配置表';

INSERT INTO `weixin_ydj_themes` VALUES ('1', '默认主题', '');

DROP TABLE IF EXISTS `weixin_ydj_config`;
CREATE TABLE `weixin_ydj_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `duration` int(11) NOT NULL DEFAULT '60' COMMENT '持续时间（秒）',
  `winningagain` int(1) DEFAULT '1' COMMENT '0表示不限次数 大于0的数字表示可以获得奖品的次数',
  `joinagain` tinyint(1) DEFAULT NULL COMMENT '1表示不可以重复参加2表示可以（之前参加过活动并得奖的用户是否能重复参与活动）',
  `status` tinyint(1) DEFAULT '1' COMMENT '1表示未开始，2进行中，3表示结束',
  `showstyle` tinyint(1) DEFAULT '1' COMMENT '1昵称2姓名3手机号',
  `themeid` int(11) DEFAULT NULL COMMENT '主题id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='摇大奖游戏配置表';

INSERT INTO `weixin_ydj_config` VALUES ('1', '30', '1', '2', '1', '1', '1');
INSERT INTO `weixin_ydj_config` VALUES ('2', '30', '1', '2', '1', '1', '1');
INSERT INTO `weixin_ydj_config` VALUES ('3', '30', '1', '2', '1', '1', '1');

DROP TABLE IF EXISTS `weixin_ydj_record`;
CREATE TABLE `weixin_ydj_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `point` int(11) DEFAULT NULL COMMENT '数量',
  `userid` int(11) DEFAULT NULL COMMENT '用户id',
  `configid` int(11) DEFAULT NULL COMMENT '配置id',
  `iswinner` tinyint(1) DEFAULT NULL COMMENT '1不是2是中奖用户',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='摇大奖游戏记录';

DROP VIEW IF EXISTS `weixin_view_importlottery`;
CREATE VIEW `weixin_view_importlottery` as select `weixin_user_prize`.`id` AS `id`,`weixin_importlottery`.`id` AS `dataid`,`weixin_user_prize`.`activityid` AS `activityid`,`weixin_importlottery`.`datarow` AS `datarow`,`weixin_importlottery`.`imgid` AS `imgid`,`weixin_user_prize`.`designated` AS `designated`,`weixin_user_prize`.`prizeid` AS `prizeid`,`weixin_user_prize`.`status` AS `status`,`weixin_user_prize`.`verifycode` AS `verifycode`,`weixin_user_prize`.`wintime` AS `wintime`,`weixin_user_prize`.`isdel` AS `isdel`,`weixin_user_prize`.`awardtime` AS `awardtime` from(`weixin_importlottery` left join `weixin_user_prize` on((`weixin_importlottery`.`id`= `weixin_user_prize`.`userid`)))where(`weixin_user_prize`.`plugname`= 'importlottery');

DROP VIEW IF EXISTS `weixin_view_lottery`;
CREATE VIEW `weixin_view_lottery` AS select `weixin_user_prize`.`id` AS `id`,`weixin_flag`.`id` AS `userid`,`weixin_flag`.`nickname` AS `nickname`,`weixin_flag`.`avatar` AS `avatar`,`weixin_flag`.`signname` AS `signname`,`weixin_flag`.`phone` AS `phone`,`weixin_user_prize`.`activityid` AS `activityid`,`weixin_user_prize`.`prizeid` AS `prizeid`,`weixin_user_prize`.`designated` AS `designated`,`weixin_user_prize`.`status` AS `status`,`weixin_user_prize`.`isdel` AS `isdel` from (`weixin_flag` left join `weixin_user_prize` on((`weixin_flag`.`id` = `weixin_user_prize`.`userid`))) where (`weixin_user_prize`.`plugname` = 'lottery') order by `weixin_flag`.`id` ;