<script src="<?php echo S('base','tageditor/jquery.caret.min.js');?>"></script>
<script src="<?php echo S('base','tageditor/jquery.tag-editor.min.js');?>"></script>
<script src="<?php echo S('base','jquery-ui/jquery-ui.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo S('base','tageditor/jquery.tag-editor.css');?>" />
<link rel="stylesheet" href="<?php echo S('base','jquery-ui/jquery-ui.min.css');?>" />
<form id="submit_form" class="ajaxform" method="post" style="width:600px;height:400px;">
    <table class="edit_table">
        <tr>
            <th>相册名：</th>
            <td><input type="text" name="name" size="40" value="<?php echo $info['name'];?>" /></td>
        </tr>
        <tr>
            <th>所属分类：</th>
            <td><select name="cate_id" class="chosen">
            <option value="">请选择分类</option>
            <?php foreach ($cates as $key => $value): ?>
                <option value="<?php echo $value['id'];?>" <?php if($value['id'] == $info['cate_id']){ echo 'selected="selected"';} ?>><?php echo $value['spacer'].$value['name'];?></option>
            <?php endforeach ?>
        </select></td>
        </tr>
        <tr>
        	<th>相册权限：</th>
        	<td>
             <label><input type="checkbox" name="priv_type" value="1" <?php if($info['priv_type']==1) echo 'checked="checked"';?> /> 仅自己可见</label>
            </td>
        </tr>
        <tr>
            <th>相册描述：</th>
            <td><textarea name="description" rows="5" cols="55"><?php  echo $info['description'];?></textarea></td>
        </tr>
        <tr>
            <th>关键字：</th>
            <td><input id="tags" name="tags" type="text" value="<?php echo $info['tags'];?>" size="50"></td>
        </tr>
        <tr>
        	<th>启用评论：</th>
        	<td><label><input type="checkbox" name="enable_comment" value="1" <?php if($info['enable_comment']){ echo 'checked="checked"';} ?> /> 是</label></td>
        </tr>
        <tr>
            <th>是否推荐：</th>
            <td><label><input type="checkbox" name="recommended" value="1" <?php if($info['recommended']){ echo 'checked="checked"';} ?> /> 是</label></td>
        </tr>
    </table>
    <input type="submit" id="dosubmit" style="display:none;" value="保存修改" />
</form>

<script type="text/javascript">
$(function(){
    $('#tags').tagEditor({ placeholder: '输入标签...',autocomplete: { 'source': '<?php echo U('album','tags','a=gettags');?>', minLength: 1} });
});
</script>