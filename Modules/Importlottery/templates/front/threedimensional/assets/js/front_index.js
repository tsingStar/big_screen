var themeconfig=lotteryconfig.themeconfig,datalist=[],LotterySphere=function(){var e,n,t=new THREE.PerspectiveCamera(30,window.innerWidth/window.innerHeight,1,2e3);rotationspeedy=.005;rotationspeedx=0;function r(){0,e.rotation.y+=rotationspeedy,e.rotation.x+=rotationspeedx,requestAnimationFrame(r),n.render(e,t)}return{show:function(t){(e=function(t){for(var e=new THREE.Scene,n=new THREE.Vector3,r=t.length,o=160,i=0;i<o;i++){var a=document.createElement("div");a.className="element";var l=i%r,c=$('<div class="lottery-item"></div>');null!=t[l].datarow&&(t[l].datarow[0]&&$("<div>"+t[l].datarow[0]+"</div>").appendTo($(c)),t[l].datarow[1]&&$("<div>"+t[l].datarow[1]+"</div>").appendTo($(c)),t[l].datarow[2]&&$("<div>"+t[l].datarow[2]+"</div>").appendTo($(c)),""!=t[l].imagepath&&$('<img src="'+t[l].imagepath+'"></a>').appendTo($(a))),$(c).appendTo($(a));var s=new THREE.CSS3DObject(a);if(0==i)var p=1.025*Math.acos(2*i/o-1),u=Math.sqrt(o*Math.PI)*p*1.01;else p=Math.acos(2*i/o-1),u=Math.sqrt(o*Math.PI)*p;s.position.x=750*Math.cos(u)*Math.sin(p),s.position.y=750*Math.sin(u)*Math.sin(p),s.position.z=750*Math.cos(p),n.copy(s.position).multiplyScalar(2),s.lookAt(n),e.add(s)}return e}(t)).position.z=-4e3,(n=new THREE.CSS3DRenderer).setSize(window.innerWidth,window.innerHeight),n.domElement.style.position="absolute",n.domElement.style.top=0,$(n.domElement).appendTo("#lottery_main"),r()},speedup:function(){rotationspeedy=.1,rotationspeedx=.1},slowdown:function(){e.rotation.y=0,e.rotation.x=0,rotationspeedy=.005,rotationspeedx=0},stop:function(){e.rotation.y=0,e.rotation.x=0,rotationspeedy=0,rotationspeedx=0}}}(),currentprizeindex=0,leftnum=0,current_userleft=0,current_prizeleft=0,currentprizeid=0;function initNavBar(){if(window.sessionStorage.bgpath=themeconfig.bg_path,window.sessionStorage.bgmusic=themeconfig.bgmusic_path+"|"+themeconfig.bgmusic_switch,0<lotteryconfigs.length){for(var t=[],e=["","icon3dcj","iconfdc"],n=0,r=lotteryconfigs.length;n<r;n++){var o=configid==lotteryconfigs[n].id?1:2;t[n]={name:lotteryconfigs[n].title,action:"/Modules/module.php?m=importlottery&c=front&a=index&id="+lotteryconfigs[n].id,icon:e[lotteryconfigs[n].themeid],current:o}}window.top.roundbar().init(t)}}function initMinibar(){window.top.minibar.init({})}function initTheme(){$(".lottery-left").css({"background-color":themeconfig.leftcolor}),$(".lottery-right").css({"background-color":themeconfig.rightcolor}),$("#lottery_main .element").css({"background-color":themeconfig.ballcolor}),$("#lottery_main .lottery-item").css({color:themeconfig.ballfontcolor}),$(".win-list-item").css({"background-color":themeconfig.winnerbgcolor}),$(".win-list-item .lottery-item").css({color:themeconfig.winnerfontcolor}),$(".prizename").css({color:themeconfig.prizefontcolor}),$(".control-item,.winnernum").css({color:themeconfig.fontcolor})}$(document).ready(function(){init(),ajaxGetRandData().done(function(t){0<t.code&&(LotterySphere.show(t.data),datalist=t.data,initTheme())}),$(".btn-start").on("click",start),$(".btn-stop").on("click",stop),$(".btn-left").on("click",prevprize),$(".btn-right").on("click",nextprize),$(".icon-minus").on("click",minusnum),$(".icon-plus").on("click",plusnum),initNavBar(),initMinibar()});var isstarted=!1;function start(){if(current_userleft<leftnum)return alert("已经没有足够的人数可以用来抽奖了"),!1;if(current_prizeleft<leftnum)return alert("当前奖品的剩余数量不够了哦"),!1;if(0==isstarted){var t=$(".btn-start");lotterymain(leftnum),LotterySphere.speedup(),$(t).removeClass("btn-start"),$(t).addClass("btn-stop"),$(t).off("click"),$(t).on("click",stop)}isstarted=!0}var maxnum=9;function showresult(t){var e=t.length;if(maxnum<e){newdata=t.slice(0,maxnum),t=t.slice(maxnum),lotterymainresult(newdata);var n=!1;$(".lottery-scroll li").animate({top:"-100px",left:"100px",opacity:"0"},1e3,"swing",function(){0==n&&(n=!0,winnerslist(newdata),setTimeout(function(){showresult(t)},1e3))})}else{lotterymainresult(t);n=!1;$(".lottery-scroll li").animate({top:"-100px",left:"100px",opacity:"0"},1e3,"swing",function(){0==n&&(n=!0,winnerslist(t))})}}var winnerscorll=null;function winnerslist(t){for(var e="",n=0,r=t.length;n<r;n++)e+=winnerhtml(t[n]);$(e).prependTo(".lottery-win-scroll"),$(".lottery-win-scroll li").slideDown(),null==winnerscorll?(winnerscorll=$(".lottery-win-scroll")).niceScroll():setTimeout(function(){winnerscorll.getNiceScroll().resize()},500)}function lotterymainresult(t){$(".lottery-scroll").html("");for(var e="",n=0,r=t.length;n<r;n++){e+=lotteryscrollitem(t[n])}$(e).appendTo($(".lottery-scroll"))}function lotteryscrollitem(t){var e='<li><div class="lottery-item" >{{col1}}{{col2}}{{col3}}</div>{{img}}</li>';return e=t.datarow[0]?e.replace("{{col1}}","<p>"+t.datarow[0]+"</p>"):e.replace("{{col1}}","<p></p>"),e=t.datarow[1]?e.replace("{{col2}}","<p>"+t.datarow[1]+"</p>"):e.replace("{{col2}}","<p></p>"),e=t.datarow[2]?e.replace("{{col3}}","<p>"+t.datarow[2]+"</p>"):e.replace("{{col3}}","<p></p>"),e=""!=t.imagepath?e.replace("{{img}}",'<img src="'+t.imagepath+'">'):e.replace("{{img}}","")}var newwinners=[];function stop(){ajax_act_get_result(leftnum).done(function(t){if(0<t.code){newwinners=t.data,current_userleft-=t.data.length,newwinners=newwinners.sort(function(){return Math.random()});var e=$(".winnernum_txt").text();e=parseInt(e),e+=newwinners.length,$(".winnernum_txt").text(e),showresult(newwinners)}else $(".lottery-scroll").html(""),alert(t.message)}).fail(function(){alert("请检查一下您的网络，是否断网了")}).always(function(){isstarted=!1;var t=$(".btn-stop");LotterySphere.slowdown(),$(t).removeClass("btn-stop"),$(t).addClass("btn-start"),$(t).off("click"),$(t).on("click",start),clearTimeout(lotterymain_timer)})}var lotterymain_timer=null;function lotterymain(t){$(".lottery-scroll").html(""),t=(t=maxnum<t?maxnum:t)<1?1:t;for(var e="",n=datalist.length,r=0;r<t;r++){e+=lotteryscrollitem(datalist[Math.floor(Math.random()*n)])}$(e).appendTo($(".lottery-scroll")),lotterymain_timer=setTimeout(function(){lotterymain(t,datalist)},100)}function winnerhtml(t){var e='<li class="win-list-item" style="display:none"><div class="lottery-item">{{col1}}{{col2}}{{col3}}</div>{{image}}</li>';return e=t.datarow[0]?e.replace("{{col1}}","<p>"+t.datarow[0]+"</p>"):e.replace("{{col1}}","<p></p>"),e=t.datarow[1]?e.replace("{{col2}}","<p>"+t.datarow[1]+"</p>"):e.replace("{{col2}}","<p></p>"),e=t.datarow[2]?e.replace("{{col3}}","<p>"+t.datarow[2]+"</p>"):e.replace("{{col3}}","<p></p>"),e=null!=t.imagepath&&""!=t.imagepath?e.replace("{{image}}",'<img src="'+t.imagepath+'">'):e.replace("{{image}}","")}function init(){prizesdata.length<=0?alert("所有的奖品已经抽完了"):(setprizeinfo(currentprizeindex),$(".lottery-num").html(1),leftnum=1)}function nextprize(){var t=prizesdata.length;1!=t&&(t<=++currentprizeindex&&(currentprizeindex=0),setprizeinfo(currentprizeindex))}function prevprize(){var t=prizesdata.length;1!=t&&(--currentprizeindex<0&&(currentprizeindex=t-1),setprizeinfo(currentprizeindex))}function setprizeinfo(t){$("#prizedata").attr("data",prizesdata[t].id),$(".prizename_txt").text(prizesdata[t].prizename),$(".lottery-img").find("img").attr("src",prizesdata[t].formatedtext.text),currentprizeid=prizesdata[t].id,ajax_act_get_ready().done(function(t){if(0<t.code){if(current_userleft=t.data.count,current_prizeleft=t.data.prizenum,$(".lottery-win-scroll").html(""),$(".winnernum_txt").text(0),0<t.data.winners.length&&(winnerslist(t.data.winners),$(".winnernum_txt").text(t.data.winners.length)),2==themeconfig.selectall){var e=current_prizeleft<current_userleft?current_prizeleft:current_userleft;$(".lottery-num").text(e),leftnum=e}}else alert(t.message)}).fail(function(){alert("请检查一下您的网络，是否断网了")}).always(function(){})}function plusnum(){var t=$(".lottery-num").html();t=parseInt(t)+1,leftnum=t,$(".lottery-num").html(t)}function minusnum(){var t=$(".lottery-num").html();t=parseInt(t),leftnum=t=t-1<=1?1:t-1,$(".lottery-num").html(t)}function ajax_act_get_ready(){return $.ajax({url:"/Modules/module.php?m=importlottery&c=front&a=ajax_act_get_ready&prizeid="+currentprizeid,type:"get",dataType:"json"})}function ajaxGetRandData(){return $.ajax({url:"/Modules/module.php?m=importlottery&c=front&a=ajaxGetRandData",type:"get",dataType:"json"})}function ajax_act_get_result(t){return $.ajax({url:"/Modules/module.php?m=importlottery&c=front&a=ajax_act_get_result&num="+t+"&prizeid="+currentprizeid,type:"get",dataType:"json"})}hotkeys("up",function(t){plusnum()}),hotkeys("down",function(t){minusnum()}),hotkeys("left",function(t){prevprize()}),hotkeys("right",function(t){nextprize()}),hotkeys("space",function(t){0==t.repeat&&(0==isstarted?start():stop())});