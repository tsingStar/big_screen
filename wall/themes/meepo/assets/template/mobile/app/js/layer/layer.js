!function(h,e){"use strict";function t(e){var t=this;t.index=++g.index,t.config=m.extend({},t.config,d.config,e),document.body?t.creat():setTimeout(function(){t.creat()},30)}var m,c,i,n=h.layui&&layui.define,d={getPath:function(){var e=document.scripts,t=e[e.length-1],i=t.src;if(!t.getAttribute("merge"))return i.substring(0,i.lastIndexOf("/")+1)}(),config:{},end:{},minIndex:0,minLeft:[],btn:["&#x786E;&#x5B9A;","&#x53D6;&#x6D88;"],type:["dialog","page","iframe","loading","tips"],getStyle:function(e,t){var i=e.currentStyle?e.currentStyle:h.getComputedStyle(e,null);return i[i.getPropertyValue?"getPropertyValue":"getAttribute"](t)},link:function(e,t,i){if(g.path){var n=document.getElementsByTagName("head")[0],a=document.createElement("link");"string"==typeof t&&(i=t);var o="layuicss-"+(i||e).replace(/\.|\//g,""),s=0;a.rel="stylesheet",a.href=g.path+e,a.id=o,document.getElementById(o)||n.appendChild(a),"function"==typeof t&&function e(){return 80<++s?h.console&&console.error("layer.css: Invalid"):void(1989===parseInt(d.getStyle(document.getElementById(o),"width"))?t():setTimeout(e,100))}()}}},g={v:"3.1.0",ie:(i=navigator.userAgent.toLowerCase(),!!(h.ActiveXObject||"ActiveXObject"in h)&&((i.match(/msie\s(\d+)/)||[])[1]||"11")),index:h.layer&&h.layer.v?1e5:0,path:d.getPath,config:function(e,t){return e=e||{},g.cache=d.config=m.extend({},d.config,e),g.path=d.config.path||g.path,"string"==typeof e.extend&&(e.extend=[e.extend]),d.config.path&&g.ready(),e.extend&&(n?layui.addcss("modules/layer/"+e.extend):d.link("theme/"+e.extend)),this},ready:function(e){var t="layer",i=(n?"modules/layer/":"theme/")+"default/layer.css?v="+g.v;return n?layui.addcss(i,e,t):d.link(i,e,t),this},alert:function(e,t,i){var n="function"==typeof t;return n&&(i=t),g.open(m.extend({content:e,yes:i},n?{}:t))},confirm:function(e,t,i,n){var a="function"==typeof t;return a&&(n=i,i=t),g.open(m.extend({content:e,btn:d.btn,yes:i,btn2:n},a?{}:t))},msg:function(e,t,i){var n="function"==typeof t,a=d.config.skin,o=(a?a+" "+a+"-msg":"")||"layui-layer-msg",s=f.anim.length-1;return n&&(i=t),g.open(m.extend({content:e,time:2e3,shade:!1,skin:o,title:!1,closeBtn:!1,btn:!1,resize:!1,end:i},n&&!d.config.skin?{skin:o+" layui-layer-hui",anim:s}:(-1!==(t=t||{}).icon&&(void 0!==t.icon||d.config.skin)||(t.skin=o+" "+(t.skin||"layui-layer-hui")),t)))},load:function(e,t){return g.open(m.extend({type:3,icon:e||0,resize:!1,shade:.01},t))},tips:function(e,t,i){return g.open(m.extend({type:4,content:[e,t],closeBtn:!1,time:3e3,shade:!1,resize:!1,fixed:!1,maxWidth:210},i))}};t.pt=t.prototype;var f=["layui-layer",".layui-layer-title",".layui-layer-main",".layui-layer-dialog","layui-layer-iframe","layui-layer-content","layui-layer-btn","layui-layer-close"];f.anim=["layer-anim-00","layer-anim-01","layer-anim-02","layer-anim-03","layer-anim-04","layer-anim-05","layer-anim-06"],t.pt.config={type:0,shade:.3,fixed:!0,move:f[1],title:"&#x4FE1;&#x606F;",offset:"auto",area:"auto",closeBtn:1,time:0,zIndex:19891014,maxWidth:360,anim:0,isOutAnim:!0,icon:-1,moveType:1,resize:!0,scrollbar:!0,tips:2},t.pt.vessel=function(e,t){var i,n=this.index,a=this.config,o=a.zIndex+n,s="object"==typeof a.title,r=a.maxmin&&(1===a.type||2===a.type),l=a.title?'<div class="layui-layer-title" style="'+(s?a.title[1]:"")+'">'+(s?a.title[0]:a.title)+"</div>":"";return a.zIndex=o,t([a.shade?'<div class="layui-layer-shade" id="layui-layer-shade'+n+'" times="'+n+'" style="z-index:'+(o-1)+'; "></div>':"",'<div class="'+f[0]+" layui-layer-"+d.type[a.type]+(0!=a.type&&2!=a.type||a.shade?"":" layui-layer-border")+" "+(a.skin||"")+'" id="'+f[0]+n+'" type="'+d.type[a.type]+'" times="'+n+'" showtime="'+a.time+'" conType="'+(e?"object":"string")+'" style="z-index: '+o+"; width:"+a.area[0]+";height:"+a.area[1]+(a.fixed?"":";position:absolute;")+'">'+(e&&2!=a.type?"":l)+'<div id="'+(a.id||"")+'" class="layui-layer-content'+(0==a.type&&-1!==a.icon?" layui-layer-padding":"")+(3==a.type?" layui-layer-loading"+a.icon:"")+'">'+(0==a.type&&-1!==a.icon?'<i class="layui-layer-ico layui-layer-ico'+a.icon+'"></i>':"")+(1==a.type&&e?"":a.content||"")+'</div><span class="layui-layer-setwin">'+(i=r?'<a class="layui-layer-min" href="javascript:;"><cite></cite></a><a class="layui-layer-ico layui-layer-max" href="javascript:;"></a>':"",a.closeBtn&&(i+='<a class="layui-layer-ico '+f[7]+" "+f[7]+(a.title?a.closeBtn:4==a.type?"1":"2")+'" href="javascript:;"></a>'),i)+"</span>"+(a.btn?function(){var e="";"string"==typeof a.btn&&(a.btn=[a.btn]);for(var t=0,i=a.btn.length;t<i;t++)e+='<a class="'+f[6]+t+'">'+a.btn[t]+"</a>";return'<div class="'+f[6]+" layui-layer-btn-"+(a.btnAlign||"")+'">'+e+"</div>"}():"")+(a.resize?'<span class="layui-layer-resize"></span>':"")+"</div>"],l,m('<div class="layui-layer-move"></div>')),this},t.pt.creat=function(){var n=this,a=n.config,o=n.index,s="object"==typeof(l=a.content),r=m("body");if(!a.id||!m("#"+a.id)[0]){switch("string"==typeof a.area&&(a.area="auto"===a.area?["",""]:[a.area,""]),a.shift&&(a.anim=a.shift),6==g.ie&&(a.fixed=!1),a.type){case 0:a.btn="btn"in a?a.btn:d.btn[0],g.closeAll("dialog");break;case 2:var l=a.content=s?a.content:[a.content||"http://layer.layui.com","auto"];a.content='<iframe scrolling="'+(a.content[1]||"auto")+'" allowtransparency="true" id="'+f[4]+o+'" name="'+f[4]+o+'" onload="this.className=\'\';" class="layui-layer-load" frameborder="0" src="'+a.content[0]+'"></iframe>';break;case 3:delete a.title,delete a.closeBtn,-1===a.icon&&a.icon,g.closeAll("loading");break;case 4:s||(a.content=[a.content,"body"]),a.follow=a.content[1],a.content=a.content[0]+'<i class="layui-layer-TipsG"></i>',delete a.title,a.tips="object"==typeof a.tips?a.tips:[a.tips,!0],a.tipsMore||g.closeAll("tips")}if(n.vessel(s,function(e,t,i){r.append(e[0]),s?2==a.type||4==a.type?m("body").append(e[1]):l.parents("."+f[0])[0]||(l.data("display",l.css("display")).show().addClass("layui-layer-wrap").wrap(e[1]),m("#"+f[0]+o).find("."+f[5]).before(t)):r.append(e[1]),m(".layui-layer-move")[0]||r.append(d.moveElem=i),n.layero=m("#"+f[0]+o),a.scrollbar||f.html.css("overflow","hidden").attr("layer-full",o)}).auto(o),m("#layui-layer-shade"+n.index).css({"background-color":a.shade[1]||"#000",opacity:a.shade[0]||a.shade}),2==a.type&&6==g.ie&&n.layero.find("iframe").attr("src",l[0]),4==a.type?n.tips():n.offset(),a.fixed&&c.on("resize",function(){n.offset(),(/^\d+%$/.test(a.area[0])||/^\d+%$/.test(a.area[1]))&&n.auto(o),4==a.type&&n.tips()}),a.time<=0||setTimeout(function(){g.close(n.index)},a.time),n.move().callback(),f.anim[a.anim]){var e="layer-anim "+f.anim[a.anim];n.layero.addClass(e).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){m(this).removeClass(e)})}a.isOutAnim&&n.layero.data("isOutAnim",!0)}},t.pt.auto=function(e){var t=this.config,i=m("#"+f[0]+e);""===t.area[0]&&0<t.maxWidth&&(g.ie&&g.ie<8&&t.btn&&i.width(i.innerWidth()),i.outerWidth()>t.maxWidth&&i.width(t.maxWidth));function n(e){(e=i.find(e)).height(a[1]-o-s-2*(0|parseFloat(e.css("padding-top"))))}var a=[i.innerWidth(),i.innerHeight()],o=i.find(f[1]).outerHeight()||0,s=i.find("."+f[6]).outerHeight()||0;switch(t.type){case 2:n("iframe");break;default:""===t.area[1]?0<t.maxHeight&&i.outerHeight()>t.maxHeight?(a[1]=t.maxHeight,n("."+f[5])):t.fixed&&a[1]>=c.height()&&(a[1]=c.height(),n("."+f[5])):n("."+f[5])}return this},t.pt.offset=function(){var e=this,t=e.config,i=e.layero,n=[i.outerWidth(),i.outerHeight()],a="object"==typeof t.offset;e.offsetTop=(c.height()-n[1])/2,e.offsetLeft=(c.width()-n[0])/2,a?(e.offsetTop=t.offset[0],e.offsetLeft=t.offset[1]||e.offsetLeft):"auto"!==t.offset&&("t"===t.offset?e.offsetTop=0:"r"===t.offset?e.offsetLeft=c.width()-n[0]:"b"===t.offset?e.offsetTop=c.height()-n[1]:"l"===t.offset?e.offsetLeft=0:"lt"===t.offset?(e.offsetTop=0,e.offsetLeft=0):"lb"===t.offset?(e.offsetTop=c.height()-n[1],e.offsetLeft=0):"rt"===t.offset?(e.offsetTop=0,e.offsetLeft=c.width()-n[0]):"rb"===t.offset?(e.offsetTop=c.height()-n[1],e.offsetLeft=c.width()-n[0]):e.offsetTop=t.offset),t.fixed||(e.offsetTop=/%$/.test(e.offsetTop)?c.height()*parseFloat(e.offsetTop)/100:parseFloat(e.offsetTop),e.offsetLeft=/%$/.test(e.offsetLeft)?c.width()*parseFloat(e.offsetLeft)/100:parseFloat(e.offsetLeft),e.offsetTop+=c.scrollTop(),e.offsetLeft+=c.scrollLeft()),i.attr("minLeft")&&(e.offsetTop=c.height()-(i.find(f[1]).outerHeight()||0),e.offsetLeft=i.css("left")),i.css({top:e.offsetTop,left:e.offsetLeft})},t.pt.tips=function(){var e=this.config,t=this.layero,i=[t.outerWidth(),t.outerHeight()],n=m(e.follow);n[0]||(n=m("body"));var a={width:n.outerWidth(),height:n.outerHeight(),top:n.offset().top,left:n.offset().left},o=t.find(".layui-layer-TipsG"),s=e.tips[0];e.tips[1]||o.remove(),a.autoLeft=function(){0<a.left+i[0]-c.width()?(a.tipLeft=a.left+a.width-i[0],o.css({right:12,left:"auto"})):a.tipLeft=a.left},a.where=[function(){a.autoLeft(),a.tipTop=a.top-i[1]-10,o.removeClass("layui-layer-TipsB").addClass("layui-layer-TipsT").css("border-right-color",e.tips[1])},function(){a.tipLeft=a.left+a.width+10,a.tipTop=a.top,o.removeClass("layui-layer-TipsL").addClass("layui-layer-TipsR").css("border-bottom-color",e.tips[1])},function(){a.autoLeft(),a.tipTop=a.top+a.height+10,o.removeClass("layui-layer-TipsT").addClass("layui-layer-TipsB").css("border-right-color",e.tips[1])},function(){a.tipLeft=a.left-i[0]-10,a.tipTop=a.top,o.removeClass("layui-layer-TipsR").addClass("layui-layer-TipsL").css("border-bottom-color",e.tips[1])}],a.where[s-1](),1===s?a.top-(c.scrollTop()+i[1]+16)<0&&a.where[2]():2===s?0<c.width()-(a.left+a.width+i[0]+16)||a.where[3]():3===s?0<a.top-c.scrollTop()+a.height+i[1]+16-c.height()&&a.where[0]():4===s&&0<i[0]+16-a.left&&a.where[1](),t.find("."+f[5]).css({"background-color":e.tips[1],"padding-right":e.closeBtn?"30px":""}),t.css({left:a.tipLeft-(e.fixed?c.scrollLeft():0),top:a.tipTop-(e.fixed?c.scrollTop():0)})},t.pt.move=function(){var s=this,r=s.config,e=m(document),l=s.layero,t=l.find(r.move),i=l.find(".layui-layer-resize"),f={};return r.move&&t.css("cursor","move"),t.on("mousedown",function(e){e.preventDefault(),r.move&&(f.moveStart=!0,f.offset=[e.clientX-parseFloat(l.css("left")),e.clientY-parseFloat(l.css("top"))],d.moveElem.css("cursor","move").show())}),i.on("mousedown",function(e){e.preventDefault(),f.resizeStart=!0,f.offset=[e.clientX,e.clientY],f.area=[l.outerWidth(),l.outerHeight()],d.moveElem.css("cursor","se-resize").show()}),e.on("mousemove",function(e){if(f.moveStart){var t=e.clientX-f.offset[0],i=e.clientY-f.offset[1],n="fixed"===l.css("position");if(e.preventDefault(),f.stX=n?0:c.scrollLeft(),f.stY=n?0:c.scrollTop(),!r.moveOut){var a=c.width()-l.outerWidth()+f.stX,o=c.height()-l.outerHeight()+f.stY;t<f.stX&&(t=f.stX),a<t&&(t=a),i<f.stY&&(i=f.stY),o<i&&(i=o)}l.css({left:t,top:i})}if(r.resize&&f.resizeStart){t=e.clientX-f.offset[0],i=e.clientY-f.offset[1];e.preventDefault(),g.style(s.index,{width:f.area[0]+t,height:f.area[1]+i}),f.isResize=!0,r.resizing&&r.resizing(l)}}).on("mouseup",function(e){f.moveStart&&(delete f.moveStart,d.moveElem.hide(),r.moveEnd&&r.moveEnd(l)),f.resizeStart&&(delete f.resizeStart,d.moveElem.hide())}),s},t.pt.callback=function(){var t=this,i=t.layero,n=t.config;t.openLayer(),n.success&&(2==n.type?i.find("iframe").on("load",function(){n.success(i,t.index)}):n.success(i,t.index)),6==g.ie&&t.IE6(i),i.find("."+f[6]).children("a").on("click",function(){var e=m(this).index();0===e?n.yes?n.yes(t.index,i):n.btn1?n.btn1(t.index,i):g.close(t.index):!1===(n["btn"+(e+1)]&&n["btn"+(e+1)](t.index,i))||g.close(t.index)}),i.find("."+f[7]).on("click",function(){!1===(n.cancel&&n.cancel(t.index,i))||g.close(t.index)}),n.shadeClose&&m("#layui-layer-shade"+t.index).on("click",function(){g.close(t.index)}),i.find(".layui-layer-min").on("click",function(){!1===(n.min&&n.min(i))||g.min(t.index,n)}),i.find(".layui-layer-max").on("click",function(){m(this).hasClass("layui-layer-maxmin")?(g.restore(t.index),n.restore&&n.restore(i)):(g.full(t.index,n),setTimeout(function(){n.full&&n.full(i)},100))}),n.end&&(d.end[t.index]=n.end)},d.reselect=function(){m.each(m("select"),function(e,t){var i=m(this);i.parents("."+f[0])[0]||1==i.attr("layer")&&m("."+f[0]).length<1&&i.removeAttr("layer").show(),i=null})},t.pt.IE6=function(e){m("select").each(function(e,t){var i=m(this);i.parents("."+f[0])[0]||"none"===i.css("display")||i.attr({layer:"1"}).hide(),i=null})},t.pt.openLayer=function(){g.zIndex=this.config.zIndex,g.setTop=function(e){return g.zIndex=parseInt(e[0].style.zIndex),e.on("mousedown",function(){g.zIndex++,e.css("z-index",g.zIndex+1)}),g.zIndex}},d.record=function(e){var t=[e.width(),e.height(),e.position().top,e.position().left+parseFloat(e.css("margin-left"))];e.find(".layui-layer-max").addClass("layui-layer-maxmin"),e.attr({area:t})},d.rescollbar=function(e){f.html.attr("layer-full")==e&&(f.html[0].style.removeProperty?f.html[0].style.removeProperty("overflow"):f.html[0].style.removeAttribute("overflow"),f.html.removeAttr("layer-full"))},(h.layer=g).getChildFrame=function(e,t){return t=t||m("."+f[4]).attr("times"),m("#"+f[0]+t).find("iframe").contents().find(e)},g.getFrameIndex=function(e){return m("#"+e).parents("."+f[4]).attr("times")},g.iframeAuto=function(e){if(e){var t=g.getChildFrame("html",e).outerHeight(),i=m("#"+f[0]+e),n=i.find(f[1]).outerHeight()||0,a=i.find("."+f[6]).outerHeight()||0;i.css({height:t+n+a}),i.find("iframe").css({height:t})}},g.iframeSrc=function(e,t){m("#"+f[0]+e).find("iframe").attr("src",t)},g.style=function(e,t,i){var n=m("#"+f[0]+e),a=n.find(".layui-layer-content"),o=n.attr("type"),s=n.find(f[1]).outerHeight()||0,r=n.find("."+f[6]).outerHeight()||0;n.attr("minLeft"),o!==d.type[3]&&o!==d.type[4]&&(i||(parseFloat(t.width)<=260&&(t.width=260),parseFloat(t.height)-s-r<=64&&(t.height=64+s+r)),n.css(t),r=n.find("."+f[6]).outerHeight(),o===d.type[2]?n.find("iframe").css({height:parseFloat(t.height)-s-r}):a.css({height:parseFloat(t.height)-s-r-parseFloat(a.css("padding-top"))-parseFloat(a.css("padding-bottom"))}))},g.min=function(e,t){var i=m("#"+f[0]+e),n=i.find(f[1]).outerHeight()||0,a=i.attr("minLeft")||181*d.minIndex+"px",o=i.css("position");d.record(i),d.minLeft[0]&&(a=d.minLeft[0],d.minLeft.shift()),i.attr("position",o),g.style(e,{width:180,height:n,left:a,top:c.height()-n,position:"fixed",overflow:"hidden"},!0),i.find(".layui-layer-min").hide(),"page"===i.attr("type")&&i.find(f[4]).hide(),d.rescollbar(e),i.attr("minLeft")||d.minIndex++,i.attr("minLeft",a)},g.restore=function(e){var t=m("#"+f[0]+e),i=t.attr("area").split(",");t.attr("type"),g.style(e,{width:parseFloat(i[0]),height:parseFloat(i[1]),top:parseFloat(i[2]),left:parseFloat(i[3]),position:t.attr("position"),overflow:"visible"},!0),t.find(".layui-layer-max").removeClass("layui-layer-maxmin"),t.find(".layui-layer-min").show(),"page"===t.attr("type")&&t.find(f[4]).show(),d.rescollbar(e)},g.full=function(t){var e,i=m("#"+f[0]+t);d.record(i),f.html.attr("layer-full")||f.html.css("overflow","hidden").attr("layer-full",t),clearTimeout(e),e=setTimeout(function(){var e="fixed"===i.css("position");g.style(t,{top:e?0:c.scrollTop(),left:e?0:c.scrollLeft(),width:c.width(),height:c.height()},!0),i.find(".layui-layer-min").hide()},100)},g.title=function(e,t){m("#"+f[0]+(t||g.index)).find(f[1]).html(e)},g.close=function(n){var a=m("#"+f[0]+n),o=a.attr("type");if(a[0]){var s="layui-layer-wrap",e=function(){if(o===d.type[1]&&"object"===a.attr("conType")){a.children(":not(."+f[5]+")").remove();for(var e=a.find("."+s),t=0;t<2;t++)e.unwrap();e.css("display",e.data("display")).removeClass(s)}else{if(o===d.type[2])try{var i=m("#"+f[4]+n)[0];i.contentWindow.document.write(""),i.contentWindow.close(),a.find("."+f[5])[0].removeChild(i)}catch(e){}a[0].innerHTML="",a.remove()}"function"==typeof d.end[n]&&d.end[n](),delete d.end[n]};a.data("isOutAnim")&&a.addClass("layer-anim layer-anim-close"),m("#layui-layer-moves, #layui-layer-shade"+n).remove(),6==g.ie&&d.reselect(),d.rescollbar(n),a.attr("minLeft")&&(d.minIndex--,d.minLeft.push(a.attr("minLeft"))),g.ie&&g.ie<10||!a.data("isOutAnim")?e():setTimeout(function(){e()},200)}},g.closeAll=function(i){m.each(m("."+f[0]),function(){var e=m(this),t=i?e.attr("type")===i:1;t&&g.close(e.attr("times")),t=null})};function v(e){return a.skin?" "+a.skin+" "+a.skin+"-"+e:""}var a=g.cache||{};g.prompt=function(i,n){var e="";if("function"==typeof(i=i||{})&&(n=i),i.area){var t=i.area;e='style="width: '+t[0]+"; height: "+t[1]+';"',delete i.area}var a,o=2==i.formType?'<textarea class="layui-layer-input"'+e+">"+(i.value||"")+"</textarea>":'<input type="'+(1==i.formType?"password":"text")+'" class="layui-layer-input" value="'+(i.value||"")+'">',s=i.success;return delete i.success,g.open(m.extend({type:1,btn:["&#x786E;&#x5B9A;","&#x53D6;&#x6D88;"],content:o,skin:"layui-layer-prompt"+v("prompt"),maxWidth:c.width(),success:function(e){(a=e.find(".layui-layer-input")).focus(),"function"==typeof s&&s(e)},resize:!1,yes:function(e){var t=a.val();""===t?a.focus():t.length>(i.maxlength||500)?g.tips("&#x6700;&#x591A;&#x8F93;&#x5165;"+(i.maxlength||500)+"&#x4E2A;&#x5B57;&#x6570;",a,{tips:1}):n&&n(t,e,a)}},i))},g.tab=function(a){var n=(a=a||{}).tab||{},o="layui-this",i=a.success;return delete a.success,g.open(m.extend({type:1,skin:"layui-layer-tab"+v("tab"),resize:!1,title:function(){var e=n.length,t=1,i="";if(0<e)for(i='<span class="'+o+'">'+n[0].title+"</span>";t<e;t++)i+="<span>"+n[t].title+"</span>";return i}(),content:'<ul class="layui-layer-tabmain">'+function(){var e=n.length,t=1,i="";if(0<e)for(i='<li class="layui-layer-tabli '+o+'">'+(n[0].content||"no content")+"</li>";t<e;t++)i+='<li class="layui-layer-tabli">'+(n[t].content||"no  content")+"</li>";return i}()+"</ul>",success:function(e){var t=e.find(".layui-layer-title").children(),n=e.find(".layui-layer-tabmain").children();t.on("mousedown",function(e){e.stopPropagation?e.stopPropagation():e.cancelBubble=!0;var t=m(this),i=t.index();t.addClass(o).siblings().removeClass(o),n.eq(i).show().siblings().hide(),"function"==typeof a.change&&a.change(i)}),"function"==typeof i&&i(e)}},a))},g.photos=function(a,e,t){var i,n,o,s,r={};if((a=a||{}).photos){var l=a.photos.constructor===Object,f=l?a.photos:{},c=f.data||[],d=f.start||0;r.imgIndex=1+(0|d),a.img=a.img||"img";var u=a.success;if(delete a.success,l){if(0===c.length)return g.msg("&#x6CA1;&#x6709;&#x56FE;&#x7247;")}else{var y=m(a.photos),p=function(){c=[],y.find(a.img).each(function(e){var t=m(this);t.attr("layer-index",e),c.push({alt:t.attr("alt"),pid:t.attr("layer-pid"),src:t.attr("layer-src")||t.attr("src"),thumb:t.attr("src")})})};if(p(),0===c.length)return;if(e||y.on("click",a.img,function(){var e=m(this).attr("layer-index");g.photos(m.extend(a,{photos:{start:e,data:c,tab:a.tab},full:a.full}),!0),p()}),!e)return}r.imgprev=function(e){r.imgIndex--,r.imgIndex<1&&(r.imgIndex=c.length),r.tabimg(e)},r.imgnext=function(e,t){r.imgIndex++,r.imgIndex>c.length&&(r.imgIndex=1,t)||r.tabimg(e)},r.keyup=function(e){if(!r.end){var t=e.keyCode;e.preventDefault(),37===t?r.imgprev(!0):39===t?r.imgnext(!0):27===t&&g.close(r.index)}},r.tabimg=function(e){if(!(c.length<=1))return f.start=r.imgIndex-1,g.close(r.index),g.photos(a,!0,e)},r.event=function(){r.bigimg.hover(function(){r.imgsee.show()},function(){r.imgsee.hide()}),r.bigimg.find(".layui-layer-imgprev").on("click",function(e){e.preventDefault(),r.imgprev()}),r.bigimg.find(".layui-layer-imgnext").on("click",function(e){e.preventDefault(),r.imgnext()}),m(document).on("keyup",r.keyup)},r.loadi=g.load(1,{shade:!("shade"in a)&&.9,scrollbar:!1}),i=c[d].src,n=function(n){g.close(r.loadi),r.index=g.open(m.extend({type:1,id:"layui-layer-photos",area:function(){var e=[n.width,n.height],t=[m(h).width()-100,m(h).height()-100];if(!a.full&&(e[0]>t[0]||e[1]>t[1])){var i=[e[0]/t[0],e[1]/t[1]];i[1]<i[0]?(e[0]=e[0]/i[0],e[1]=e[1]/i[0]):i[0]<i[1]&&(e[0]=e[0]/i[1],e[1]=e[1]/i[1])}return[e[0]+"px",e[1]+"px"]}(),title:!1,shade:.9,shadeClose:!0,closeBtn:!1,move:".layui-layer-phimg img",moveType:1,scrollbar:!1,moveOut:!0,isOutAnim:!1,skin:"layui-layer-photos"+v("photos"),content:'<div class="layui-layer-phimg"><img src="'+c[d].src+'" alt="'+(c[d].alt||"")+'" layer-pid="'+c[d].pid+'"><div class="layui-layer-imgsee">'+(1<c.length?'<span class="layui-layer-imguide"><a href="javascript:;" class="layui-layer-iconext layui-layer-imgprev"></a><a href="javascript:;" class="layui-layer-iconext layui-layer-imgnext"></a></span>':"")+'<div class="layui-layer-imgbar" style="display:'+(t?"block":"")+'"><span class="layui-layer-imgtit"><a href="javascript:;">'+(c[d].alt||"")+"</a><em>"+r.imgIndex+"/"+c.length+"</em></span></div></div></div>",success:function(e,t){r.bigimg=e.find(".layui-layer-phimg"),r.imgsee=e.find(".layui-layer-imguide,.layui-layer-imgbar"),r.event(e),a.tab&&a.tab(c[d],e),"function"==typeof u&&u(e)},end:function(){r.end=!0,m(document).off("keyup",r.keyup)}},a))},o=function(){g.close(r.loadi),g.msg("&#x5F53;&#x524D;&#x56FE;&#x7247;&#x5730;&#x5740;&#x5F02;&#x5E38;<br>&#x662F;&#x5426;&#x7EE7;&#x7EED;&#x67E5;&#x770B;&#x4E0B;&#x4E00;&#x5F20;&#xFF1F;",{time:3e4,btn:["&#x4E0B;&#x4E00;&#x5F20;","&#x4E0D;&#x770B;&#x4E86;"],yes:function(){1<c.length&&r.imgnext(!0,!0)}})},(s=new Image).src=i,s.complete?n(s):(s.onload=function(){s.onload=null,n(s)},s.onerror=function(e){s.onerror=null,o(e)})}},d.run=function(e){c=(m=e)(h),f.html=m("html"),g.open=function(e){return new t(e).index}},h.layui&&layui.define?(g.ready(),layui.define("jquery",function(e){g.path=layui.cache.dir,d.run(layui.$),e("layer",h.layer=g)})):"function"==typeof define&&define.amd?define(["jquery"],function(){return d.run(h.jQuery),g}):(d.run(h.jQuery),g.ready())}(window);