function ajax_act_delete_menu(a){return $.ajax({url:"/Modules/module.php?m=menu&c=admin&a=ajax_act_delete_menu",data:{id:a},type:"get",dataType:"json"})}function ajax_act_get_menu(a){return $.ajax({url:"/Modules/module.php?m=menu&c=admin&a=ajax_act_get_menu",data:{id:a},type:"get",dataType:"json"})}function setformdefaultval(a){var e=$.extend({},{ordernum:"1",title:"",link:"",id:0,icon:0,iconpath:"/Modules/Menu/templates/assets/images/aui-icon-question.png"},a);$("input[name=ordernum]").val(e.ordernum),$("input[name=title]").val(e.title),$("input[name=link]").val(e.link),$("input[name=id]").val(e.id),$("input[name=icon]").val(e.icon),$("input[name=iconpath]").ace_file_input("show_file_list",[{type:"image",name:e.iconpath}])}function ajax_submit_form(){var a=$("#savemenuform");return $(a).ajaxSubmit({dataType:"json",success:function(a){a.code<=0&&alert(a.message),0<a.code&&window.location.reload()}}),!1}$(document).ready(function(){$(".imageuploader").ace_file_input({style:"well",btn_choose:"点击此处选择图片",btn_change:null,no_icon:"ace-icon fa fa-cloud-upload",droppable:!0,maxSize:55e4,allowExt:["jpeg","jpg","png","gif"],allowMime:["image/jpg","image/jpeg","image/png","image/gif"],thumbnail:"large",previewHeight:200,preview_error:function(a,e){},before_remove:function(){$("input[name=icon]").val(0),$("input[name=iconpath]").ace_file_input("reset_input")}}).on("change",function(){$("input[name=icon]").val(0)}),$(".btn_add").on("click",function(){setformdefaultval(null),$("#configmodal").modal("show")}),$(".btn_edit").on("click",function(){ajax_act_get_menu($(this).attr("data")).done(function(a){a.code<=0?alert(a.message):(setformdefaultval({ordernum:a.data.ordernum,title:a.data.title,link:a.data.link,id:a.data.id,icon:a.data.icon,iconpath:a.data.iconpath,icon:a.data.icon}),$("#configmodal").modal("show"))}).fail(function(){alert("获取数据失败了")})}),$(".btn_delete").on("click",function(){if(!confirm("确定要删除这个菜单吗?"))return!1;ajax_act_delete_menu($(this).attr("data")).done(function(a){alert(a.message),0<a.code&&window.location.reload()}).fail(function(){alert("获取数据失败了")})}),$(".btn_save").on("click",function(){ajax_submit_form()})});