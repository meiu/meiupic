<form id="submit_form" class="ajaxform" method="post" style="width:600px;height:400px;">
    <table class="edit_table">
        <tr>
            <th>相册名：</th>
            <td><input type="text" name="name" size="20" value="<?php echo $info['name'];?>" /></td>
        </tr>
        <tr>
            <th>所属分类：</th>
            <td><select name="cate_id">
            <option value="">请选择分类</option>
            <?php foreach ($cates as $key => $value): ?>
                <option value="<?php echo $value['id'];?>" <?php if($value['id'] == $info['cate_id']){ echo 'selected="selected"';} ?>><?php echo $value['spacer'].$value['name'];?></option>
            <?php endforeach ?>
        </select></td>
        </tr>
        <tr>
        	<th>相册权限：</th>
        	<td>
             <label><input type="radio" name="priv_type" value="0" onclick="$('#filed_password').hide();" <?php if($info['priv_type']==0) echo 'checked="checked"';?> /> 公开</label>
             <label><input type="radio" name="priv_type" value="1" onclick="$('#filed_password').show();" <?php if($info['priv_type']==1) echo 'checked="checked"';?> /> 密码访问</label> 
             <label><input type="radio" name="priv_type" value="2" onclick="$('#filed_password').hide();" <?php if($info['priv_type']==2) echo 'checked="checked"';?> /> 私人</label>
            </td>
        </tr>
        <tr id="filed_password" <?php if($info['priv_type']!=1){echo 'style="display:none"';} ?>>
        	<th>相册密码：</th>
        	<td>
        	<input id="password_ipt" type="password" name="priv_pass" value="<?php echo $info['priv_pass'];?>" /><label><input class="ml5" style="margin-left:10px;" type="checkbox" value="1" onclick="setMask('password_ipt',this.checked)" /> 显示密码</label></td>
        </tr>
        <tr>
            <th>相册描述：</th>
            <td><textarea name="desc" rows="10" cols="50"><?php  echo $info['desc'];?></textarea></td>
        </tr>
        <tr>
        	<th>是否显示评论：</th>
        	<td><label><input type="checkbox" name="enable_comment" value="1" <?php if($info['enable_comment']){ echo 'checked="checked"';} ?> /> 开启</label></td>
        </tr>
    </table>
    <input type="submit" id="dosubmit" style="display:none;" value="保存修改" />
</form>