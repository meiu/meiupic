function initPostUpload(upload_url,w,h,q){
    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'pickfiles',
        container: $('li.upload-item').get(0),
        drop_element:'muilti_uploader',
        url : upload_url,
        flash_swf_url : PUBLIC_URL+'base/plupload/Moxie.swf',
        silverlight_xap_url : PUBLIC_URL+'base/plupload/Moxie.xap',
        chunk_size : '1mb',
        unique_names:true,
        send_file_name:true,
        filters : {
            max_file_size : '20mb',
            mime_types: [
                {title : "Image files", extensions : "jpeg,jpg,gif,png"}
            ]
        },
        resize : w&&h?{width : w, height : h, quality : q}:false,
        init: {
            PostInit: function() {
                $('#muilti_uploader ul.photo-list li.photo-item').remove();
            },
            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    $('#muilti_uploader ul li.upload-item').before('<li class="photo-item photo-progress" id="' + file.id + '"><i></i><span>上传中 0%</span></li>');
                });
                uploader.start();
            },
            UploadProgress: function(up, file) {
                $('#'+file.id).find('span').html('上传中 ' +file.percent + '%');
            },
            FileUploaded: function(up, file, info) {
                console.log('[FileUploaded] File:', file, "Info:", info);
                if(info.status==200){
                    var result = eval("("+info.response+")");
                    var width=height=0;
                    if(result.result.width>result.result.height){
                        width=250;
                        height=parseInt(width/result.result.width*result.result.height);
                    }else{
                        height=250;
                        width=parseInt(height/result.result.height*result.result.width);
                    }
                    width+=10;
                    height+=10;

                    $('#'+file.id).removeClass('photo-progress').html('<img src="'+result.result.path+'"><div class="mask" style="width: '+width+'px; height: '+height+'px;"><a class="icon-close">x</a></div><input type="hidden" name="pic_ids[]" value="'+result.id+'" />');
                }
            },
            Error: function(up, err) {
                console.log(err)
                art.dialog.tips('上传失败！'+err,1,true);
            }
        }
    });
    $('#muilti_uploader').bind('dragover',function(){
        $(this).addClass('drophere');
    });
    $('#muilti_uploader').bind('dragleave',function(){
        $(this).removeClass('drophere');
    });
    $('#muilti_uploader').bind("drop", function (e) {
        $(this).removeClass('drophere');
    });

    $(function(){
        uploader.init();
    });
}
function uploadBanner(upload_url){
    var banneruploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'change-banner',
        url : upload_url,
        flash_swf_url : PUBLIC_URL+'base/plupload/Moxie.swf',
        silverlight_xap_url : PUBLIC_URL+'base/plupload/Moxie.xap',
        resize : { width : 2000, height : 500, quality : 90 },
        filters : {
            max_file_size : '5mb',
            mime_types: [
                {title : "Image files", extensions : "jpg,gif,png"}
            ]
        },

        init: {
            FilesAdded: function(up, files) {
                banneruploader.start();
            },
            UploadProgress: function(up, file) {
                $('#change-banner').html('上传：'+file.percent +'%');
            },
            Error: function(up, err) {
                $('#change-banner').html('编辑封面 &gt;');
                art.dialog.tips('上传失败！',1,true);
            },
            UploadComplete: function(up, files) {
                $('#change-banner').html('编辑封面 &gt;');
                art.dialog.tips('上传成功！',1,true);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }
        }
    });
    banneruploader.init();
}
function uploadAvatar(upload_url){
    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'avatarPicker',
        url : upload_url,
        flash_swf_url : PUBLIC_URL+'base/plupload/Moxie.swf',
        silverlight_xap_url : PUBLIC_URL+'base/plupload/Moxie.xap',
        resize : { width : 400, height : 400, quality : 90 },
        filters : {
            max_file_size : '2mb',
            mime_types: [
                {title : "Image files", extensions : "jpg,gif,png"}
            ]
        },

        init: {
            FilesAdded: function(up, files) {
                uploader.start();
            },
            UploadProgress: function(up, file) {
                $('#avatarPicker label').html('上传：'+file.percent +'%');
            },
            Error: function(up, err) {
                $('#avatarPicker label').html('更换头像');
                art.dialog.tips('上传失败！',1,true);
            },
            UploadComplete: function(up, files) {
                $('#avatarPicker label').html('更换头像');
                art.dialog.tips('上传成功！',1,true);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }
        }
    });

    uploader.init();
}