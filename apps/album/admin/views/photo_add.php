<script src="<?php echo S('base','tageditor/jquery.caret.min.js');?>"></script>
<script src="<?php echo S('base','tageditor/jquery.tag-editor.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo S('base','tageditor/jquery.tag-editor.css');?>" />
<style>
	div.leftphotos{
		width:440px;float:left;height:360px;overflow-y:auto;margin: 10px 0 0;
	}
	div.leftphotos li{
		width:140px;float:left;margin-bottom:10px;
		text-align: left;
	}
    div.leftphotos li div.picbox{
        width: 130px;
        height: 130px;
        margin-bottom: 5px;
        overflow: hidden;
    }
	div.leftphotos li input{
		width:110px;
	}
	#submit_form table{
		width:450px;float: left;
	}
</style>
<form id="submit_form" action="<?php echo U('album','upload','a=saveinfo');?>" class="ajaxform" method="post" style="width:900px;height:370px;">
	<div class="leftphotos">
		<ul>
			<?php foreach ($photos as $key => $value): ?>
			<li>
			<div class="picbox"><img src="<?php echo U('album','upload','a=thumb&path='.urlencode($value['path']));?>" width="130"></div>
			<input name="photonames[<?php echo $value['id'];?>]" type="text" size="16" value="<?php echo $value['name'];?>">
			</li>
			<?php endforeach ?>
		</ul>
	</div>
    <table class="edit_table">
        <?php if(!$aid): ?>
        <tr>
            <th>所属分类：</th>
            <td><select name="cate_id" class="chosen">
            <option value="">未分类</option>
            <?php foreach ($cates as $key => $value): ?>
                <option value="<?php echo $value['id'];?>"><?php echo $value['spacer'].$value['name'];?></option>
            <?php endforeach ?>
        </select></td>
        </tr>
        <?php endif; ?>
        <tr>
        	<th>图片权限：</th>
        	<td>
             <label><input type="checkbox" name="priv_type" value="1" /> 仅自己可见</label>
            </td>
        </tr>
        <tr>
            <th>标题：</th>
            <td><input type="text" name="name" size="40" value="" /> <small>填写后覆盖默认名称</small></td>
        </tr>
        <tr>
            <th>图片描述：</th>
            <td><textarea name="description" rows="5" cols="35"></textarea></td>
        </tr>
        <tr>
            <th>关键字：</th>
            <td><input id="tags" name="tags" type="text" value="" size="50"></td>
        </tr>
    </table>
    <input type="submit" id="dosubmit" style="display:none;" value="确定添加" />
</form>

<script type="text/javascript">
$(function(){
    $('#tags').tagEditor({ placeholder: '输入标签...' });
});
</script>