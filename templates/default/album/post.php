<?php $this->display('my/head.php'); ?>
<script src="<?php echo S('base','tageditor/jquery.caret.min.js');?>"></script>
<script src="<?php echo S('base','tageditor/jquery.tag-editor.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo S('base','tageditor/jquery.tag-editor.css');?>" />
<script type="text/javascript" src="<?php echo S('base','plupload/plupload.full.min.js'); ?>"></script>
<script src="<?php echo S('base','js/jquery.dragsort.min.js');?>"></script>
<link rel="stylesheet" href="<?=ST('css/album.css')?>" type="text/css" />
<div class="post_content">
   <form class="form-basic ajaxform" method="post">
       <div class="upload_content">
           <div id="muilti_uploader">
               <ul class="photo-list">
                    <li class="upload-item">
                        <div class="upload-photo" id="pickfiles">
                            <i class="icon-add"></i>
                            <p>最大支持20MB的JPEG格式照片<br>不建议加画框和水印签名</p>
                        </div>
                    </li>
               </ul>
           </div>
       </div>
       <div class="post_pane">
            <div class="form_fields">
                <div class="line-wrap">             
                    <label class="label">标题</label>
                    <div class="input-wrap">
                        <input type="text" class="nomal-input" name="title" value="" autocomplete="off" data-target="form-basic">
                        <i></i>
                    </div>
                </div>
                <div class="line-wrap">             
                    <label class="label">所属分类</label>
                    <div class="input-wrap">
                        <select name="cate_id">
                            <option value="">未分类</option>
                            <?php foreach ($cates as $key => $value): ?>
                                <option value="<?php echo $value['id'];?>" <?php if($value['id'] == $info['cate_id']){ echo 'selected="selected"';} ?>><?php echo $value['spacer'].$value['name'];?></option>
                            <?php endforeach ?>
                        </select>
                        <i></i>
                    </div>
                </div>
                    
                <div class="line-wrap">             
                    <label class="label">图片权限</label>
                    <div class="input-wrap">
                        <label><input type="checkbox" name="priv_type" value="1" /> 仅自己可见</label>
                        <i></i>
                    </div>
                </div>
                <div class="line-wrap">             
                    <label class="label">图片描述</label>
                    <div class="input-wrap">
                        <textarea name="description" class="nomal-textarea" placeholder="说说图片中的故事" cols="30" rows="5"></textarea>
                        <i></i>
                    </div>
                </div>
                <div class="line-wrap">             
                    <label class="label">标签</label>
                    <div class="input-wrap">
                        <input id="tags" type="text" class="nomal-input" name="tags" value="" autocomplete="off" data-target="form-basic">
                        <i></i>
                    </div>
                </div>
                <div class="line-wrap">
                    <div class="input-wrap">
                        <input type="submit" class="submit-btn" value="发布">
                    </div>
                </div>
            </div>
       </div>
   </form>
</div>
<script type="text/javascript">
var uploader = new plupload.Uploader({
    runtimes : 'html5,flash,silverlight,html4',
    browse_button : 'pickfiles',
    container: $('li.upload-item').get(0),
    drop_element:'muilti_uploader',
    url : '<?=U("album","uploadprocess")?>',
    flash_swf_url : '<?php echo S("base","plupload/Moxie.swf");?>',
    silverlight_xap_url : '<?php echo S("base","plupload/Moxie.xap");?>',
    chunk_size : '1mb',
    unique_names:true,
    send_file_name:true,
    filters : {
        max_file_size : '20mb',
        mime_types: [
            {title : "Image files", extensions : "jpeg,jpg,gif,png"}
        ]
    },
    <?php if(@$_G['settings']['album_resize_img'] && @$_G['settings']['album_pre_resize_img']):?>
    resize : {width : <?php echo $_G['settings']['album_resize_img_w'];?>, height : <?php echo $_G['settings']['album_resize_img_h'];?>, quality : 90},
    <?php endif; ?>
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
    $('.photo-list').on('click','a.icon-close',function(){
        $(this).closest('li').remove();
    });
    $(".photo-list").dragsort({ dragSelector: "li.photo-item",itemSelector:"li.photo-item",placeHolderTemplate: '<li class="photo-item"></li>' });
    $('#tags').tagEditor({ placeholder: '输入标签...' });
});
</script>
</body>
</html>