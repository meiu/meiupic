function photo_detail_click(){
    $('a.fullscreen,img.photo').click(function () {
        screenfull.toggle($('.viewphoto')[0]);
    });

    $('a.addto').click(function () {
        var currentPhoto = $('body .photo-thumbs ul li.current');
        var imgid = currentPhoto.attr('data-imgid');
        var url = $(this).attr('data-url');
        if(url.indexOf('?') > -1){
            url = url+'&photo_id='+imgid;
        }else{
            url = url+'?photo_id='+imgid;
        }
        MuiShow(url,'添加图片至图集',500,500);
        return false;
    });

    $('a.download').click(function () {
        var currentPhoto = $('body .photo-thumbs ul li.current');
        var imgid = currentPhoto.attr('data-imgid');
        var url = $(this).attr('data-url');
        if(url.indexOf('?') > -1){
            url = url+'&id='+imgid;
        }else{
            url = url+'?id='+imgid;
        }
        window.open(url);
        return false;
    });

    $('.photo-container').get(0).oncontextmenu = function(e){
        return false;
    }

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

    var currentPhoto = $('body .photo-thumbs ul li.current');
    if(currentPhoto.length> 0){
        var exif=$('<div/>').html(currentPhoto.attr('data-exif')).text();
        showExif(exif);

        var ulpos = $('body .photo-thumbs ul').offset();
        var po = currentPhoto.offset();

        $('.photo-container .toolbar .caption span').html(currentPhoto.index()+1);

        $('body .photo-thumbs ul').css({
            'transform': 'translate3d(-'+(po.left - ulpos.left)+'px, 0px, 0px)'
        });

        var side_info_url = currentPhoto.attr('data-info-url');
        if(side_info_url){
            $('#set-side-info').load(side_info_url);
        }
    }

    $('body .viewphoto').find('div.photo-container a.btn-prev').click(function(){
        var pre=$('body .photo-thumbs ul li.current').prev();
        pre && pre.click();
        return false;
    });
    $('body .viewphoto').find('div.photo-container a.btn-next').click(function(){
        var next=$('body .photo-thumbs ul li.current').next();
        next && next.click();
        return false;
    });

    //点击缩略图查看大图
    $('body .photo-thumbs ul li').click(function(){
        var ulpos = $('body .photo-thumbs ul').offset();
        var po = $(this).offset();
        var that = this;
        $(this).addClass('current').siblings().removeClass('current');
        var index = $(this).index();

        if($(this).hasClass('next')){//如果是下一个相册
            var workurl = $(this).attr('data-workurl');
            if(typeof theater != "undefined" && theater.inited){
                theater.goNext(function(){
                    $(that).prev().click();
                    $(that).remove();
                });
            }else{
                window.location.href = workurl;
            }
            return false;
        }
        $('.photo-container .toolbar .caption span').html(index+1);

        var imgthumb = $(this).find('img').attr('src');
        //先填充小图，然后用大图替换
        
        var imgid = $(this).attr('data-imgid');
        var bigsrc = $(this).attr('data-bigsrc');
        var exif_data = $(this).attr('data-exif');
        if(exif_data){
            showExif($('<div/>').html(exif_data).text());
        }
        var side_info_url = $(this).attr('data-info-url');
        if(side_info_url){
            $('#set-side-info').load(side_info_url);
        }

        $('body .viewphoto img.photo').css({'min-height':$('body .photo-view').height()});

        $('body .viewphoto').attr('data-imgid',imgid);
        $('body .viewphoto img.photo').attr('src',imgthumb);

        var bigimg = new Image();
        bigimg.src = bigsrc;
        bigimg.onload = function(){
            if($('body .viewphoto img.photo').attr('src') == imgthumb){
                $('body .viewphoto img.photo').attr('src',bigsrc);
                $('body .viewphoto img.photo').css({'min-height':0});
            }
        }

        $('body .photo-thumbs ul').css({
            'transform': 'translate3d(-'+(po.left - ulpos.left)+'px, 0px, 0px)'
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

function showExif(exif){
    if(exif && typeof exif == 'string'){
        exif = JSON.parse(exif);
    }
    if(exif){
        var html = '<h2>'+exif.Model+'</h2>' +
                    '<div class="label small camera_settings">' +
                    '<div>' + 
                    '    <div title="光圈" class="cicon apertureIcon"></div>' +
                    '    <div>'+ (exif.ApertureFNumber?exif.ApertureFNumber:exif.FNumber)+'</div>' +
                    '</div>'+
                    '<div>'+
                    '    <div title="快门速度" class="cicon exposureTimeIcon"></div>' +
                    '    <div>'+ (exif.ExposureTime?exif.ExposureTime.replace('sec.',''):'') +' s</div>' +
                    '</div>' +
                    '<div>' +
                    '    <div title="焦距" class="cicon focalLengthIcon"></div>' +
                    '    <div>'+ (exif.FocalLength?exif.FocalLength:'') + '</div>' +
                    '</div>' +
                    '<div>' +
                    '    <div title="ISO/胶片" class="cicon isoIcon"></div>' +
                    '    <div>ISO '+ (exif.ISOSpeedRatings?exif.ISOSpeedRatings:'') + '</div>' +
                    '</div>' +
                '</div>';

        $('.sidebar-exif').html(html);
        $('.sidebar-exif').show();
    }else{
        $('.sidebar-exif').hide();
    }
}

function bind_keys(){
    $(document).bind('keydown',
         function(e){
             if (e.altKey) return true;
             var target = e.target;
             if (target && target.type) return true;
             switch(e.keyCode) {
                 case 63235: case 39: 
                  $('body .viewphoto').find('div.photo-container a.btn-next').click();
                  break;
                 case 63234: case 37:
                  $('body .viewphoto').find('div.photo-container a.btn-prev').click();
                  break;
             }
         }
    );
    $(document).on("mousewheel DOMMouseScroll",'.photo-container', function (e) {
        var delta = (e.originalEvent.wheelDelta && (e.originalEvent.wheelDelta > 0 ? 1 : -1)) ||  // chrome & ie
            (e.originalEvent.detail && (e.originalEvent.detail > 0 ? -1 : 1));              // firefox

        //防止连续滚动，加个延时
        if ($(this).data('scrollTimeout')) {
          clearTimeout($(this).data('scrollTimeout'));
        }
        $(this).data('scrollTimeout',setTimeout(function(){
            if (delta > 0) {
               $('body .viewphoto').find('div.photo-container a.btn-prev').click();
            } else if (delta < 0) {
               $('body .viewphoto').find('div.photo-container a.btn-next').click();
            }
        },200));

        
    });
}