var $ResultSeed,Players,Winers,audio_Running,audio_GetOne,start=function(){var t=document.getElementById("Audio_Running");t.play&&(audio_Running=t);var e=document.getElementById("Audio_Result");e.play&&(audio_GetOne=e),$(".usercount-label").html("加载数据中..."),$(".control").hide(),$(".Panel.Top").css({top:0}),$(".Panel.Bottom").css({bottom:0}),$(".Panel.Lottery").css({display:"block",opacity:1}),$ResultSeed=$(".lottery-right .result-line"),$(".control.button-run").on("click",function(){start_game()}),$(".control.button-stop").on("click",function(){stop_game()}),$(".control.button-nextround").on("click",function(){window.location.reload()}),$(".button-reload").on("click",function(){window.location.reload()}),$(".select-button").on("click",function(t){var e=$(this),n=$(".select-value"),a=n.text();return e.hasClass("minus")?1<a&&(a--,n.text(a)):e.hasClass("plus")&&(a<Players.length?a++:n=Players.length,n.text(a)),t.preventDefault(),!1})};$(document).ready(function(){start()});var tmr_playanimate,getUser=function(e){audio_GetOne&&audio_GetOne.play(),$(".lottery-right").scrollTop(0);var t=$(".lottery-right").scroll(0).children(".result-line").length-1,n=$ResultSeed.clone();n.find(".result-num").html(1+t),n.prependTo(".lottery-right").slideDown();var a=n.offset(),r=$(".lottery-run .user"),o=r.clone().appendTo("body").css({position:"absolute",top:r.offset().top,left:r.offset().left,width:r.width(),height:r.height()}).animate({width:60,height:60,top:a.top+5,left:a.left+50},500,function(){var t=o.css("background-image");o.appendTo(n).removeAttr("style").css({"background-image":t}),$.isFunction(e)&&e.call(this)})},start_game=function(){console.log(Players),(winer_count=1*$(".select-value").text())<=Players.length?($(".control.button-run").hide(),flgPlaying=!0,playanimate(),audio_Running&&audio_Running.play(),window.setTimeout(function(){$(".control.button-stop").fadeIn()},500)):alert("计划选"+winer_count+"人，但是只剩"+Players.length+"人可选，请减少选取数！")},stop_game=function(){$(".control.button-stop").hide(),$.isArray(Players)?(winer_count=1*$(".select-value").text())<=Players.length?getWiner():alert("计划选"+winer_count+"人，但是只剩"+Players.length+"人可选，请减少选取数！"):alert("无法获得游戏数据，与游戏服务器断开，请刷新重试！")},winer_count=0,getWiner=function(){flgPlaying=!1,window.clearTimeout(tmr_playanimate);var t=Math.floor(Math.random()*Players.length),e=Players.splice(t,1)[0];$(".lottery-run2 .toux1").css({"background-image":"url("+e.avatar+")"});var n=Math.floor(Math.random()*Players.length),a=Players.splice(n,1)[0];$(".lottery-run2 .toux2").css({"background-image":"url("+a.avatar+")"});var r=Math.floor(Math.random()*Players.length),o=Players.splice(r,1)[0];$(".usercount-label").html(Players.length+"人"),$(".lottery-run .toux3").css({"background-image":"url("+o.avatar+")"}),$(".lottery-run .user .nick-name").html(o.nick_name);var l=Math.floor(Math.random()*Players.length),u=Players.splice(l,1)[0];$(".lottery-run2 .toux4").css({"background-image":"url("+u.avatar+")"});var i=Math.floor(Math.random()*Players.length),s=Players.splice(i,1)[0];$(".lottery-run2 .toux5").css({"background-image":"url("+s.avatar+")"}),window.setTimeout(function(){getUser(function(){0<--winer_count?(flgPlaying=!0,playanimate(),window.setTimeout(function(){getWiner()},1500)):(audio_Running&&audio_Running.pause(),$(".control.button-run").fadeIn())})},500)},curr_index=0,flgPlaying=!1,playanimate=function(){if(Players[curr_index]){var t=Players[curr_index];$(".lottery-run .user").css({"background-image":"url("+t.avatar+")"}),$(".lottery-run .user .nick-name").html(t.nick_name);var e=Math.floor(Math.random()*Players.length),n=Players.splice(e,1)[0];$(".lottery-run2 .toux1").css({"background-image":"url("+n.avatar+")"});var a=Math.floor(Math.random()*Players.length),r=Players.splice(a,1)[0];$(".lottery-run2 .toux2").css({"background-image":"url("+r.avatar+")"});var o=Math.floor(Math.random()*Players.length),l=Players.splice(o,1)[0];$(".lottery-run2 .toux4").css({"background-image":"url("+l.avatar+")"});var u=Math.floor(Math.random()*Players.length),i=Players.splice(u,1)[0];$(".lottery-run2 .toux5").css({"background-image":"url("+i.avatar+")"}),++curr_index>=Players.length&&(curr_index=0),flgPlaying&&(tmr_playanimate=window.setTimeout(playanimate,100))}};