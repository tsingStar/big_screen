var admin_index=function(){var n=$(".btn-gameconfig"),a=$(".btn-delgameconfig"),i=$("#btn-showwinners"),e=$("#btn-openmodaladdgameconfig"),t=$("#gameconfig"),o=t.find(".btn-savegameconfig"),d=$("#btn-themeconfig"),c=$(".btn-reset"),r=$("#addgameconfigmodal"),m=r.find(".btn-addgameconfig");function u(){n.on("click",function(){var n=$(this).attr("data-id");window.location.href="/Modules/module.php?m=game&c=admin&a=index&id="+n}),c.on("click",function(){(function(n){return $.ajax({url:"/Modules/module.php?m=game&c=admin&a=ajaxResetGame",data:{configid:n},type:"post",dataType:"json"})})($(this).attr("data-id")).done(function(n){alert(n.message),0<n.code&&window.location.reload()})}),i.on("click",function(){if($(this).parent().hasClass("active"))return!1;var a=$(this).attr("data-id"),i=$(this).attr("href");$(i).html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>'),l(a).done(function(n){$(i).html(n),admin_snippets_winners(a).init()})}),e.on("click",function(){r.modal("show")}),o.on("click",function(){var n={};n.durationtype=t.find("input[name=durationtype]:checked").val(),n.duration=t.find("input[name=duration]").val(),n.toprank=t.find("input[name=toprank]").val(),n.themeid=t.find("select[name=themeid]").val(),n.winagain=t.find("input[name=winagain]:checked").val()?2:1,n.showtype=t.find("input[name=showtype]:checked").val(),n.id=t.find("input[name=id]").val(),s(n).done(function(n){alert(n.message),0<n.code&&window.location.reload()})}),m.on("click",function(){var n={};n.durationtype=r.find("input[name=durationtype]:checked").val(),n.duration=r.find("input[name=duration]").val(),n.toprank=r.find("input[name=toprank]").val(),n.themeid=r.find("select[name=themeid]").val(),n.winagain=r.find("input[name=winagain]:checked").val()?2:1,n.showtype=r.find("input[name=showtype]:checked").val(),n.id=0,s(n).done(function(n){alert(n.message),0<n.code&&window.location.reload()})}),a.on("click",function(){var n=$(this).attr("data-id");if(!confirm("确定要删除这一轮游戏吗？"))return!1;(function(n){return $.ajax({url:"/Modules/module.php?m=game&c=admin&a=ajaxDelGameConfig",data:{id:n},type:"post",dataType:"json"})})(n).done(function(n){window.location.href="/Modules/module.php?m=game&c=admin&a=index"})}),d&&d.on("click",function(){if($(this).parent().hasClass("active"))return!1;var a=$(this).attr("data-id"),i=$(this).attr("href");$(i).html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>'),f(a).done(function(n){$(i).html(n),admin_snippets_themeconfig(a).init()})})}function s(n){return n.id||(n.id=0),$.ajax({url:"/Modules/module.php?m=game&c=admin&a=ajaxSaveGameConfig",data:n,type:"post",dataType:"json"})}function f(n){return $.ajax({url:"/Modules/module.php?m=game&c=admin&a=ajaxGetThemeConfig",data:{id:n},type:"post",dataType:"text"})}function l(n){return $.ajax({url:"/Modules/module.php?m=game&c=admin&a=ajaxGetWinners",type:"post",data:{id:n},dataType:"text"})}return{init:function(){u()},reloadTab:function(a,i){switch($(a).html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>'),a){case"#gametheme":f(i).done(function(n){$(a).html(n),admin_snippets_themeconfig(i).init()});break;case"#winners":l(i).done(function(n){$(a).html(n),admin_snippets_winners(i).init()})}}}}(),admin_snippets_winners=function(a){var n=$("#widget-box-winners").find(".btn-delete");function i(){n.on("click",function(){var n=$(this).attr("data-id");if(!confirm("确认要删除这条中奖记录吗?"))return!1;(function(n){return $.ajax({url:"module.php?m=game&c=admin&a=ajaxDeleteWinner",data:{id:n},type:"get",dataType:"json"})})(n).done(function(n){alert(n.message),0<n.code&&admin_index.reloadTab("#winners",a)}).fail(function(){alert("请检查您的网络，有可能是断网了哦。")})})}return{init:function(){i()}}};$(document).ready(function(){admin_index.init()});