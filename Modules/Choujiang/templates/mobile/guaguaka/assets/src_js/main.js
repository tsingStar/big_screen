requirejs.config({baseUrl:".",paths:{jquery:"/assets/js/jquery-3.3.1.min",scratchpad:"./Choujiang/templates/mobile/guaguaka/assets/js/wScratchPad",jquery_weui:"/mobile/template/app/js/jquery-weui.min"},shim:{scratchpad:{deps:["jquery"],exports:"scratchpad"},jquery_weui:{deps:["jquery"],exports:"jquery_weui"}}}),require(["jquery","scratchpad","jquery_weui"],function(a){var n=!1;a("#scratchpad").wScratchPad({width:150,height:40,color:"#a9a9a7",scratchMove:function(e,t){if(parseInt(JOININFO.lefttimes)<=0)return a("#prize").html("谢谢参与"),!1;var i=a("#prize").html();if(20<t&&""==i){if(console.log(n),1==n)return!1;n=!0,a.ajax({url:"/Modules/module.php?m=choujiang&c=mobile&a=ajax_act_get_prize",data:{userid:USERINFO.id,id:CONFIG.id},dataType:"json",type:"get"}).done(function(e){if(JOININFO.lefttime=JOININFO.lefttime-1,0<e.code)return null==e.data?void(i="谢谢参与"):void(i=e.data.prizename);alert(e.message),i="谢谢参与",-2==e.code&&(JOININFO.lefttimes=0)}).fail(function(){alert("请检查你的网络情况")}).always(function(){""==i&&(i="谢谢参与"),a("#prize").html(i)})}}}),a(document).ready(function(){var e=(new Date).getTime();(e=Math.floor(e/1e3))<CONFIG.started_at&&(alert("活动还未开始 请耐心等待！"),window.location.href="/mobile/qiandao.php?rentopenid="+USERINFO.openid),e>CONFIG.ended_at&&(alert("活动已经结束 下次早点来哦！"),window.location.href="/mobile/qiandao.php?rentopenid="+USERINFO.openid),parseInt(JOININFO.lefttimes)<=0&&a("#lottery-again").css({display:"none"}),a("#cjtimes").html(parseInt(JOININFO.cjtimes)+parseInt(JOININFO.lefttimes)),a("#usedtimes").html(JOININFO.cjtimes),a("#description").html(CONFIG.description),a("#gocjresult").on("click",function(){window.location.href="/mobile/cjresult.php?rentopenid="+USERINFO.openid}),a("#lottery-again").on("click",function(){n=!1,a("#scratchpad").wScratchPad("reset"),a("#prize").html("")})}),a(document).ready(function(){a(".dropdown-menu").click(function(){a("#pop_nav").hasClass("weui-popup-container-visible")||(a("#pop_nav").popup(),a(".weui-popup-overlay").show())}),a(document).on("WeixinJSBridgeReady",function(){WeixinJSBridge.call("hideOptionMenu")})})});