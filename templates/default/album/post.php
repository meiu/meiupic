<?php $this->display('common/head.php'); ?>
<script src="<?php echo S('base','tageditor/jquery.caret.min.js');?>"></script>
<script src="<?php echo S('base','tageditor/jquery.tag-editor.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo S('base','tageditor/jquery.tag-editor.css');?>" />
<script type="text/javascript" src="<?php echo S('base','plupload/plupload.full.min.js'); ?>"></script>
<script src="<?php echo S('base','js/jquery.dragsort.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo ST('css/album.css')?>" type="text/css" />
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
<script>
    initPostUpload('<?php echo U("album","uploadprocess")?>'<?php if(@$_G['settings']['album_resize_img'] && @$_G['settings']['album_pre_resize_img']){ echo ",".$_G['settings']['album_resize_img_w'].','.$_G['settings']['album_resize_img_h'].',90';}?>)
</script>
<script type="text/javascript">
$(function(){
    $('.photo-list').on('click','a.icon-close',function(){
        $(this).closest('li').remove();
    });
    $(".photo-list").dragsort({ dragSelector: "li.photo-item",itemSelector:"li.photo-item",placeHolderTemplate: '<li class="photo-item"></li>' });
    $('#tags').tagEditor({ placeholder: '输入标签...' });
});
</script>
</body>
</html>