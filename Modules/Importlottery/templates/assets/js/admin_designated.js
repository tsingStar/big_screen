$(document).ready(function(){$("#btn_designated").on("click",function(){$("#formmodal").modal("show")}),$(".btn_search").on("click",function(){ajax_act_get_data().done(function(a){if(0<a.code){var e="",t=a.data.length;if(0<t)for(var n=0;n<t;n++)e+='<option value="'+a.data[n].id+'">第一列:'+a.data[n].col1+" 第二列："+a.data[n].col2+" 第三列："+a.data[n].col3+"</option>";else e='<option value="0">无数据</option>';$("select[name=userid]").html(e)}}).fail(function(){alert("请检查您的网络，有可能是断网了哦。")})}),$(".btn_save").on("click",function(){var a=$("select[name=userid]").val(),e=$("select[name=prizeid]").val(),t=$("select[name=designated]").val(),n=$("input[name=activityid]").val(),c=$("input[name=plugname]").val();return 0==a?(alert("必须选择一个人进行内定"),!1):0==e?(alert("必须选择一个奖品进行内定"),!1):void ajax_act_save_designated(a,e,t,c,n).done(function(a){alert(a.message),0<a.code&&window.location.reload()}).fail(function(){return alert("请检查您的网络，有可能是断网了哦。"),!1})}),$(".btn_cancel").on("click",function(){if(!confirm("确认要取消这个内定吗？"))return!1;var a=$(this).attr("data");if(a<=0)return alert("数据错误"),!1;ajax_act_cancel_designated(a).done(function(a){alert(a.message),0<a.code&&window.location.reload()}).fail(function(){alert("请检查您的网络，有可能是断网了哦。")})})});var ajax_act_save_designated=function(a,e,t,n,c){return $.ajax({url:"module.php?m=prize&c=admin&a=ajax_act_save_designated",data:{userid:a,prizeid:e,designated:t,plugname:n,activityid:c,title:"导入数据抽奖"},type:"get",dataType:"json"})};function ajax_act_get_data(){var a=$(".search-query").val();return $.ajax({url:"module.php?m=importlottery&c=admin&a=ajax_act_get_data",data:{txt:a},type:"get",dataType:"json"})}var getParam=function(a){var e=document.location.search,t=new RegExp("[?&]"+a+"=([^&]+)","g").exec(e),n=null;if(null!=t)try{n=decodeURIComponent(decodeURIComponent(t[1]))}catch(a){try{n=decodeURIComponent(t[1])}catch(a){n=t[1]}}return n},ajax_act_cancel_designated=function(a){return $.ajax({url:"module.php?m=prize&c=admin&a=ajax_act_cancel_designated",data:{id:a},type:"get",dataType:"json"})};