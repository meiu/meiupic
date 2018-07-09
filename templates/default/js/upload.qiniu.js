function initPostUpload(upload_url,w,h,q){
    uploadWithQiniu({
            browse_button: 'pickfiles',
            drop_element: 'muilti_uploader',
            container: $('li.upload-item').get(0),
            multi_selection: true,
            resize : w&&h?{width : w, height : h, quality : q}:false
        },{
        PostInit:function(){
            $('#muilti_uploader ul.photo-list li.photo-item').remove();
        },
        Error: function(up, err) {
            art.dialog.tips('上传失败！'+err,1,true);
        },
        FilesAdded: function(up,files){
            plupload.each(files, function(file) {
                $('#muilti_uploader ul li.upload-item').before('<li class="photo-item photo-progress" id="' + file.id + '"><i></i><span>上传中 0%</span></li>');
            });
        },
        UploadProgress: function(uploader, file) {
            $('#'+file.id).find('span').html('上传中 ' +file.percent + '%');
        },
        uploadFinish: function(res,info,file_id){
            console.log(res);
            var width=height=0;
            if(info.width>info.height){
                width=250;
                height=parseInt(width/info.width*info.height);
            }else{
                height=250;
                width=parseInt(height/info.height*info.width);
            }
            width+=10;
            height+=10;

            $.post(upload_url,{type:'uploaded',name:res['x:name'],width:info.width,height:info.height,filesize:info.size,key:res.key},function(data){
                if(data.ret){
                    $('#'+file_id).removeClass('photo-progress').html('<img src="'+data.info.url+'"><div class="mask" style="width: '+width+'px; height: '+height+'px;"><a class="icon-close">x</a></div><input type="hidden" name="pic_ids[]" value="'+data.info.id+'" />');
                }
            },'json');
        },
        updateExif: function(key,exif){
            $.post(upload_url,{type:'updateexif',key:key,exif:JSON.stringify(exif)},function(data){},'json');
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
}
/*

function uploadBanner(upload_url){
    uploadWithQiniu({
            browse_button: 'change-banner',
            multi_selection: false,
            resize : {width : 2000, height : 500, quality : 90}
        },{
        Error: function(up, err) {
            $('#change-banner').html('编辑封面 &gt;');
            art.dialog.tips('上传失败！'+err,1,true);
        },
        UploadProgress: function(uploader, file) {
            $('#change-banner').html('上传：'+file.percent +'%');
        },
        UploadComplete: function(up, files) {
            $('#change-banner').html('编辑封面 &gt;');
            art.dialog.tips('上传成功！',1,true);
            setTimeout(function(){
                window.location.reload();
            },1000);
        },
        uploadFinish: function(res,info,file_id){
            //上传成功
        }
    });

}

function uploadAvatar(upload_url){
    uploadWithQiniu({
            browse_button: 'avatarPicker',
            multi_selection: false,
            resize : {width : 400, height : 400, quality : 90}
        },{
        Error: function(up, err) {
            $('#avatarPicker label').html('更换头像');
            art.dialog.tips('上传失败！'+err,1,true);
        },
        UploadProgress: function(uploader, file) {
            $('#avatarPicker label').html('上传：'+file.percent +'%');
        },
        UploadComplete: function(up, files) {
            $('#avatarPicker label').html('更换头像');
            art.dialog.tips('上传成功！',1,true);
            setTimeout(function(){
                window.location.reload();
            },1000);
        },
        uploadFinish: function(res,info,file_id){
            
        }
    });
}
*/

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

function uploadWithQiniu(upconfig,callbacks){
    var config = {
      useCdnDomain: true,
      disableStatisticsReport: false,
      retryCount: 5,
      region: qiniu.region.z0
    };
    var putExtra = {
      fname: "",
      params: {},
      mimeType: null
    };
    var domain = $('meta[name="updomain"]').attr('content');
    var token = $('meta[name="uptoken"]').attr('content');

    qiniu.getUploadUrl(config, token).then(function(res){
    var uploadUrl = res
    var board = {};
    var indexCount = 0;
    var resume = false;
    var chunk_size;
    var blockSize;
    var plupload_config = {
      runtimes: "html5,flash,silverlight,html4",
      url: uploadUrl,
      flash_swf_url : PUBLIC_URL+'base/plupload/Moxie.swf',
      silverlight_xap_url : PUBLIC_URL+'base/plupload/Moxie.xap',

      chunk_size: 4 * 1024 * 1024,
      max_retries: 3,
      filters : {
            max_file_size : '20mb',
            mime_types: [
                {title : "Image files", extensions : "jpeg,jpg,gif,png"}
            ]
        },
      multipart_params: {
        token: token
      },
      init: {
        PostInit: function() {
            if(callbacks && callbacks.PostInit){
                callbacks.PostInit();
            }
        },
        FilesAdded: function(up, files) {
          resume = false;
          chunk_size = uploader.getOption("chunk_size");

          if(callbacks && callbacks.FilesAdded){
            callbacks.FilesAdded(up,files);
          }
          uploader.start();
        },
        UploadProgress: function(uploader, file) {
          var id = file.id;
          // 更新进度条进度信息;
          var fileUploaded = file.loaded || 0;
          var count = Math.ceil(file.size / uploader.getOption("chunk_size"));
          if (file.size > chunk_size) {
              var index = Math.ceil(file.loaded / chunk_size);
              var leftSize = file.loaded - chunk_size * (index - 1);
              if (index == count) {
                chunk_size = file.size - chunk_size * (index - 1);
              }
          }

          if(callbacks && callbacks.UploadProgress){
            callbacks.UploadProgress(uploader,file);
          }
          
        },
        FileUploaded: function(up, file, info) {
          console.log(info);
          var id = file.id;
          if (resume) {
            mkFileRequest(file)
          } else {
            uploadFinish(JSON.parse(info.response), file.name,id);
          }
        },
        UploadComplete: function(up, files) {
          console.log("[完成]");

          if(callbacks && callbacks.UploadComplete){
            callbacks.UploadComplete(up,files);
          }
        },
        Error: function(up, err) {
          console.log(err)
          if(callbacks && callbacks.Error){
            callbacks.Error(up,err);
          }
        }
      }
    };
    $.extend(plupload_config, upconfig);
    var uploader = new plupload.Uploader(plupload_config);
    uploader.init();
    uploader.bind("BeforeUpload", function(uploader, file) {
      //上传路径
      var date = new Date();
      var timestamp = date.getTime();
      var ext = file.name.split('.').splice(-1);
      key = 'albums/'+formatDate(date)+'/'+timestamp+'.'+ext;

      putExtra.params["x:name"] = file.name;
      var id = file.id;
      chunk_size = uploader.getOption("chunk_size");
      var directUpload = function() {
        var multipart_params_obj = {};
        multipart_params_obj.token = token;
        // filterParams 返回符合自定义变量格式的数组，每个值为也为一个数组，包含变量名及变量值
        var customVarList = qiniu.filterParams(putExtra.params);
        for (var i = 0; i < customVarList.length; i++) {
          var k = customVarList[i];
          multipart_params_obj[k[0]] = k[1];
        }
        multipart_params_obj.key = key;
        uploader.setOption({
          url: uploadUrl,
          multipart: true,
          multipart_params: multipart_params_obj
        });
      };

      var resumeUpload = function() {
        blockSize = chunk_size;
        initFileInfo(file);
        if(blockSize === 0){
          mkFileRequest(file)
          uploader.stop()
          return
        }
        resume = true;
        var multipart_params_obj = {};
        var headers = qiniu.getHeadersForChunkUpload(token)
        uploader.setOption({
          url: uploadUrl + "/mkblk/" + blockSize,
          multipart: false,
          required_features: "chunks",
          headers: {
            Authorization: "UpToken " + token
          },
          multipart_params: multipart_params_obj
        });
      };
      // 判断是否采取分片上传
      if (
        (uploader.runtime === "html5" || uploader.runtime === "flash") &&
        chunk_size
      ) {
        if (file.size < chunk_size) {
          directUpload();
        } else {
          resumeUpload();
        }
      } else {
        console.log(
          "directUpload because file.size < chunk_size || is_android_weixin_or_qq()"
        );
        directUpload();
      }
    });

    uploader.bind("ChunkUploaded", function(up, file, info) {
      var res = JSON.parse(info.response);
      var leftSize = info.total - info.offset;
      var chunk_size = uploader.getOption && uploader.getOption("chunk_size");
      if (leftSize < chunk_size) {
        up.setOption({
          url: uploadUrl + "/mkblk/" + leftSize
        });
      }
      up.setOption({
        headers: {
          Authorization: "UpToken " + token
        }
      });
      // 更新本地存储状态
      var localFileInfo = JSON.parse(localStorage.getItem(file.name))|| [];
      localFileInfo[indexCount] = {
        ctx: res.ctx,
        time: new Date().getTime(),
        offset: info.offset,
        percent: file.percent
      };
      indexCount++;
      localStorage.setItem(file.name, JSON.stringify(localFileInfo));
    });

    function uploadFinish(res, name, id) {
      localStorage.removeItem(name)
      if (res.key && res.key.match(/\.(jpg|jpeg|png|gif)$/)) {

        qiniu.imageInfo(res.key,domain).then(function(d){
            if(callbacks && callbacks.uploadFinish){
                callbacks.uploadFinish(res,d.data,id);
            }
            if(callbacks && callbacks.updateExif){
                qiniu.exif(res.key,domain).then(function(exif){
                    callbacks.updateExif(res.key,exif.data);
                });
            }
        });
      }
    }

    function initFileInfo(file) {
      var localFileInfo = JSON.parse(localStorage.getItem(file.name))|| [];
      indexCount = 0;
      var length = localFileInfo.length
      if (length) {
        var clearStatus = false
        for (var i = 0; i < localFileInfo.length; i++) {
            indexCount++
          if (isExpired(localFileInfo[i].time)) {
            clearStatus = true
            localStorage.removeItem(file.name);
            break;
          }
        }
        if(clearStatus){
          indexCount = 0;
          return
        }
        file.loaded = localFileInfo[length - 1].offset;
        var leftSize = file.size - file.loaded;
        if(leftSize < chunk_size){
          blockSize = leftSize
        }
        file.percent = localFileInfo[length - 1].percent;
        return
      }else{
        indexCount = 0
      }
    }

    function mkFileRequest(file){
      // 调用sdk的url构建函数
      var requestUrl = qiniu.createMkFileUrl(
        uploadUrl,
        file.size,
        key,
        putExtra
      );
      var ctx = []
      var id = file.id
      var local = JSON.parse(localStorage.getItem(file.name))
      for(var i =0;i<local.length;i++){
        ctx.push(local[i].ctx)
      }
      // 设置上传的header信息
      var headers = qiniu.getHeadersForMkFile(token)
      $.ajax({url: requestUrl, type: "POST",  headers: headers, data: ctx.join(","), success: function(res){
        uploadFinish(res, file.name,id);
      }})
    }

    function isExpired(time){
      var expireAt = time + 3600 * 24* 1000;
      return new Date().getTime() > expireAt;
    }
  });
}

function formatDate(date) {  
    var y = date.getFullYear();  
    var m = date.getMonth() + 1;  
    m = m < 10 ? '0' + m : m;  
    var d = date.getDate();  
    d = d < 10 ? ('0' + d) : d;  
    return y + '' + m + '' + d;  
}; 