var Meepo_utility={};function reloadUrl(t,n){n=(n||"t")+"=";var e=new RegExp(n+"\\d+"),i=+new Date;if(-1<t.indexOf(n))return t.replace(e,n+i);if(-1<t.indexOf("?")){var r=t.split("?");return r[1]?r[0]+"?"+n+i+"&"+r[1]:r[0]+"?"+n+i}return-1<t.indexOf("#")?t.split("#")[0]+"?"+n+i+location.hash:t+"?"+n+i}function showtipsbox(t,n){(void 0===n||""==n||n<=0||0==n)&&(n=1),$(document).minTipsBox({tipsContent:t,tipsTime:n})}Meepo_utility.array_search=function(t,n,e){for(var i in t)if(t[i][e]==n)return t[i];return null},Meepo_utility.array_remove=function(t,n,e){for(var i=0;i<t.length;i++)if(n==t[i][e])return t.splice(i,1)[0];return null},Meepo_utility.array_sort=function(t,i){t.sort(function(t,n){var e=t[i];return n[i]-e})},Meepo_utility.get_array_by_value=function(t,n,e){for(var i=[],r=0;r<t.length;r++)t[r].hasOwnProperty(e)&&n==t[r][e]&&i.push(t[r]);return i},Meepo_utility.get_random_array=function(t,n){var e=t.length;if(e<=n)return t;for(var i=[],r=0;r<e;r++)i[r]=r;i.sort(function(){return.5-Math.random()});var o=[];for(r=0;r<n;r++)o.push(t[i[r]]);return o},Meepo_utility.delegate=function(t,n){return function(){return n.apply(t,arguments)}},Meepo_utility.getRandom=function(t,n){var e=n-t,i=Math.random()*e+t;return parseInt(i,10)},Meepo_utility.startCountdown=function(t,n,e,i){var r=setInterval(function(){(t-=n)<=0?(clearInterval(r),i&&i(0)):e&&e(t)},1e3*n)},Meepo_utility.getUrlParams=function(){var t={},n=window.location.href,e=n.indexOf("?");if(0<e)for(var i,r,o=n.substring(e+1).split("&"),a=0;i=o[a];a++)t[(r=o[a]=i.split("="))[0]]=!(1<r.length)||r[1];return t},Meepo_utility.doRequest=function(t,n,e){$.ajax({url:t+"/?r="+Math.random(),data:n,type:"POST",success:function(t){e&&e(t)},error:function(){e&&e({code:500,msg:"网络发生异常，请检查网络！"})}})},function(o){o.fn.extend({minTipsBox:function(n){n=o.extend({tipsContent:"",tipsTime:1},n);var t=".min_tips_box",e=".min_tips_box .tips_content",i=1e3*parseFloat(n.tipsTime);function r(){o(e).html(n.tipsContent);var t=o(e).width()/2+10;o(e).css("margin-left","-"+t+"px")}0<o(t).length?o(t).show():o('<div class="min_tips_box"><b class="bg"></b><span class="tips_content"></span></div>').appendTo("body"),r(),setTimeout(function(){o(t).hide()},i)}})}(jQuery),function(e){e.fn.extend({animateControl:function(t,n){return this.addClass("animated "+t).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){n&&n(),e(this).removeClass("animated "+t)}),this}})}(jQuery);