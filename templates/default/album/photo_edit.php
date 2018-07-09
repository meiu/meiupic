<?php $this->display('space/mini_head.php'); ?>
<script src="<?php echo S('base','tageditor/jquery.caret.min.js');?>"></script>
<script src="<?php echo S('base','tageditor/jquery.tag-editor.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo S('base','tageditor/jquery.tag-editor.css');?>" />

<div class="mini-container">
    <div class="content">
        <form class="form-basic ajaxform" method="post">
            <div class="line-wrap">             
                <label class="label">图片名</label>
                <div class="input-wrap">
                    <input type="text" class="nomal-input" name="name" value="<?php echo $info['name'];?>" autocomplete="off" data-target="form-basic">
                    <i></i>
                </div>
            </div>
            <div class="line-wrap">
                <label class="label">所属分类</label>
                <div class="input-wrap">
                    <select name="cate_id" class="chosen">
                        <option value="">请选择分类</option>
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
                    <label><input type="checkbox" name="priv_type" value="1" <?php if($info['priv_type']==1) echo 'checked="checked"';?> /> 仅自己可见</label>
                    <i></i>
                </div>
            </div>
            <div class="line-wrap">             
                <label class="label">图片描述</label>
                <div class="input-wrap">
                    <textarea name="description" rows="5" class="nomal-textarea"><?php  echo $info['description'];?></textarea>
                    <i></i>
                </div>
            </div>
            <div class="line-wrap">             
                <label class="label">关键字</label>
                <div class="input-wrap">
                    <input id="tags" class="nomal-input" name="tags" type="text" value="<?php echo $info['tags'];?>" size="50">
                    <i></i>
                </div>
            </div>

            <input type="submit" id="dosubmit" style="display:none;" value="保存修改" />
        </form>
    </div>
</div>
<script>
$(function(){
    $('#tags').tagEditor({ placeholder: '输入标签...' });
});
</script>
<?php $this->display('space/mini_foot.php'); ?>