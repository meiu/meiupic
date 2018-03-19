function ajaxAlert(data,time,lock,callback){
    time = time||1;
    lock = lock||false;

    if(!data.ret && data.msg){
        window.top.art.dialog.alert(data.msg);
        return;
    }

    if(data.ret && data.msg){
        window.top.art.dialog.tips(data.msg,time,lock);
    }
    if(typeof(callback) == 'function'){
        callback();
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
    $('form.ajaxform').ajaxForm({
        data: { ajaxform: '1' },
        dataType: 'json',
        success: function(data){
            ajaxAlert(data,1,true);
        }
    });
}

function opt_one(o,msg,param,callback){
    if(param){
        param.isajax = 1;
    }else{
        param = {isajax:1};
    }
    if(msg){
        art.dialog.confirm(msg, function () {
            $.post($(o).attr('href'),param,function(data){
                ajaxAlert(data,1,true,callback);
            },'json');
        });
    }else{
        $.post($(o).attr('href'),param,function(data){
                ajaxAlert(data,1,true,callback);
        },'json');
    }
    
    return false;
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

$(function(){
    $('.sub-list-trigger').hover(function(){
        $(this).find('.sub-list').css('opacity',1).show();
    },function(){
        $(this).find('.sub-list').css('opacity',0).hide();
    });

    setForm();
});