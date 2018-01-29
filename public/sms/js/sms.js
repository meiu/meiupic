function timeLeftBtn(btn,time){
    var t=time;
    $(btn).attr('disabled',true);
    var aa=setInterval(function(){
        if(t>=1){
            $(btn).val(t+' 秒后再获取');
        }else{
            $(btn).val('获取验证码');
            $(btn).attr('disabled',false);
            if(aa){
                clearInterval(aa);
                return false;
            }
        }
        t--;
    },1000);
}

function showMobileCaptcha(div,ipt){
    $(div).click(function(){
        var mobile = $(ipt).val();
        
        if(mobile == ''){
            alert('请先输入手机号码！');
            return false;
        }
        if(!(/^1\d{10}$/.test(mobile))){
            alert('手机号码格式输入错误！');
            return false;
        } 
        var bodyheight = $('html').outerHeight();
        var orgi = $(this).attr('orgi');
        var smsurl = $(this).attr('smsurl');

        $('body').append('<div id="float_sms_bg" style="position:absolute;left:0;top:0;width:100%;height:'+bodyheight+'px;background:#000;opacity:0.4;"> </div><div id="float_sms_div" style="position:fixed;left:50%;top:50%;width:320px;height:200px;padding:20px;margin-left:-160px;margin-top:-100px;border:1px solid #ccc;background:#fff;text-align:center;"><a id="float_sms_close" style="position:absolute;right:10px;top:2px;font-size:20px;" href="javascript:void(0)">x</a><h2 style="font-size:16px;color:#5fb336;text-align:center;">发送短信验证码</h2><form style="margin:0;padding:0;" method="post" onsubmit="return false;"><div>图形验证码: <input type="text" name="send_captcha" style="border-radius: 4px;font-size:16px;border:1px solid #ccc;width:90px;padding: 6px 12px;" /> <img style="height:34px;cursor:pointer;" src="'+orgi+(orgi.indexOf('?')>=0?'&':'?')+'t='+Math.random()+'" align="absmiddle" /> <div style="text-align:center;padding:20px 0;"><input id="float_sms_btn" type="submit" value="确定发送" style="border: none;background-color: #5fb336;color: #fff;border-radius: 6px;height: 32px;padding: 0 10px;line-height: 32px;font-size: 14px;" /></div></div></form></div>');
        var capt_ipt = $('#float_sms_div input[name="send_captcha"]');
        capt_ipt.focus();

        $("#float_sms_close").click(function(){
            $('#float_sms_bg').remove();
            $('#float_sms_div').remove();
        });
        $('#float_sms_div img').click(function(){
            $(this).attr('src',orgi+(orgi.indexOf('?')>=0?'&':'?')+'t='+Math.random() );
        });

        $('#float_sms_div form').submit(function(){
            var capt = capt_ipt.val();
            if(capt==''){
                alert('请输入验证码！');
                return false;
            }
            $.post(smsurl,{captcha:capt,mobile:mobile},function(data){
                if(data.ret){
                    $("#float_sms_close").click();

                    //按钮倒计时
                    timeLeftBtn(div,60);

                }else{
                    alert(data.msg);
                    capt_ipt.val('');
                    $('#float_sms_div img').click();
                }
            },'json');
            return false;
        });
    });
}