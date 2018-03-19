function photo_detail_click(){
    $('a.fullscreen,img.photo').click(function () {
        screenfull.toggle($('.viewphoto')[0]);
    });
    $('.viewphoto a.icon-like,.viewphoto a.icon-liked').click(function(){
        var that = this;
        if(this.href=='javascript:void(0)'){
            return false;
        }
        var param = {};
        if($(this).hasClass('icon-liked')){
            param.a = 'cancel';
        }
        return opt_one(this,false,param,function(){
            if($(that).hasClass('icon-like')){
                $('.viewphoto a.icon-like').text(parseInt($(that).text())+1);
                $('.viewphoto a.icon-like').removeClass('icon-like').addClass('icon-liked');
            }else{
                $('.viewphoto a.icon-liked').text(parseInt($(that).text())-1);
                $('.viewphoto a.icon-liked').removeClass('icon-liked').addClass('icon-like');
            }
        });
    });
    screenfull.on('change', function(){
        if (!screenfull.isFullscreen) {
            $('img.photo').css('cursor','zoom-in');
            $('.viewphoto').removeClass('fullscreen');
        }else{
            $('img.photo').css('cursor','zoom-out');
            $('.viewphoto').addClass('fullscreen');
        }
    });
}