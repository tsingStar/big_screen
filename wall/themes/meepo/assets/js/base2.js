var PATH_ACTIVITY="";Array.prototype.indexOf||(Array.prototype.indexOf=function(e){var n=this.length,t=Number(arguments[1])||0;for((t=t<0?Math.ceil(t):Math.floor(t))<0&&(t+=n);t<n;t++)if(t in this&&this[t]===e)return t;return-1}),function(l,u){u.fn.scroll_subtitle=function(){return this.each(function(){var t=u(this);1<t.children().length&&l.setInterval(function(){var e=t.children(),n=u(e[0]);n.slideUp(2e3,function(){n.remove().appendTo(t).show()})},5e3)})},u.preloadImages=function(e,n){if(u.isArray(e)){var t=e.length;if(0<t){function i(){t<=++r&&"function"==typeof n&&l.setTimeout(n,100)}for(var r=0,o=0;o<t;o++){var c=new Image;c.onload=i,c.onerror=i,c.src=e[o]}}}},u.getUrlParam=function(e){var n=new RegExp("(^|&)"+e+"=([^&]*)(&|$)"),t=l.location.search.substr(1).match(n);return null!=t?unescape(t[2]):null},u.fn.toFillText=function(){return this.each(function(){var e=u(this),n=e.html(),t=e.height();e.html("");var i=u("<div>"+n+"</div>").appendTo(e);i.css("font-size","12px");for(var r=12;r<200;r++){if(i.height()>t){e.css("font-size",r-2+"px").html(n);break}i.css("font-size",r+"px")}})},u.fillText=function(e){var n=e.html(),t=e.height();e.html("");var i=u("<div>"+n+"</div>").appendTo(e);i.css("font-size","12px");for(var r=12;r<200;r++){if(i.height()>t){e.css("font-size",r-2+"px").html(n);break}i.css("font-size",r+"px")}},u.showPage=function(e){var n=u('<div class="frame-dialog"><iframe frameborder="0" src="'+e+'"></iframe><div class="closebutton"></div></div>');n.appendTo("body").show().on("click",".closebutton",function(){n.hide(function(){n.remove(),n=null})})},l.WBActivity={showLoginForm:function(){u(".loginform").fadeIn()},hideLoginForm:function(){u(".loginform").fadeOut()},showLoading:function(){u(".loader").fadeIn()},hideLoading:function(){u(".loader").fadeOut()}},u(function(){if(window.top!=window.self){u(".top_title").scroll_subtitle(),u(".button-login").on("click",function(){l.WBActivity.showLoading(),u.getJSON(PATH_ACTIVITY+Path_url("login.do.php"),{rid:scene_id,password:u("#password").val()},function(e){0==e.errno?(l.sessionStorage.setItem("loginkey",1),l.WBActivity.hideLoginForm(),l.WBActivity.start()):alert("密码错误")}).complete(function(){l.WBActivity.hideLoading()})}),u(".mp_account_codeimage").on("click",function(){$(this).hasClass("bigqrcode")?(u(".bigmpcodebar").slideDown(),$(this).removeClass("bigqrcode")):$(this).addClass("bigqrcode")}),u(".bigmpcodebar .closebutton").on("click",function(){u(".bigmpcodebar").slideUp()}),u(".navbaritem.fullscreen").on("click",function(){u.toggleFullScreen()});l.sessionStorage.getItem("loginkey")?l.WBActivity.start():(l.WBActivity.hideLoading(),l.WBActivity.showLoginForm())}else window.location.href="/frame.php"}),u(l).on("resize",function(){l.WBActivity.resize()})}(window,jQuery),function(e,n){var t={supportsFullScreen:!1,isFullScreen:function(){return!1},requestFullScreen:function(){},cancelFullScreen:function(){},fullScreenEventName:"",prefix:""},i="webkit moz o ms khtml".split(" ");if(void 0!==document.cancelFullScreen)t.supportsFullScreen=!0;else for(var r=0,o=i.length;r<o;r++)if(t.prefix=i[r],void 0!==document[t.prefix+"CancelFullScreen"]){t.supportsFullScreen=!0;break}t.supportsFullScreen&&(t.fullScreenEventName=t.prefix+"fullscreenchange",t.isFullScreen=function(){switch(this.prefix){case"":return document.fullScreen;case"webkit":return document.webkitIsFullScreen;default:return document[this.prefix+"FullScreen"]}},t.requestFullScreen=function(e){return""===this.prefix?e.requestFullScreen():e[this.prefix+"RequestFullScreen"]()},t.cancelFullScreen=function(e){return""===this.prefix?document.cancelFullScreen():document[this.prefix+"CancelFullScreen"]()}),"undefined"!=typeof jQuery&&(jQuery.fn.requestFullScreen=function(){return this.each(function(){var e=jQuery(this);t.supportsFullScreen&&t.requestFullScreen(e)})}),e.fullScreenApi=t,n.toggleFullScreen=function(){t.isFullScreen()?t.cancelFullScreen(document.documentElement):t.requestFullScreen(document.documentElement)}}(window,jQuery);var scene_id=$.getUrlParam("rid");