jQuery(function(o){o(".btn_cancel").on("click",function(){if(!confirm("确认要取消这个内定吗？"))return!1;var e=o(this).attr("data");if(e<=0)return alert("数据错误"),!1;var a;(a=e,o.ajax({url:"module.php?m=prize&c=admin&a=ajax_act_cancel_designated",data:{id:a},type:"get",dataType:"json"})).done(function(e){alert(e.message),0<e.code&&window.location.reload()}).fail(function(){alert("请检查您的网络，有可能是断网了哦。")})}),o("#btn_designated").on("click",function(){o(".search-query").val(""),o("select[name=userid]").html('<option value="0">无数据</option>'),o("select[name=prizeid]").val(0),o("select[name=designated]").val(2),o("#formmodal").modal("show")}),o(".btn_search").on("click",function(){var e,a=o(".search-query").val();(e=a,o.ajax({url:"module.php?m=prize&c=admin&a=ajax_act_get_users",data:{searchtext:e},type:"get",dataType:"json"})).done(function(e){if(0<e.code){var a="",t=e.data.length;if(0<t)for(var n=0;n<t;n++)a+='<option value="'+e.data[n].id+'">昵称:'+e.data[n].nickname+" 姓名："+e.data[n].signname+" 电话："+e.data[n].phone+"</option>",console.log(e.data[n]);else a='<option value="0">无数据</option>';o("select[name=userid]").html(a)}""!=e.message?o("#userid-help-block").html('<i class="ace-icon fa fa-exclamation-triangle red"></i>'+e.message):o("#userid-help-block").html("")}).fail(function(){alert("请检查您的网络，有可能是断网了哦。")})}),o(".btn_save").on("click",function(){var e=o("select[name=userid]").val(),a=o("select[name=prizeid]").val(),t=o("select[name=designated]").val(),n=o("input[name=activityid]").val(),i=o("input[name=plugname]").val();return 0==e?(alert("必须选择一个人进行内定"),!1):0==a?(alert("必须选择一个奖品进行内定"),!1):void l(e,a,t,i,n).done(function(e){alert(e.message),0<e.code&&window.location.reload()}).fail(function(){return alert("请检查您的网络，有可能是断网了哦。"),!1})});var l=function(e,a,t,n,i){return o.ajax({url:"module.php?m=prize&c=admin&a=ajax_act_save_designated",data:{userid:e,prizeid:a,designated:t,plugname:n,activityid:i,title:c("title")},type:"get",dataType:"json"})};var c=function(e){var a=document.location.search,t=new RegExp("[?&]"+e+"=([^&]+)","g").exec(a),n=null;if(null!=t)try{n=decodeURIComponent(decodeURIComponent(t[1]))}catch(e){try{n=decodeURIComponent(t[1])}catch(e){n=t[1]}}return n}});