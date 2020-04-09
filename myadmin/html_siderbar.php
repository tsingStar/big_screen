<?php

?>
<div id="sidebar" class="sidebar responsive ace-save-state">
<script type="text/javascript">
{literal}
    try{ace.settings.loadState('sidebar')}catch(e){}
{/literal}
</script>
<div class="sidebar-shortcuts" id="sidebar-shortcuts">
    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
        <button class="btn btn-success" onclick="window.location.href='wallmessage.php'">
            <i class="ace-icon fa fa-comments"></i>
        </button>

        <button class="btn btn-info" onclick="window.location.href='wallnotice.php'">
            <i class="ace-icon fa fa-pencil"></i>
        </button>

        <!-- #section:basics/sidebar.layout.shortcuts -->
        <button class="btn btn-warning" onclick="window.location.href='qiandao.php'">
            <i class="ace-icon fa fa-users" ></i>
        </button>

        <button class="btn btn-danger" onclick="window.location.href='intergrate.php'">
            <i class="ace-icon fa fa-cogs"></i>
        </button>

        <!-- /section:basics/sidebar.layout.shortcuts -->
    </div>

    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
        <span class="btn btn-success"></span>

        <span class="btn btn-info"></span>

        <span class="btn btn-warning"></span>

        <span class="btn btn-danger"></span>
    </div>
</div><!-- /.sidebar-shortcuts -->

<ul class="nav nav-list">
    <li class="">
        <a href="index.php">
            <i class="menu-icon fa fa-tachometer"></i>
            <span class="menu-text"> 首页 </span>
        </a>
        <b class="arrow"></b>
    </li>
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-users"></i>
            <span class="menu-text">签到设置</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <li class="">
                <a href="qiandaosettings.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    签到设置
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="qiandao.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    签到列表
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="mobileqiandao.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    手机签到
                </a>
                <b class="arrow"></b>
            </li>
            
        </ul>
    </li>
    <li class="">
        <a href="threedimensionalsign.php">
            <i class="menu-icon fa  fa-desktop"></i>
            <span class="menu-text">3D签到设置</span>
        </a>
        <b class="arrow"></b>
    </li>
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-desktop"></i>
            <span class="menu-text">
                上墙消息管理
            </span>

            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            

            <li class="">
                <a href="wallsettings.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    上墙设置
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="danmusettings.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    弹幕设置
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="wallmessage.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    消息列表
                </a>

                <b class="arrow"></b>
            </li>

            <li class="">
                <a href="wallnotice.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    发布公告
                </a>

                <b class="arrow"></b>
            </li>
        </ul>
    </li>

    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-trophy"></i>
            <span class="menu-text">摇一摇管理</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <li class="">
                <a href="shakeconfig.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    摇一摇设置
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="shakethemes.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    摇一摇模板
                </a>
                <b class="arrow"></b>
            </li>
        </ul>
    </li>
    <li class="">
        <a href="shuqianconfig.php">
            <i class="menu-icon fa  fa-trophy"></i>
            <span class="menu-text">数钱游戏</span>
        </a>
        <b class="arrow"></b>
    </li>
    <li class="">
        <a href="pashuconfig.php">
            <i class="menu-icon fa  fa-trophy"></i>
            <span class="menu-text">猴子爬树</span>
        </a>
        <b class="arrow"></b>
    </li>
    <li class="">
        <a href="voteconfig.php">
            <i class="menu-icon fa  fa-signal"></i>
            <span class="menu-text">投票管理</span>
        </a>
        <b class="arrow"></b>
    </li>
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-gift"></i>
            <span class="menu-text">抽奖管理</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <li class="">
                <a href="lotterysettings.php?plug=cj">
                    <i class="menu-icon fa fa-caret-right"></i>
                    抽奖设置
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="zjlist.php?plug=cj">
                    <i class="menu-icon fa fa-caret-right"></i>
                    中奖列表
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="neiding.php?plug=cj">
                    <i class="menu-icon fa fa-caret-right"></i>
                    内定列表
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="awardlist.php?plug=cj">
                    <i class="menu-icon fa fa-caret-right"></i>
                    奖品设置
                </a>

                <b class="arrow"></b>
            </li>
        </ul>
    </li>
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-gift"></i>
            <span class="menu-text">3D抽奖设置</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <li class="">
                <a href="lotterysettings.php?plug=threedimensionallottery">
                    <i class="menu-icon fa fa-caret-right"></i>
                    3D抽奖设置
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="zjlist.php?plug=threedimensionallottery">
                    <i class="menu-icon fa fa-caret-right"></i>
                    中奖列表
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="neiding.php?plug=threedimensionallottery">
                    <i class="menu-icon fa fa-caret-right"></i>
                    内定列表
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="awardlist.php?plug=threedimensionallottery">
                    <i class="menu-icon fa fa-caret-right"></i>
                    奖品设置
                </a>

                <b class="arrow"></b>
            </li>
        </ul>
    </li>
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-gift"></i>
            <span class="menu-text">砸金蛋管理</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <li class="">
                <a href="lotterysettings.php?plug=zjd">
                    <i class="menu-icon fa fa-caret-right"></i>
                    抽奖设置
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="zjlist.php?plug=zjd">
                    <i class="menu-icon fa fa-caret-right"></i>
                    中奖列表
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="neiding.php?plug=zjd">
                    <i class="menu-icon fa fa-caret-right"></i>
                    内定列表
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="awardlist.php?plug=zjd">
                    <i class="menu-icon fa fa-caret-right"></i>
                    奖品设置
                </a>
                <b class="arrow"></b>
            </li>
        </ul>
    </li>
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-gift"></i>
            <span class="menu-text">抽奖箱管理</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <li class="">
                <a href="lotterysettings.php?plug=cjx">
                    <i class="menu-icon fa fa-caret-right"></i>
                    抽奖设置
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="zjlist.php?plug=cjx">
                    <i class="menu-icon fa fa-caret-right"></i>
                    中奖列表
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="neiding.php?plug=cjx">
                    <i class="menu-icon fa fa-caret-right"></i>
                    内定列表
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="awardlist.php?plug=cjx">
                    <i class="menu-icon fa fa-caret-right"></i>
                    奖品设置
                </a>

                <b class="arrow"></b>
            </li>
        </ul>
    </li>
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-gift"></i>
            <span class="menu-text">幸运手机号</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            
            <li class="">
                <a href="xingyunshoujihao.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    中奖记录
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="xingyunshoujihaodesignatedlist.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    内定记录
                </a>

                <b class="arrow"></b>
            </li>
        </ul>
    </li>
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-gift"></i>
            <span class="menu-text">幸运号码</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <li class="">
                <a href="xingyunhaomaconfig.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    幸运号码设置
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="xingyunhaoma.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    中奖记录
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="xingyunhaomadesignated.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    内定记录
                </a>

                <b class="arrow"></b>
            </li>
        </ul>
    </li>
    <li class="">
        <a href="xiangce.php">
            <i class="menu-icon fa  fa-picture-o"></i>
            <span class="menu-text">相册管理</span>
        </a>
        <b class="arrow"></b>
    </li>


    <li class="">
        <a href="kaimu.php">
            <i class="menu-icon fa  fa-desktop"></i>
            <span class="menu-text">开幕墙 </span>
        </a>
        <b class="arrow"></b>
    </li>
    <li class="">
        <a href="bimu.php">
            <i class="menu-icon fa  fa-desktop"></i>
            <span class="menu-text">闭幕墙 </span>
        </a>
        <b class="arrow"></b>
    </li>
    {if $wall_config['rentweixin']==1}
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-gift"></i>
            <span class="menu-text">红包管理</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <li class="">
                <a href="redpacketconfig.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    红包基本设置
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="redpacket.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    红包设置列表
                </a>

                <b class="arrow"></b>
            </li>
        </ul>
    </li>
    {/if}
    <li class="">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-gear"></i>
            <span class="menu-text">系统设置</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">
            <!-- <li class="">
                <a href="intergrate.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    公众号对接设置
                </a>

                <b class="arrow"></b>
            </li> -->
            <li class="">
                <a href="functionswitch.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    功能开关
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="systemsettings.php">
                    <i class="menu-icon fa fa-caret-right"></i>
                    系统设置
                </a>
                <b class="arrow"></b>
            </li>
            
        </ul>
    </li>
    <li class="">
        <a href="music.php">
            <i class="menu-icon fa fa-music"></i>
            <span class="menu-text"> 配乐 </span>
        </a>
        <b class="arrow"></b>
    </li>
    <li class="">
        <a href="background.php">
            <i class="menu-icon fa fa-image"></i>
            <span class="menu-text">背景图</span>
        </a>
        <b class="arrow"></b>
    </li>
</ul><!-- /.nav-list -->
</div>