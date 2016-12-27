<form id="submit_form" class="ajaxform" method="post" style="width:500px;height:200px;">
    <table class="edit_table">
        <tr>
            <th>相册分类：</th>
            <td><select name="cate_id">
            <option value="">请选择分类</option>
            <?php foreach ($cates as $key => $value): ?>
                <option value="<?php echo $value['id'];?>"><?php echo $value['spacer'].$value['name'];?></option>
            <?php endforeach ?>
        </select></td>
        </tr>
        <tr>
            <th>选择相册：</th>
            <td><select name="cate_id">
            <option value="">请选择相册</option>
            <?php foreach ($cates as $key => $value): ?>
                <option value="<?php echo $value['id'];?>"><?php echo $value['spacer'].$value['name'];?></option>
            <?php endforeach ?>
        </select></td>
        </tr>
    </table>
    <input type="submit" id="dosubmit" style="display:none;" value="保存修改" />
</form>