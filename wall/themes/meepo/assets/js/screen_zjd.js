var $ResultSeed,Winers,audio_Running,audio_GetOne,resizePart=window.WBActivity.resize=function(){},start=window.WBActivity.start=function(){window.WBActivity.hideLoading();var t=document.getElementById("Audio_Running");t.play&&(audio_Running=t);var e=document.getElementById("Audio_Result");e.play&&(audio_GetOne=e),$(".usercount-label").html("?"),$(".control").hide(),getReady(),$(".Panel.Top").css({top:0}),$(".Panel.Bottom").css({bottom:0}),$(".Panel.Lottery").css({display:"block",opacity:1}),$ResultSeed=$(".lottery-right .result-line"),$(".control.button-run").on("click",function(){start_game()}),$(".control.button-stop").on("click",function(){stop_game()}),$(".control.button-nextround").on("click",function(){window.location.reload()}),$(".button-reload").on("click",function(){window.location.reload()})},getReady=function(){var t=$("#tagid").val();if(-1==t)return!1;$(".lottery-right .result-line").remove(),$.getJSON(PATH_ACTIVITY+Path_url("ajax_act_lottory.php?action=ready"),{from:"zjd",awardid:t},function(t){0==t.ret&&null==t.data?($(".usercount-label").html("0"),alert("所有人都已经中奖了")):0==t.ret&&0<t.data.length?($(".usercount-label").html(t.count),$(".control.button-stop").fadeIn(),null!=t.luckuser&&$.each(t.luckuser,function(t,e){var n='<div class="result-line had_luck_user" style="display: block;">';n+='<div class="result-num">'+(t+1)+"</div>",n+='<div class="user" style="background-image: url('+e.avatar+');">',n+='<span class="nick-name">'+e.nick_name+"</span></div></div>",$(".lottery-right").prepend(n)})):alert("数据异常，无法进行抽奖，请刷新！")}).fail(function(){alert("无法连接服务器，请重试")})};function changeLuck(t){-1!=t&&getReady()}var getUser=function(n){window.setTimeout(function(){audio_GetOne&&audio_GetOne.play()},400),$(".lottery-right").scrollTop(0);var t=$(".lottery-right").scroll(0).children(".result-line").length,o=$ResultSeed.clone();o.find(".result-num").html(t+1),o.prependTo(".lottery-right").slideDown();var a=o.offset();$(".lottery-run").addClass("moving"),$(".lottery-run").removeClass("box-moving"),window.setTimeout(function(){window.setTimeout(function(){$(".lottery-run").removeClass("moving")},1e3);var t=$(".lottery-run .user"),e=t.clone().appendTo("body").css({position:"absolute",top:t.offset().top,left:t.offset().left,width:t.width(),height:t.height()}).addClass("").animate({width:60,height:60,top:a.top+5,left:a.left+50},500,function(){var t=e.css("background-image");e.appendTo(o).removeAttr("style").css({"background-image":t}),$.isFunction(n)&&n.call(this)})},1500)},stop_game=function(){$(".control.button-stop").hide();var t=$(".usercount-label").text(),e=parseInt(t);if((e=isNaN(e)?0:e)<=0)return alert("已经没有人可以参与抽奖了"),$(".control.button-stop").fadeIn(),!1;(winer_count=1*$("#num").val())<=e?getWiner():(alert("计划选"+winer_count+"人，但是只剩"+e+"人可选，请减少选取数！"),$(".control.button-stop").fadeIn())},winer_count=0,getWiner=function(){$.ajax({url:"ajax_act_lottory.php?action=ok",data:{awardid:$("select[name=luckTag]").val(),from:"zjd"},type:"post",dataType:"json",async:!0,success:function(t){if(0<t.ret){var e=parseInt($(".usercount-label").html());e=0<e-1?e-1:0,$(".usercount-label").html(e),$(".lottery-run .user").css({"background-image":"url("+t.data.avatar+")"}),$(".lottery-run .user .nick-name").html(t.data.nick_name),$(".lottery-run .user .mobile").html(t.data.mobile)}},error:function(){alert("您断网了，请检查网络连接是否正常")}}),window.setTimeout(function(){getUser(function(){0<--winer_count?window.setTimeout(function(){getWiner()},600):(audio_Running&&audio_Running.pause(),$(".control.button-stop").fadeIn())})},1e3)};