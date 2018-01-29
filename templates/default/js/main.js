function photo_detail_click(){
    $('a.fullscreen,img.photo').click(function () {
        screenfull.toggle($('.viewphoto')[0]);
    });
    $('a.icon-like').click(function(){
        var that = this;
        if(this.href=='javascript:void(0)'){
            return false;
        }
        return opt_one(this,false,function(){
            that.text(parseInt(that.text())+1);
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