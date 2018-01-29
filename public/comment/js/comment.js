/*依赖jquery，如果没有请自行载入*/
function show_comment_alert(msg){
    if(window.top.art){
        window.top.art.dialog.tips(msg,1,false);
    }else{
        alert(msg);
    }
}
var commentInit = function(){
    //表及id
    var ele = $('.mc-thread');
    var mod=ele.attr('data-thread-mod');
    var infoid=ele.attr('data-id');
    var options=ele.attr('data-options');
    var postform='';
    var clicked_appraise=false;
    l = 0;

    options = $.parseJSON(options);

    ele.delegate('form','submit',function(){
        $.post(options.add_url,$(this).serializeArray(),function(data){
            if(data.ret){
                //重新加载当前数据;
                show_comment_alert(data.msg);
                getList();
            }else{
                show_comment_alert(data.msg);
            }
        },'json');
        return false;
    });
    ele.delegate('a.mc-post-reply','click',function(){
        var postli = $(this).closest('li');
        
        if($(this).hasClass('mc-reply-actived')){
            $(this).removeClass('mc-reply-actived');
            postli.find('div.mc-replybox').fadeOut(200, function() {
                $(this).remove();
            });
        }else{
            $(this).addClass('mc-reply-actived');
            postli.find('.mc-comment-body').append('<div class="mc-replybox">'+postform.replace('{$par_id}',postli.attr('data-post-id'))+'</div>').find("textarea").focus();

            //隐藏其他的框
            postli.siblings().find('div.mc-replybox').hide(0, function() {
                $(this).parent().find('a.mc-reply-actived').removeClass('mc-reply-actived');
                $(this).remove();
            });
        }
        return false;
    });
    ele.delegate('a.mc-post-good','click',function(){//赞的操作
        if(clicked_appraise) return false;

        var postli = $(this).closest('li');
        var apprise_num = $(this).find('em');
        $.post(options.appraise_url,{'postid':postli.attr('data-post-id'),'type':'support'},function(data){
            if(data.ret){
                apprise_num.text('('+data.msg+')');
            }else{
                show_comment_alert(data.msg);
            }
        },'json');
        clicked_appraise=true;

        return false;
    });
    
    ele.delegate('a.mc-post-bad','click',function(){//反对的操作
        if(clicked_appraise) return false;

        var postli = $(this).closest('li');
        var apprise_num = $(this).find('em');
        $.post(options.appraise_url,{'postid':postli.attr('data-post-id'),'type':'object'},function(data){
            if(data.ret){
                apprise_num.text('('+data.msg+')');
            }else{
                show_comment_alert(data.msg);
            }
        },'json');
        clicked_appraise=true;

        return false;
    });

    ele.delegate('.mc-comment-pages a','click',function(){//翻页的操作
        var url = $(this).attr('href');

        if(url){
            ele.prepend('<div class="mc-comment-loading">加载评论中...</div>');
            $.get(url,{'_':Math.random()},function(data){
                parseData(data);
            },'json');
        }
        return false;
    });

    ele.delegate('.captcha_show a,.captcha_show img','click',function(e){
        var imgobj = $(this).parent().find('img');
        var osrc=imgobj.attr('orgisrc');
        imgobj.attr('src',osrc+(osrc.indexOf('?')>=0?'&':'?')+'t='+Math.random() );
        return false;
    });

    ele.delegate('textarea','keypress',function(e){//Ctrl+Enter提交评论
        if(e.ctrlKey&&e.keyCode==13){
            $(this).closest('form').submit();
        }
    });
    //时间相关函数
    var b = {
        parseDate: function(e) {
            return e.parse("2011-10-28T00:00:00+08:00") &&
            function(t) {
                return new e(t)
            } || e.parse("2011/10/28T00:00:00+0800") &&
            function(t) {
                return new e(t.replace(/-/g, "/").replace(/:(\d\d)$/, "$1"))
            } || e.parse("2011/10/28 00:00:00+0800") &&
            function(t) {
                return new e(t.replace(/-/g, "/").replace(/:(\d\d)$/, "$1").replace("T", " "))
            } ||
            function(t) {
                return new e(t)
            }
        }(Date),
        fullTime: function(e) {
            var t = b.parseDate(e);
            return t.getFullYear() + "年" + (t.getMonth() + 1) + "月" + t.getDate() + "日 " + t.toLocaleTimeString()
        },
        elapsedTime: function(e) {
            var t = b.parseDate(e),
                n = new Date,
                r = (n - l - t) / 1e3;
            return r < 10 ? "刚刚" : r < 60 ? Math.round(r) + "秒前" : r < 3600 ? Math.round(r / 60) + "分钟前" : r < 86400 ? Math.round(r / 3600) + "小时前" : (n.getFullYear() == t.getFullYear() ? "" : t.getFullYear() + "年") + (t.getMonth() + 1) + "月" + t.getDate() + "日"
        },
        timeHtml: function(e) {
            return '<span class="mc-time" datetime="' + e + '" title="' + b.fullTime(e) + '">' + b.elapsedTime(e) + "</span>"
        }
    }
    //加载评论数据
    function getList(){
        $.get(options.list_url,{'_':Math.random()},function(data){
            parseData(data);
        },'json');
    }
    //渲染并显示内容
    function parseData(d){
        if(d.status=='closed'){
            ele.html('<div class="comment-closed">评论已关闭！</div>');
            return false;
        }
        initPostForm(d);

        var commentsHtml = '<ul class="mc-comments">';
        var listhtml = '';
        for(i in d.List){
            listhtml += '<li class="mc-post" data-post-id="'+d.List[i].id+'">'
            +'<div class="mc-avatar"><img alt="'+d.List[i].author+'" src="'+d.List[i].avatar+'"></div>'
            +'<div class="mc-comment-body"><div class="mc-comment-header"><a class="mc-user-name" data-user-id="'+d.List[i].uid+'" rel="nofollow" href="javascript:void(0)">'+d.List[i].author+'</a></div>'
            +'<p>'+d.List[i].content+'</p>'
            +'<div class="mc-comment-footer">'
            +b.timeHtml(d.List[i].addtime)
            +(d.options.allow_reply?'<a href="javascript:void(0)" class="mc-post-reply"><span class="mc-icon mc-icon-reply"></span>回复</a>':'');
            if(d.options.allow_support){
                listhtml += '<a href="javascript:void(0)" class="mc-post-good"><span class="mc-icon mc-icon-good"></span>赞<em>'+(d.List[i].support>0?'('+d.List[i].support+')':'')+'</em></a>';
            }
            if(d.options.allow_object){
                listhtml += '<a href="javascript:void(0)" class="mc-post-bad"><span class="mc-icon mc-icon-bad"></span>反对<em>'+(d.List[i].object>0?'('+d.List[i].object+')':'')+'</em></a>';
            }
            listhtml += '</div></div>'
            +'</li>';
        }
        if(listhtml == ''){
            commentsHtml += '<li class="mc-post mc-post-placeholder">还没有评论，沙发等你来抢</li></ul>';
        }else{
            commentsHtml += listhtml+'</ul>';
        }
        

        ele.html('<!--<div class="mc-comments-counts"><strong>'+d.totalCount+'</strong> 条评论</div>--><div class="mc-replybox">'+postform.replace('{$par_id}','')+'</div>'+commentsHtml+'<div class="mc-comment-pages">'+d.pagerHtml+'</div>');
    }

    //初始化评论表单模版
    function initPostForm(d){
        if(d.user.id > 0){//已登录
            postform = '<a class="mc-avatar" href="javascript:void(0)">\
                <img alt="'+d.user.nickname+'" src="'+d.user.avatar+'">\
            </a>\
            <form method="post">\
        <input type="hidden" value="{$par_id}" name="par_id">\
        <div class="mc-comment-user">\
            '+d.user.nickname+', 您已登录。<a href="'+options.logout_url+(options.logout_url.indexOf("?")>=0?'&':'?')+'redirect='+encodeURIComponent(window.location.href)+'">退出登录</a>';
            if(d.options.enable_captcha){
                postform += '<div class="captcha_show"><span>验证码:</span>　<input type="text" value="" name="captcha" /> <img src="'+options.captcha_url+(options.captcha_url.indexOf('?')?'&':'?')+'t='+Math.random()+'" orgisrc="'+options.captcha_url+'" /> <a href="#">更换验证码</a></div>';
            }
            postform += '</div>\
        <div class="mc-comment-textarea">\
            <textarea name="content" placeholder="请输入评论内容"></textarea>\
        </div>\
        <div class="mc-comment-submit">\
            <input type="submit" value="提交评论">\
        </div>\
        </form>';
        }else{//未登录
            postform = '<a class="mc-avatar" href="javascript:void(0)">\
                <img alt="'+d.user.nickname+'" src="'+d.user.avatar+'">\
            </a>\
            <form method="post">\
        <input type="hidden" value="{$par_id}" name="par_id">\
        <div class="mc-comment-user">';
            //允许游客评论
            if(d.options.allow_guest){
                postform += '<span>昵　称: </span><input type="text" value="" name="author" /><span>Email: </span><input type="text" value="" name="email" />';
                if(d.options.enable_captcha){
                    postform += '<div class="captcha_show"><span>验证码: </span><input type="text" value="" name="captcha" /> <img src="'+options.captcha_url+(options.captcha_url.indexOf('?')?'&':'?')+'t='+Math.random()+'" orgisrc="'+options.captcha_url+'" /> <a href="#">更换验证码</a></div>';
                }
            }else{
                postform += '您好，请先<a href="'+options.login_url+(options.login_url.indexOf("?")>=0?'&':'?')+'redirect='+encodeURIComponent(window.location.href)+'">登录</a>';
            }

            postform += '</div>\
        <div class="mc-comment-textarea">\
            <textarea name="content" placeholder="请输入评论内容"></textarea>\
        </div>\
        <div class="mc-comment-submit">\
            <input type="submit" value="提交评论">\
        </div>\
        </form>';
        }
    }
    
    //初次加载列表
    getList();
}