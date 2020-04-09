jQuery(function($){
    var ratehtml=PLUGNAME=='choujiang'?'<div class="space-4"></div>\
    <div class="row">\
        <div class="form-group">\
            <label class="col-xs-4" style="text-align: right;">中奖概率(%)：</label>\
            <div>\
                <input type="number"  name="rate"  class="col-xs-5" value="100" />\
            </div>\
        </div>\
    </div>':'';
    
    var default_itemtemplate='<div class="space-4"></div>\
    <div class="row">\
        <div class="form-group">\
            <label class="col-xs-4" style="text-align: right;">奖品名称：</label>\
            <div>\
                <input type="text"  name="prizename"  class="col-xs-5" value="" />\
            </div>\
        </div>\
    </div>\
    <div class="space-4"></div>\
    <div class="row">\
        <div class="form-group">\
            <label class="col-xs-4" style="text-align: right;">剩余数量：</label>\
            <div>\
                <input type="number"  name="num"  class="col-xs-5" value="1" />\
            </div>\
        </div>\
    </div>'
    +ratehtml+
    '<div class="space-4"></div>\
    <div class="row">\
        <div class="form-group">\
            <label class="col-xs-4" style="text-align: right;">奖品类型：</label>\
            <div>\
                <select class="chosen-select col-xs-5" name="type" data-placeholder="">\
                    <option value="1">普通奖品</option>\
                    <option value="3">微信红包</option>\
                </select>\
            </div>\
        </div>\
    </div>\
    <div id="prizetype"></div>';
    var img_itemtemplate='<div class="space-4"></div><div class="row"><div class="form-group"><label class="col-xs-4" style="text-align: right;">奖品图片：</label><div class="col-md-8" style="padding-left:0px;"><input type="file" class="imageuploader" name="imagepath"/><input type="hidden"  name="imageid" value="0"/></div></div>';
    var amount_itemtemplate='<div class="space-4"></div>\
    <div class="row">\
    <div class="form-group">\
        <label class="col-xs-4" style="text-align: right;">金额(元)：</label>\
        <div>\
            <input type="number"  name="amount"  class="col-xs-5" value="1"  min="1" max="200"/>\
        </div>\
    </div>\
</div>';
    $('.btn_add').on('click',function(){
        openform(0);
    });

    $('.btn_edit').on('click',function(){
        openform($(this).attr('data'));
    });

    $('.btn_save').on('click',function(){
        ajax_act_save_prize();
        return false;
    });

    
    //删除游戏
    $('.btn_delete').on('click',function(){
        if(!confirm('确认要删除这个奖品吗？'))return false;
        var id=$(this).attr('data');
        if(id<=0){
            alert('数据错误');
            return false;
        }
        var ajax_act_delete_prize=function(id){
            return $.ajax({
                "url":"module.php?m=prize&c=admin&a=ajax_act_delete_prize",
                "data":{"id":id},
                "type":"get",
                "dataType":"json",
            });
        }
        ajax_act_delete_prize(id).done(function(json){
            alert(json.message);
            if(json.code>0){
                window.location.reload();
            }
            return ;
        }).fail(function(){
            alert('请检查您的网络，有可能是断网了哦。');
        });
    });
    function openform(id){
        var defautdata={
            "id":0,
            "prizename":'',
            "activityid":ACTIVITYID,
            'rate':100,
            "type":1,
            "imageid":0,
            "num":1,
        };
        if(id<=0){
            fillform(defautdata);
        }else{
            ajax_get_prize(id).done(function(json){
                if(json.code>0){
                    fillform(json.data);
                }else{
                    alert(json.message);
                    fillform(defautdata);
                }
            }).fail(function(){
                alert('请检查您的网络，有可能是断网了哦。');
            });
        }
        $('#editformmodal').modal('show');
    }
    //填充配置信息添加修改表单
    function fillform(data){
        var formEl=$('#editform');
        //清空原来表单中的内容
        $('.columnitems').html('');
        //添加默认的模板内容并设置默认值
        $(default_itemtemplate).appendTo('.columnitems');
        $('input[name=id]').val(data.id);
        $('input[name=activityid]').val(data.activityid);
        $('input[name=prizename]').val(data.prizename);
        $('input[name=rate]').val(data.rate);
        $('select[name=type]').val(data.type);
        $('input[name=num]').val(data.leftnum);
        var typeselect=formEl.find('select[name=type]');
        typeselect.off('change');
        typeselect.on('change',function(){
            data.type=$(this).val();
            genformitem(data);
        });
        //针对不同类型的奖品添加不同类型的字段
        genformitem(data);
    }

    function genformitem(data){
        $('#prizetype').html('');
        //普通奖品
        if(data.type==1){
            genprizenormalitem(data);
        }
        if(data.type==3){
            genredpacketitem(data);
        }
    }

    function genredpacketitem(data){
        $(amount_itemtemplate).appendTo('#prizetype');
        $(img_itemtemplate).appendTo('#prizetype');
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
        }).on('file.error.ace',function(e,errors){
            
        }); 
        if(data.id>0){
            if(data.prizedata_arr.imageid>0){
                $('input[name=imageid]').val(data.prizedata_arr.imageid);
                $('input[name=imagepath]')
                    .ace_file_input('show_file_list', [
                        {type: 'image', name: '奖品图', path:data.formatedtext.text },
                    ]);
            }
        }
        var amount=(data.prizedata_arr.amount/100).toFixed(2);
        $('input[name=amount]').val(amount);
    }

    function genprizenormalitem(data){
        $(img_itemtemplate).appendTo('#prizetype');
        // $(img_itemtemplate).appendTo('.columnitems');
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
        }).on('file.error.ace',function(e,errors){
            
        }); 
       
        if(data.id>0){
            if(data.prizedata_arr.imageid>0){
                $('input[name=imageid]').val(data.prizedata_arr.imageid);
                $('input[name=imagepath]')
                    .ace_file_input('show_file_list', [
                        {type: 'image', name: '奖品图', path:data.formatedtext.text },
                    ]);
            }
        }
    }
    //返回指定id的配置信息的deferred对象
    function ajax_get_prize(id){
        return $.ajax({
            "url":"module.php?m=prize&c=admin&a=ajax_act_get_prize",
            "type":"post",
            "data":{"id":id},
            "dataType":"json",
        });
    }
    //保存轮次配置信息
    function ajax_act_save_prize(){
        $('#editform').ajaxSubmit({
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
});

