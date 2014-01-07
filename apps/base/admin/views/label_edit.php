<script src="<?php echo S('base','ckeditor/ckeditor.js');?>"></script>
<a href="<?php echo U('base','template','a=label');?>" >&lt;&lt;返回</a><br>
<form id="submit_form" class="ajaxform" method="post">
    <div class="text_label">
        标签名：
    </div>
    <div class="text_ipt">
        <input type="text" name="name" size="20" value="<?php echo $label_info['name'];?>" />
    </div>
    <div class="text_label">
        内容：
    </div>
    <div class="text_ipt">
    <textarea id="data" name="data" class="ckeditor" rows="10" cols="90"><?php  echo $label_info['data'];?></textarea>
    </div>

    <div class="padding">
    <input type="submit" class="submit-btn" value="保存修改" />
    </div>
</form>