requirejs.config({baseUrl:".",paths:{jquery:"/assets/js/jquery-3.3.1.min",hotkeys:"/assets/plugs/hotkeys-master/dist/hotkeys.min",bindhotkeys:"/wall/themes/meepo/assets/js/bindhotkeys"},shim:{bindhotkeys:{deps:["hotkeys"]}}}),requirejs(["jquery","bindhotkeys"],function(r){window.getdatatimer=null;var s=0,n=0,o=r(".welcome_box");function a(t){laststatus!=GAMECONFIG.status&&(laststatus=GAMECONFIG.status,function(){var t=r(window.top.document).find("#box img").attr("src");t=(t=t.replace("./","/")).trim(),o.find("img.qrimg").attr("src",t),o.slideDown()}()),radarusers=radarusers.concat(t.userlist),function(t){r(".main_right .joinNum").html(t+"人")}(t.num),function(){if(!(radarusers.length<=0)){r("#Audio_NewPlayer")[0].play();var t=radarusers.shift(),a='<img class="avatar default" src="'+t.avatar+'">';10<r(".avatar.default").length?r(".avatar.default").eq(0).animate({opacity:0},1e3,function(){r(this).remove(),r(a).appendTo(".list").animate({opacity:.7},500)}):r(".avatar.default").eq(radaruserindex).attr("src",t.avatar),radaruserindex++}}()}function i(t){if(laststatus!=GAMECONFIG.status&&(laststatus=GAMECONFIG.status,o.slideUp(),u(),p.resize()),1==GAMECONFIG.themeconfig.durationtype){s=0==s?Math.round((new Date).getTime()/1e3):s;var a=Math.round((new Date).getTime()/1e3);n=1<=n?1:(a-s)/GAMECONFIG.themeconfig.duration}else n=1;if(t)for(var i=0,e=t.length;i<e;i++)p.showprogress(i,t[i]),m=m>parseInt(t[i].point)?m:parseInt(t[i].point);1==GAMECONFIG.themeconfig.durationtype?1==n&&l():parseInt(m)>=parseInt(GAMECONFIG.themeconfig.duration)&&l()}function e(){var t=window.sessionStorage.game;"start"==t&&(window.sessionStorage.game="idle",c("start").done(function(t){i()})),"reset"==t&&(window.sessionStorage.game="idle",c("reset").done(function(t){clearTimeout(getdatatimer),getdatatimer=null,alert(t.message),0<t.code&&setTimeout(function(){window.location.reload()},3e3)})),setTimeout(e,500)}function l(){c("stop").done(function(t){t.code<0&&alert(t.message)})}function d(){t().done(function(t){if(0<t.code)return GAMECONFIG.status=t.config.status,1==t.config.status&&(a(t.users),getdatatimer=setTimeout(d,1e3)),2==t.config.status&&(getdatatimer=setTimeout(d,1e3),i(t.users)),void(3==t.config.status&&function(t){laststatus!=GAMECONFIG.status&&(laststatus=GAMECONFIG.status,g.show(t))}(t.users))}).fail(function(){alert("您的网络不稳定,点确定后,系统会重新连接,如果再次弹出这个提示,请检查网络,或者更换网络"),clearTimeout(getdatatimer),getdatatimer=setTimeout(d,1e3)})}r(document).ready(function(){GAMECONFIG?(u(),d(),e()):alert("后台没有设置游戏,请先设置一轮游戏哦")}),radarusers=[],radaruserindex=0,laststatus=0;var t=function(){return r.ajax({url:"/Modules/module.php?m=game&c=front&a=ajaxGetData",type:"get",dataType:"json"})},c=function(t){return r.ajax({url:"/Modules/module.php?m=game&c=front&a=ajaxPostData",type:"post",dataType:"json",data:{action:t}})};function u(){var t={};if(1==GAMECONFIG.status&&(t.btn_start=function(){window.sessionStorage.game="start"}),2!=GAMECONFIG.status&&3!=GAMECONFIG.status||(t.btn_reset=function(){window.sessionStorage.game="reset"}),window.top.minibar.init(t),window.sessionStorage.bgmusic=GAMECONFIG.themeconfig.bgmusic_path+"|"+GAMECONFIG.themeconfig.bgmusic_switch,1<CONFIGS.length){for(var a=[],i=["iconyyy","iconxqc","iconhzsq","iconsq","iconjzsf","iconsaipao","iconslz","iconsc","iconsm","iconst","iconqbtzj","icon61","iconqixi","iconzhongqiujie"],e=["iconyyy","默认汽车","猴子爬树","数钱游戏","金猪送福","赛跑","赛龙舟","赛车","赛马","游艇","丘比特之箭","欢乐六一","爱在七夕","浓情中秋"],s=0,n=CONFIGS.length;s<n;s++){var r=GAMECONFIG.id==CONFIGS[s].id?1:2;a[s]={name:e[CONFIGS[s].themeid],action:"/Modules/module.php?m=game&c=front&a=index&id="+CONFIGS[s].id,icon:i[CONFIGS[s].themeid],current:r}}window.top.roundbar().init(a)}}var h,m=0,p={tracklist_elm:null,track_elm:null,width:0,height:0,size:0,init:function(){null==this.tracklist_elm&&(this.tracklist_elm=r(".tracklist")),this.height=this.tracklist_elm.height()/10,this.width=this.tracklist_elm.width()-this.height,this.size=this.height>this.width?this.width:this.height},resize:function(){this.init();for(var t=this,a="",i=0;i<10;i++)a+='<div class="trackline" style="display: block; height: '+t.height+"px; line-height: "+t.height+"px; font-size: "+t.height/2+'px;">',a+='<div class="track-start" style="width: '+t.height+"px; height: "+t.height+'px;">'+(i+1)+"</div>",a+='<div class="track-end" style="width:6px; height: '+t.height+'px;"></div>',a+="</div>";r(".tracklist").append(a);var e="";for(i=0;i<10;i++)e+='<div class="player player'+i+'" style="top:'+(i+1)*t.height+'px;">',e+='<div class="yyytp">',e+='<img class="yyyimg" src="/Modules/Game/templates/front/pig/assets/images/pig.gif" />',e+='<div class="lunzia car'+i+' okplay"></div>',e+='<div class="lunzib car'+i+' okplay"></div>',e+="</div>",e+='<div class="pnctx">',e+="<div class='head shake' style='background-image: url(/Modules/Game/templates/front/pig/assets/images/default.png)'></div>",e+='<div class="nickname">选手'+(i+1)+"号</div>",e+="</div>",e+="</div>";r(".tracklist").append(e),r(".track-end").show(),r(".paomabeijing2,.paomabeijing,.tracklist,.player .lunzia,.player .lunzib").removeClass("okplay"),r(".paomabeijing2,.paomabeijing,.tracklist,.player .lunzia,.player .lunzib").addClass("okplay")},ph_arr:[],oldprogress:[],showprogress:function(t,a){null==this.oldprogress[t]&&(this.oldprogress[t]=0);var i=this.calcProgress(a.point);null==this.tracklist_elm&&this.resize();var e=r(".player"+t);e.css("left",i+"%"),e.find(".head").css({"background-image":"url("+a.avatar+")"}),e.find(".nickname").text(a.nickname)},maxpoint:0,calcProgress:function(t){if(1!=GAMECONFIG.themeconfig.durationtype)return 100<=(a=Math.round(t/GAMECONFIG.themeconfig.duration*100))?100:a;this.maxpoint=this.maxpoint>parseInt(t)?this.maxpoint:parseInt(t);var a=Math.round(t/this.maxpoint*n*100);return console.warn("point:"+t+" maxpoint:"+this.maxpoint+" percent:"+n),a}},g={result_layer:null,result_label:null,result_cap:null,player_tp:r("<div class='player'><div class='head'></div><div class='nickname'></div></div>"),showGameoverPage:function(){r(".paomabeijing2,.paomabeijing,.tracklist,.player .lunzia,.player .lunzib").removeClass("okplay"),this.result_layer.show().animateControl("bounceIn"),r("#Audio_Gameover")[0].play()},show:function(t){var a=this;this.result_layer=r(".rank_box"),winner_num=t.num,10<t.num?setTimeout(function(){a.showScore()},1e3):(this.showGameoverPage(),function(t){var a=t.length;if(1<=a&&t[0]){var i=r(".rank1");i.find("img").attr("src",t[0].avatar),i.find("p").text(t[0].nickname)}if(2<=a&&t[1]){var i=r(".rank2");i.find("img").attr("src",t[1].avatar),i.find("p").text(t[1].nickname)}if(3<=a&&t[2]){var i=r(".rank3");i.find("img").attr("src",t[2].avatar),i.find("p").text(t[2].nickname)}if(3<a)for(var e=r(".rank_others").find("li"),s=3,n=a;s<n;s++)r(e[s-3]).find(".avarta").attr("src",t[s].avatar),r(e[s-3]).find("p").text(t[s].nickname)}(t.users))},showScore:function(){showPage("/Modules/module.php?m=game&c=front&a=gameResult")}};showPage=function(t){var a=r('<div class="frame-dialog"><div class="phbphb" id="phbphb"><img src="/Modules/Game/templates/front/racing/assets/images/phb.png" class="phbtop" ><div class="phbk"><div class="phbbiaok"><iframe frameborder="0" src="'+t+'"></iframe></div></div></div></div>');a.css({"z-index":999}),a.appendTo("body").show()},(h=jQuery).fn.extend({animateControl:function(t,a){return this.addClass("animated "+t).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){a&&a(),h(this).removeClass("animated "+t)}),this}})});