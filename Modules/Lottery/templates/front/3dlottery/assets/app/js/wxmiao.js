$(function(){var t,e=$("#lottery-panel"),o=e.find(".icon-minus"),a=e.find(".icon-plus"),r=e.find(".lottery-num"),l=1,i=void 0,s=void 0;function n(){this.gameOverEle=document.getElementById("Audio_Gameover"),this.gameRuningEle=document.getElementById("Audio_Running")}function d(){l++,r.text(l)}function c(){l=--l<1?1:l,r.text(l)}function m(){TWEEN.removeAll(),$("#container").hide()}function p(t,o){$.ajax({url:rootDomain+"/Modules/module.php?m=lottery&c=front&a=prize_info",dataType:"JSON",type:"POST",data:{activityid:t.activeId,prizeid:t.prizeid},success:function(t){if(1e5==t.status){if(0<t.data.winners.length){var e=t.data.winners;$.each(e,function(t,e){var o=e.bd_data,a="";if(1==bd_show&&0<lottory_show.length&&o)for(var r=0;r<lottory_show.length;r++)"mobile"!=lottory_show[r]||(lottory_show[r]=o[lottory_show[r]].replace(/(\d{3})\d{4}(\d{4})/,"$1****$2")),a+=0<r?"<br>"+o[lottory_show[r]]||" ":o[lottory_show[r]]||" ";h(e.id,e.avatar,e.nick_name,a,2)})}}else $("#lottory_winner_total").text(0);$.isFunction(o)&&o()}})}function y(){i.gameOver();$("#tagNum").val();clearInterval(t),startGame||(startGame=!0,transform(targets.grid,50,"grid",2e4),$(".lottory-container").hide(),i.gameRunning(),$("#container").show(),$.ajax({url:rootDomain+"/Modules/module.php?m=lottery&c=front&a=ajaxGetLotteryResult",dataType:"json",async:!0,type:"POST",data:{activityid:configid,prizeid:$("#tagId").val(),num:l},success:function(t){1==t.code?(setTimeout(function(){$("#close-draw").css({display:"flex"})},500),luckUsers=[],luckUsers=t.data,luckUsers.length<=0&&(i.gameRunning(),layer.msg("奖池人数太少了！",{time:2e3}),clearInterval(s),m(),$("#close-draw").hide(),$(".lottory-container").show(),startGame=!1)):(i.gameRunning(),layer.msg(t.message,{time:2e3}),clearInterval(s),m(),$("#close-draw").hide(),$(".lottory-container").show(),startGame=!1)},error:function(){i.gameRunning(),layer.msg("网络不好、请稍后重试",{time:2e3}),clearInterval(s),m(),$("#close-draw").hide(),$(".lottory-container").show(),startGame=!1},timeout:15e3}))}function u(){if(0<$(".pop_detail").length&&$(".pop_detail").remove(),!overGame){i.gameRunning(),clearInterval(s),overGame=!0;var t=0;if("1"==cj_showtype)if(0==(t=luckUsers.length-1))_(luckUsers[t].id,luckUsers[t].avatar,luckUsers[t].nick_name,luckUsers[t].bd_data),clearInterval(showGame),overGame=!1,startGame=!1;else{_(luckUsers[t].id,luckUsers[t].avatar,luckUsers[t].nick_name,luckUsers[t].bd_data);var e=luckshowtime+2e3;showGame=setInterval(function(){t<=0?(clearInterval(showGame),overGame=!1,startGame=!1):(0<$("#Audio_Gameover").length&&$("#Audio_Gameover")[0].pause(),t--,_(luckUsers[t].id,luckUsers[t].avatar,luckUsers[t].nick_name,luckUsers[t].bd_data))},e)}else 1==luckUsers.length?_(luckUsers[t].id,luckUsers[t].avatar,luckUsers[t].nick_name,luckUsers[t].bd_data):function(l){var t="";t+='<div class="pop_3d lottory_totalshow"><div class="lottory_selected">',t+="<ul>";for(var e=0;e<l.length;e++){t+='<li class="total_userone">';var o=l[e],a=(l[e].avatar,l[e].nick_name),r=l[e].bd_data;t+='<img class="userone_avatar" src="'+o.avatar+'">';var s="";if(1==bd_show&&0<lottory_show.length&&null!=r&&r){for(var n=0;n<lottory_show.length;n++)"mobile"!=lottory_show[n]||(r[lottory_show[n]]=r[lottory_show[n]].replace(/(\d{3})\d{4}(\d{4})/,"$1****$2")),s+=0<n?"<br>"+r[lottory_show[n]]||" ":r[lottory_show[n]]||" ";t+='<div class="userone_name">'+s+"</div>"}else t+='<div class="userone_name">'+a+"</div>";t+="</li>"}t+="</ul></div></div>",$("body").append(t),i.gameOverPlay(),$(".lottory_totalshow").css({display:"flex"}),$(".lottory_totalshow ul").addClass("pop_zoomInDown"),$(".win-box").addClass("winMove"),setTimeout(function(){$(".lottory_totalshow").animate({left:$(".lottory-reward-box").offset().left+10,top:$(".lottory-reward-box").offset().top+10,width:$(".lottory-reward-box").width(),height:$(".lottory-reward-box").height()},function(){$(".lottory_totalshow ul").removeClass("pop_zoomInDown"),$(".lottory_totalshow").hide(),$(".lottory_totalshow").remove(),$(".win-box").removeClass("winMove");for(var t=l.length-1;0<=t;t--){var e=l[t],o=e.bd_data,a="";if(1==bd_show&&0<lottory_show.length&&o)for(var r=0;r<lottory_show.length;r++)"mobile"!=lottory_show[r]||(o[lottory_show[r]]=o[lottory_show[r]].replace(/(\d{3})\d{4}(\d{4})/,"$1****$2")),a+=0<r?"<br>"+o[lottory_show[r]]||" ":o[lottory_show[r]]||" ";h(e.id,e.avatar,e.nick_name,a,1)}})},luckshowtime)}(luckUsers),clearInterval(showGame),overGame=!1,startGame=!1;$("#close-draw").hide(),m(),$(".lottory-container").show()}}n.prototype.gameOver=function(){this.gameOverEle&&this.gameOverEle.pause()},n.prototype.gameRunning=function(){this.gameRuningEle&&this.gameRuningEle.pause()},n.prototype.gameOverPlay=function(){this.gameOverEle&&this.gameOverEle.play()},$.ajax({url:rootDomain+"/Modules/module.php?m=lottery&c=front&a=prize_ajax",dataType:"json",success:function(t){if("100000"==t.status){t.data.prizes&&0<t.data.prizes.length&&function(t){for(var e,o="",a=0;e=t[a];a++)o+='<li class="hidden" data-active-id="'+e.activityid+'" data-id="'+e.id+'"data-tagname="'+e.prizename+'"data-tagNum="'+e.num+'"><img class="img-circle lottory-big-head"src="'+rootDomain+e.formatedtext.text+'"><div class="lottory-present-name">'+e.prizename+"</div></li>";$("#core_lottery_award_box").html(o),$("#core_lottery_award_box li:eq(0)").removeClass("hidden").addClass("selected"),1==$(".lottory_award_box li").length&&$(".lottory-next-btn").addClass("lottory-btn-disabled")}(t.data.prizes),$("#lottory_join_total").html(t.data.joinNum||0);var e=$(".lottory_award_box .selected").attr("data-id");p({activeId:$(".lottory_award_box .selected").attr("data-active-id"),prizeid:e}),$("#tagId").val(e),i=new n}}}),$.ajax({url:rootDomain+"/Modules/module.php?m=lottery&c=front&a=ajaxGetTempUsersInfo",dataType:"json",success:function(t){if("100000"==t.status&&0<t.data.length&&personArray){for(var e,o=0;e=t.data[o];o++)personArray.push({id:e.id,image:e.avatar,name:e.nickname});s=setInterval(function(){$(".element").eq(12).find("img").attr("src",personArray[Math.floor(Math.random()*personArray.length)].image),$(".element").eq(37).find("img").attr("src",personArray[Math.floor(Math.random()*personArray.length)].image),$(".element").eq(62).find("img").attr("src",personArray[Math.floor(Math.random()*personArray.length)].image),$(".element").eq(87).find("img").attr("src",personArray[Math.floor(Math.random()*personArray.length)].image),$(".element").eq(112).find("img").attr("src",personArray[Math.floor(Math.random()*personArray.length)].image)},60)}}}),t=setInterval(function(){get_new_sign_list()},5e3),$("#close-draw").on("click",function(){u()}),o.on("click",function(){c()}),a.on("click",function(){d()}),hotkeys("up",function(t){d()}),hotkeys("down",function(t){c()}),hotkeys("left",function(t){$(".lottory-prev-btn").trigger("click")}),hotkeys("right",function(t){$(".lottory-next-btn").trigger("click")}),$("body").on("click",".close-detail-btn",function(){$(".pop_detail").remove()}),$("body").on("mouseover mouseout",".lottory-reward-people",function(t){"mouseover"==t.type?$(this).find(".lottory-del-btn").show():"mouseout"==t.type&&$(this).find(".lottory-del-btn").hide()}),$("body").on("mouseover mouseout",".lottory-reward-people",function(t){"mouseover"==t.type?$(this).find(".lottory-del-btn").show():"mouseout"==t.type&&$(this).find(".lottory-del-btn").hide()}),$("body").on("click",".lottory-del-btn",function(t){t.stopPropagation();var e=layer.load(2,{shade:!1}),o=$(this),a=o.parent().attr("data-id");$.ajax({url:rootDomain+"/Modules/module.php?m=lottery&c=front&a=remove_lottery_recode",dataType:"JSON",type:"POST",data:{record_id:a},success:function(t){layer.close(e),1e5==t.status?(o.parent().remove(),$("#lottory_winner_total").text($(".lottory-reward-people").length)):layer.msg("中奖记录不存在删除失败了",{time:2e3})}})}),$("body").on("click",".lottory-reward-people",function(t){var e=$(this),o=e.attr("data-nickname");0<o.indexOf("<br>")&&(o=o.replace("<br>","&nbsp;&nbsp;"));var a="";a+='<div class="pop_3d pop_detail">',a+='<div class="fireworks"></div>',a+='<div class="pop_box">',a+='<div class="light"></div>',a+='<div class="pop_userInfo">',a+='<img class="lucker_avatar" src="'+e.attr("data-avatar")+'">',a+='<em></em><img class="close-detail-btn" src="'+assetsPath+'/app/images/del_btn.png"> style="display: block;">',a+="<span></span>",a+='<div class="lucker_nickname">'+o+"</div>",a+="</div></div></div>",$("body").append(a),$(".pop_detail").addClass("aniResult")}),$(".lottory-prev-btn").on("click",function(){if(1!=press_pre&&!$(this).hasClass("lottory-btn-disabled")){var t=layer.load(2,{shade:!1});press_pre=1;var e=$(".lottory_award_box .selected").index();$(".lottory_award_box li").attr("class","hidden"),$(".lottory_award_box li").eq(e-1).attr("class","selected"),$(".lottory-next-btn").removeClass("lottory-btn-disabled"),1==e?$(this).addClass("lottory-btn-disabled"):$(this).removeClass("lottory-btn-disabled"),$(".lottory-level,#lottory_winner_name").text($(".lottory_award_box .selected").attr("data-tagname")),$("#tagNum").val($(".lottory_award_box .selected").attr("data-tagNum")),$("#tagId").val($(".lottory_award_box .selected").attr("data-id")),$(".lottory-reward-box").empty(),p({activeId:$(".lottory_award_box .selected").attr("data-active-id"),prizeid:$("#tagId").val()},function(){layer.close(t),press_pre=0})}}),$(".lottory-next-btn").on("click",function(){if(1!=press_next&&!$(this).hasClass("lottory-btn-disabled")){var t=layer.load(2,{shade:!1});press_next=1;var e=$(".lottory_award_box .selected").index(),o=$(".lottory_award_box li").length;$(".lottory_award_box li").attr("class","hidden"),$(".lottory_award_box li").eq(e+1).attr("class","selected"),$(".lottory-prev-btn").removeClass("lottory-btn-disabled"),o-e==2?$(this).addClass("lottory-btn-disabled"):$(this).removeClass("lottory-btn-disabled"),$(".lottory-level,#lottory_winner_name").text($(".lottory_award_box .selected").attr("data-tagname")),$("#tagNum").val($(".lottory_award_box .selected").attr("data-tagNum")),$("#tagId").val($(".lottory_award_box .selected").attr("data-id")),$(".lottory-reward-box").empty(),p({activeId:$(".lottory_award_box .selected").attr("data-active-id"),prizeid:$("#tagId").val()},function(){layer.close(t),press_next=0})}}),$("#start-draw").unbind("click").bind("click",function(){0<$(".pop_3d").length||($("#lottory_join_total").text()<=0?layer.msg("奖池人数太少啦!",{time:2e3}):y())});var _=function(t,e,o,a){var r="";r+='<div class="pop_3d">',r+='<div class="fireworks"></div>',r+='<div class="pop_box">',r+='<div class="light"></div>',r+='<div class="pop_userInfo">',r+='<img class="lucker_avatar" src="'+e+'">',r+="<em></em>",r+="<span></span>";var l="";if(1==bd_show&&0<lottory_show.length&&""!=a){for(var s=0;s<lottory_show.length;s++)"mobile"!=lottory_show[s]||(a[lottory_show[s]]=a[lottory_show[s]].replace(/(\d{3})\d{4}(\d{4})/,"$1****$2")),l+=0<s?"&nbsp;&nbsp;"+a[lottory_show[s]]||" ":a[lottory_show[s]]||" ";r+='<div class="lucker_nickname">'+l+"</div>"}else r+='<div class="lucker_nickname">'+o+"</div>";r+="</div></div></div>",$("body").append(r),$(".pop_3d").addClass("aniResult"),i.gameOverPlay(),setTimeout(function(){$(".pop_3d .fireworks,.pop_3d .light,.pop_3d em,.pop_3d span,.lucker_nickname").css("display","none"),$(".pop_3d img").css({width:90,height:90,"border-radius":"50%"}),$(".pop_3d").animate({left:$(".lottory-reward-box").offset().left+10,top:$(".lottory-reward-box").offset().top+10,width:90,height:90},function(){$(".pop_3d").css({"background-color":"transparent"}),$(".pop_3d").hide(),$(".pop_3d").remove(),0<l.indexOf("&nbsp;&nbsp;")&&(l=l.replace("&nbsp;&nbsp;","<br>")),h(t,e,o,l,1)})},luckshowtime)};function h(t,e,o,a,r){var l="";l+='<div class="lottory-reward-people" data-id="'+t+'" data-nickname="'+(""==a?o:a)+'" data-avatar="'+e+'">',l+='<div class="lottory-r-div">',l+='<img class="lottory-reward-people-img" src="'+e+'">',l+="</div>",l+='<div class="lottory-r-name">'+(""==a?o.substring(0,6):a)+"</div>",l+='<img class="lottory-del-btn"  src="'+assetsPath+'/app/images/del_btn.png">',l+="</div>",$(".lottory-reward-box").prepend(l),$("#lottory_winner_total").text($(".lottory-reward-people").length)}document.onkeypress=function(t){if("block"!=$(".loginform").css("display")){var e=t||window.event,o=e.keyCode||e.which;switch(console.log(o),o){case 13:$(".lottory_selectnum").focus();break;case 32:if(0<$(".pop_3d").length)return;if($("#close-draw").is(":hidden")){if(startGame)return;if($("#lottory_join_total").text()<=0)return void layer.msg("奖池人数太少了!",{time:2e3});y()}else{if(overGame)return;u()}break;case 61:1!=$("#bg_music").attr("data-status")?($("#audio")[0].play(),$("#bg_music").attr("data-status","1"),$("#bg_music").find("img").attr("src","http://wq.imlehu.com/addons/meepo_xianchang/template/mobile/app/images/icon/icon_music.png")):($("#audio")[0].pause(),$("#bg_music").attr("data-status","0"),$("#bg_music").find("img").attr("src","http://wq.imlehu.com/addons/meepo_xianchang/template/mobile/app/no_music.png"));break;case 45:break;case 47:layer.confirm("您确定清空当前奖品的中奖记录吗？",{title:!1,btn:["确定","取消"]},function(t,e){var o=layer.load(2,{shade:!1});$.ajax({url:"./index.php?i=11&c=entry&rid=15&do=3d_lottory_reset&m=meepo_xianchang",timeout:1e4,type:"POST",dataType:"json",data:{activityid:configid,prizeid:$("#tagId").val()},success:function(t){layer.close(o),0==t.errno?(layer.msg("清空成功",{time:2e3}),$(".lottory-reward-people").remove(),$("#lottory_winner_total").text(0)):layer.msg("网络错误、请刷新重试！",{time:2e3})}})},function(t){layer.close(t)});break;case 46:0==press_next&&$(".lottory-next-btn").click();break;case 44:0==press_pre&&$(".lottory-prev-btn").click()}}}});