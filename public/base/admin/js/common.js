jQuery.fn.addOption = function(text,value){jQuery(this).get(0).options.add(new Option(text,value));}

function ajaxAlert(data,time,lock){
    time = time||1;
    lock = lock||false;

    if(!data.ret && data.msg){
        window.top.art.dialog.alert(data.msg);
        return;
    }

    if(data.ret && data.msg){
        window.top.art.dialog.tips(data.msg,time,lock);
    }
    setTimeout(function(){
        if(data.redirect && data.redirect == 'js_reload'){
            window.top.location.reload();
        }else if(data.redirect){
            window.top.location.href=data.redirect;
        }
        if(data.winid){
            window.top.art.dialog({id:data.winid}).close();
        }
    },time*1000);
}

function setForm(){
    $('form.ajaxform input[type="submit"]').click(function(){
        if(typeof(CKEDITOR) !== 'undefined'){
            for ( instance in CKEDITOR.instances ) 
                CKEDITOR.instances[instance].updateElement();
        }
    });
    
    $('form.ajaxform').ajaxForm({
        data: { ajaxform: '1' },
        dataType: 'json',
        success: function(data){
            ajaxAlert(data,1,true);
        }
    });
}
function tab(h,con){
    var _tab=$(h);
    var _box=$(con);
    var _hover='active';
    var _index;
    _tab.click(function(){
        _index=_tab.index(this);
        $(this).addClass(_hover).siblings().removeClass(_hover);
        _box.eq(_index).show().siblings().hide();
    }).eq(0).click();
}
function setMask(id,state){
    var oldEl = $('#'+id);
    if(oldEl.length == 0){
        return;
    }
    var val=oldEl.val();
    var cla=oldEl.attr('class');
    var name=oldEl.attr('name');
    var sibling = oldEl.next();
    var newInput = document.createElement('input');
    
    $(newInput).val(val);
    $(newInput).attr('id',id);
    $(newInput).attr('class',cla);
    $(newInput).attr('name',name);
    if (state == true)
        $(newInput).attr('type','text');
    else
        $(newInput).attr('type','password');
    
    oldEl.remove();
    sibling.before($(newInput));
}
function remove_line(o){
    var tr=$(o).parent().parent();
    tr.remove();
}

function multi_opt(url,msg){
    var seled = $(".id_sel:checked");

    if(seled.length == 0){
      art.dialog.alert('请先选择项目！');
      return;
    }

    art.dialog.confirm(msg, function () {
      var idarr = seled.serialize();
      if(url.indexOf('?')==-1){
        url = url+'?isajax=1';
      }else{
        url = url+'&isajax=1';
      }
      $.post(url,idarr,function(data){
        ajaxAlert(data,0.5,false);
      },'json');
    });
}

function multi_show(url,title,width,height){
    var seled = $(".id_sel:checked");

    if(seled.length == 0){
      art.dialog.alert('请先选择项目！');
      return;
    }

    var ids = '';
    seled.each(function(){
        ids += $(this).val()+',';
    });
    ids = ids.substring(0,ids.length-1);
    if(url.indexOf('?')!=-1){
        url = url+'&ids='+ids;
    }else{
        url = url+'?ids='+ids;
    }
    MuiShow(url,title,width,height);
}

function opt_one(o,msg){
    return del_one(o,msg);
}

function del_one(o,msg){
    art.dialog.confirm(msg, function () {
        $.post($(o).attr('href'),{isajax:1},function(data){
            ajaxAlert(data,1,true);
        },'json');
    });
    return false;
}

function resize_img(w,h,x,y){
	var w_original=w,h_original=h;
	if (w > x) {
	  h = h * (x / w);
	  w = x;
	}
	if (h > y) {
		w = w_original * (y / h_original);
		h = y;
	}
	return {width:w,height:h}
}
//上传窗口
var uploadWin={
    open: function(type,num,callbackFun,attach){
        var arg_type = type || 'image';
        var arg_num = num || '1';
        var attach = attach || '1';

        if(typeof(callbackFun) == "function"){
            uploadWin.callback = callbackFun;
        }else{
            uploadWin.callback = null;
        }
        art.dialog.open(BASE_URL+'sys.php?app=base&m=upfile&type='+arg_type+'&num='+arg_num+'&attach='+attach,{
            title: 'Meiu上传组件',
            lock: true,
            fixed: true,
            background: '#000', // 背景色
            opacity: 0.3,  // 透明度
            okVal:'选好了',
            ok: function (w) {
                var ids = w.$('#att-ids').html();
                var paths = w.$('#att-path').html();
                var names = w.$('#att-name').html();
                if(paths == ''){//如果没有选择任何文件的话，提醒
                    alert('请先选择文件！');
                    return false;
                }

                var idarr = ids.split('|');
                var patharr = paths.split('|');
                var namearr = names.split('|');
                var ret = new Array;
                for(var i in patharr){
                    if(patharr[i]!='')
                        ret.push({"id":idarr[i],"path":patharr[i],"name":namearr[i]});
                }
                if(patharr.length > 1){
                    if(arg_num == '1'){
                        uploadWin.callback(ret[0]);
                    }else{
                        uploadWin.callback(ret);
                    }
                }
                return true;
            },
            cancelVal: '取消',
            cancel: true
        });
    },
    callback: function(ret){}
}

function editorMultiUpload(editor){
    var img;
    var p;
    uploadWin.open('image',20,function(vReturnValue){ 
        for (var i in vReturnValue) {
            p = new CKEDITOR.dom.element( 'p' );
            img = new CKEDITOR.dom.element("img");
            img.setAttribute('src',  UPFILE_PRE+vReturnValue[i]['path']);
            p.append(img);
            editor.insertElement( p ); 
        };
    });
}

function sel_relate(id,model){
    art.dialog.open(BASE_URL+'sys.php?app=cms&m=relate&model='+model,{
        title: '选择关联项',
        lock: true,
        background: '#000', // 背景色
        opacity: 0.3,  // 透明度
        okVal:'插入',
        ok: null,
        close: function () {
            var selid = art.dialog.data('selid');// 读取B页面的数据
            if (selid !== undefined) {
                $('#r_'+id).val(selid);
            }
        },
        cancelVal: '取消',
        cancel: true
    });
}

function MuiShow(o,title,width,height){
    if(typeof(o) == 'string'){
        var url = o;
    }else{
        var url = $(o).attr('href');
    }

    //随机生成一个窗口id
    var winid = "win"+Math.floor(Math.random()*10000);
    if(url.indexOf('?') > -1){
        url = url+'&winid='+winid;
    }else{
        url = url+'?winid='+winid;
    }
    var option = {
        id:winid,
        title: title,
        lock: true,
        background: '#000', // 背景色
        opacity: 0.3,  // 透明度
        okVal:'确定',
        fixed:true,
        ok: function () {
            var iframe = this.iframe.contentWindow;
            var formbtn = iframe.document.getElementById('dosubmit');
            if(formbtn){
                formbtn.click();
            }
            return false;
        },
        cancelVal: '取消',
        cancel: function(){
            this.close()
        }
    };
    if(typeof(width) != 'undefined'){
        option.width = width;
    }
    if(typeof(height) != 'undefined'){
        option.height = height;
    }

    window.top.art.dialog.open(url,option);
    return false;
}

//图片输入框显示缩略图
function picArea(){
    $('.picarea').hover(function(){
        //显示图片
        var picurl = $(this).val();
        var pos = $(this).offset();
        var iheight=$(this).outerHeight();

        if(!picurl){
            return false;
        }
        if($('#img_preview').length <= 0 ){
            $('body').append('<div id="img_preview">Loading...</div>');
        }
        $('#img_preview').html('Loading...').css({top:pos.top+iheight-2,left:pos.left}).show();

        var im=new Image();
        im.src=UPFILE_PRE+picurl;

        im.onload = function ()
        {
            var size = resize_img(im.width,im.height,120,120);
            var div_html = '<img src="'+im.src+'" width="'+parseInt(size.width)+'" height="'+parseInt(size.height)+'" /></div>';
            $('#img_preview').html(div_html);
        }
    },function(){
        //隐藏图片
        $('#img_preview').hide();
    });
}
//最后一个参数可选
function addEpics(vReturnValue,thediv,inputname){
    var append='';
    var txtname='';
    for(i in vReturnValue){
        txtname = vReturnValue[i]['name'].replace(/.(jpg|png|gif|jpeg)$/i,''); 
        $(thediv).append('<li class="epic"'+append+'><div class="outerb"><div class="img"><img src="'+UPFILE_PRE+vReturnValue[i]['path']+'" width="120" /></div></div><div class="txt"><input type="text" value="'+txtname+'" name="txt_'+inputname+'" /></div><input type="hidden" value="'+vReturnValue[i]['path']+'" name="'+inputname+'"><a href="javascript:void(0)" onclick="delEpic(this,\''+vReturnValue[i]['path']+'\')">[删除]</a></li>');
    }
}

function delEpic(o,filepath){
    //if(confirm('是否同时删除文件？')){
    //    $.post('?c=files&a=delpic',{path:filepath});
    //}
    $(o).parent().remove();
}
//内容管理页面自动高度
function autoSizeContent(){
    var clientHeight = $(window).height();
    var contentheight = clientHeight-147;
    $('.body').height(contentheight);
    $('.body .mainbody .left').height(contentheight);
    $('.body .mainbody .right iframe').height(contentheight);
}
$(function(){
    setForm();

    $('#head a.dropmenu').click(function(){
        if($("#menu").is(":visible")){
            $("#menu").slideUp();
        }else{
            $("#menu").slideDown();
        }
    });

    $('select.chosen').chosen({no_results_text:'未匹配到'});
})