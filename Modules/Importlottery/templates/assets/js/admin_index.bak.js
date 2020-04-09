$(document).ready(function(){
    $('.btn_import').on('click',function(){
        $('#importmodal').modal('show');
    });
    $('.btn-saveexcelform').on('click',function(){
        ajax_act_save_excel();
    });
    $('.btn_add').on('click',function(){
        resetform();
        $('#datamodal').modal('show');
    });
    $('.btn_edit').on('click',function(){
        var id=$(this).attr('data');
        ajax_act_get_dataitem(id).done(function(json){
            if(json.code>0){
                fillform(json.data);
                $('#datamodal').modal('show');
            }else{
                alert(json.message);
            }
        }).fail(function(){
            alert('未知错误');
        });
    });
    $('.btn-save-item').on('click',function(){
        ajax_act_save_dataitem();
    });
    $('.btn_del').on('click',function(){
        if(!confirm('确认要删除这个导入的数据吗?')) return false;
        var id=$(this).attr('data');
        ajax_act_delete_dataitem(id).done(function(json){
            alert(json.message);
            if(json.code>0){
                window.location.reload();
            }
        }).fail(function(){
            alert('请检查您的网络是否正常');
        });
    });
    $('.imageuploader').ace_file_input({
        style: 'well',
        btn_choose: '点击此处选择图片',
        btn_change: null,
        no_icon: 'ace-icon fa fa-cloud-upload',
        droppable: true,
        maxSize: 550000,
        allowExt: ["jpeg", "jpg", "png", "gif"],
        allowMime: ["image/jpg", "image/jpeg", "image/png", "image/gif"],
        thumbnail: 'large',//large | fit
        previewHeight:200,
        preview_error : function(filename, error_code) {
            console.log(error_code);
        },
        before_remove:function(){
            $('input[name=imageid]').val(0);
            return true;
        }
    }).on('change', function(){
        $('input[name=imageid]').val(0);
    }).on('file.error.ace',function(e,errors){}); 

    $('.btn-search').on('click',function(){
        var txt=$('#searchtxt').val();
        if(txt!=''){
            txt='&txt='+txt;//+encodeURIComponent(txt);
        }
        window.location.href='/Modules/module.php?m=importlottery&c=admin&a=index'+txt;
    });
});
function resetform(){
    $('input[name=col1]').val('');
    $('input[name=col2]').val('');
    $('input[name=col3]').val('');
    $('input[name=id]').val(0);
    $('input[name=imageid]').val('');
}
function fillform(data){
    resetform();
    $('input[name=col1]').val(data.col1);
    $('input[name=col2]').val(data.col2);
    $('input[name=col3]').val(data.col3);
    $('input[name=id]').val(data.id);
    // if(data.id>0){
    if(data.imageid>0){
        $('input[name=imageid]').val(data.imageid);
        $('input[name=imagepath]')
            .ace_file_input('show_file_list', [
                {type: 'image', name: '奖品图', path:data.imagepath },
            ]);
    }
    // }
}
function ajax_act_get_dataitem(id){
    return $.ajax({
        'url':"/Modules/module.php?m=importlottery&c=admin&a=ajax_act_get_dataitem&id="+id,
        "type":"get",
        'dataType':"json"
    });
}

function ajax_act_save_dataitem(){
    $('#savedataform').ajaxSubmit({
        dataType: 'json',
        success:function(json){
            if (json.code>0) {
                window.location.reload();
            } else {
                alert(json.message);
            }
        }
    });
}
//保存轮次配置信息
function ajax_act_save_excel(){
    $('#saveexcelform').ajaxSubmit({
        dataType: 'json',
        success:function(json){
            alert(json.message);
            if (json.code>0) {
                window.location.reload();
            } 
        }
    });
}
//删除一条导入的信息
function ajax_act_delete_dataitem(id){
    return $.ajax({
        'url':'/Modules/module.php?m=importlottery&c=admin&a=ajax_act_delete_dataitem&id='+id,
        'type':'get',
        'dataType':'json'
    })
}