var tmr_cutdown_start,$PlayeSeed;function showGameResult(){var e=$(".result-layer").show(),t=$(".result-label",e).show().addClass("pulse"),n=$(".result-cup",e).hide(),a=pnum;$(".button.allresult",n).show(),$(".button.nexttound").show(),document.getElementById("Audio_Gameover").play(),window.setTimeout(function(){t.fadeOut(function(){n.show(function(){1<=a&&rankTopTen[0]&&window.setTimeout(function(){var e=$PlayeSeed.clone().addClass("result").css({left:"50%","margin-left":"-65px",width:"160px",height:"160px",bottom:"150px"});e.find(".head").css({"background-image":"url("+rankTopTen[0].client_avatar+")"}).addClass("shake"),e.find(".nickname").html(rankTopTen[0].client_name),e.appendTo(n).addClass("bounce")},800),2<=a&&rankTopTen[1]&&window.setTimeout(function(){var e=$PlayeSeed.clone().addClass("result").css({left:"40px",width:"100px",height:"100px",bottom:"120px"});e.find(".head").css({"background-image":"url("+rankTopTen[1].client_avatar+")"}).addClass("shake"),e.find(".nickname").html(rankTopTen[1].client_name),e.appendTo(n).addClass("bounce")},1800),3<=a&&rankTopTen[2]&&window.setTimeout(function(){var e=$PlayeSeed.clone().addClass("result").css({right:"30px",width:"70px",height:"70px",bottom:"100px"});e.find(".head").css({"background-image":"url("+rankTopTen[2].client_avatar+")"}).addClass("shake"),e.find(".nickname").html(rankTopTen[2].client_name),e.appendTo(n).addClass("bounce")},2800)})}).removeClass("pulse")},1e3)}$PlayeSeed=$('<div class="player"><div class="head"></div><div class="nickname"></div></div>').css({width:$(".pashuMain").height()/10-20,height:$(".pashuMain").height()/10-20});var resizePart=window.WBActivity.resize=function(){};function showScore(e,t){a="/wall/pashu_result.php",$.showPage(a,t)}var start=window.WBActivity.start=function(){window.WBActivity.hideLoading(),$(".Panel.Top").css({top:0}),$(".Panel.Bottom").css({bottom:0}),$(".pashuMain").css({opacity:1}),0==pashu_config.id?($(".pashuMain,.round-welcome").hide(),$(".btn-endgame").hide(),showScore(),$(".frame-dialog .closebutton").hide()):$(".round-welcome").slideDown().find(".round-label").html("轮次编号："+pashu_config.id),$(".round-welcome .button-start").on("click",function(){$(".round-welcome").slideUp(function(){$("#reset_btn").show(),$("#reset_btn").css({opacity:.3}),cutdown_start()})}),$(".button.allresult").on("click",function(){$(".result-layer").hide(),showScore(pashu_config.id)}),$("#reset_btn").on("mouseover",function(){$(this).css({opacity:1})}),$("#reset_btn").on("mouseout",function(){$(this).css({opacity:.3})}),$(".button.reset").on("click",resetgame),$("#reset_btn").on("click",resetgame)},resetgame=function(){confirm("重玩本轮会导致本轮成绩作废并清空，您确定吗？")&&$.getJSON("ajax_act_pashu.php?action=pashu_reset",{},function(e){1==e.code?(layer.msg("重置成功,2秒钟后自动刷新数据"),setTimeout(function(){window.location.reload()},2e3)):(-2==e.errno?layer.msg("当前轮数无人参与、无法开始!"):layer.msg("重置失败啦"),window.location.reload())})},cutdown_start=function(){if(3==pashu_config.status)return layer.msg("本轮游戏已经结束"),setTimeout(function(){window.location.reload()},3e3),!1;var t=$(".cutdown-start"),e=1*ready_time+1;t.html("").show().css({"margin-left":-t.width()/2+"px","margin-top":-t.height()/2+"px","font-size":.7*t.height()+"px","line-height":t.height()+"px"}).addClass("cutdownan-imation"),tmr_cutdown_start=window.setInterval(function(){0==--e?(t.html("GO!"),$.getJSON("ajax_act_pashu.php",{action:"pashu_start"},function(e){0<e.code?($(".pashuMain .houzi_user").removeClass("houzi_user"),t.hide()):(e.code,t.hide(),layer.msg(e.message),setTimeout(function(){window.location.reload()},3e3))}).fail(function(){t.hide(),layer.msg("无法连接游戏服务器，请刷新重新开始"),setTimeout(function(){window.location.reload()},3e3)})):e<0?(window.clearInterval(tmr_cutdown_start),clearInterval(get_man),hideSlogan(),window.pashu_data_time=setInterval(function(){$.ajax({url:"/wall/ajax_act_pashu.php?action=working",type:"get",dataType:"json",success:function(e){if(2==e.code&&pa_top(e.data),3==e.code){for(var t=$(".houzik").children("div"),n=0;n<t.length;n++){var a=$(t[n]).find(".guozik");$(a).css("opacity",1).css("top",$(".pashuMain").height())}mgame_end()}}})},1e3)):(document.getElementById("Audio_CutdownPlayer").play(),t.html(e))},1e3)};function hideSlogan(){$(".Panel.Top").css({top:"-"+$(".Panel.Top").height()+"px"}),$(".Panel.Bottom").css({bottom:"-"+$(".Panel.Bottom").height()+"px"}),$("#panel_status").val("0")}function showSlogan(){$(".Panel.Top").css({top:0}),$(".Panel.Bottom").css({bottom:0}),$("#panel_status").val("1")}