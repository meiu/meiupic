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
    $('form.ajaxform').ajaxForm({
        data: { ajaxform: '1' },
        dataType: 'json',
        success: function(data){
            ajaxAlert(data,1,true);
        }
    });
}

$(function(){
    $('.sub-list-trigger').hover(function(){
        $(this).find('.sub-list').css('opacity',1).show();
    },function(){
        $(this).find('.sub-list').css('opacity',0).hide();
    });

    setForm();
});