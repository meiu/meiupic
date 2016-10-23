<div class="main-head">
    <h3>添加/编辑分类</h3>
    <a href="<?php echo U('album','cate');?>" class="back">&lt;&lt;返回</a>
</div>

<script src="<?php echo S('base','ckeditor/ckeditor.js');?>"></script>
<form id="submit_form" class="ajaxform" method="post">
    <table class="edit_table">
        <?php if($cateinfo['id']): ?>
        <tr>
            <th>访问路径：</th>
            <td><?php 
            $extra = array(
                'catename'=>$cateinfo['name']
            );
            if($cateinfo['dirname']){
                $extra['dirname'] =$cateinfo['dirname'];
            }
            echo U('album','cate','id='.$cateinfo['id'],$extra,'front');
            ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <th>分类名：</th>
            <td><input type="text" name="name" size="20" value="<?php echo $cateinfo['name'];?>" /></td>
        </tr>
        <tr>
            <th>目录名：</th>
            <td><input type="text" name="dirname" size="20" value="<?php echo $cateinfo['dirname'];?>" /></td>
        </tr>
        <tr>
            <th>上级分类：</th>
            <td><select name="pid">
            <option value="0">无上级</option>
            <?php foreach ($parent_cates as $key => $value): ?>
                <option value="<?php echo $value['id'];?>" <?php if($value['id'] == $pid){ echo 'selected="selected"';} ?>><?php echo $value['spacer'].$value['name'];?></option>
            <?php endforeach ?>
        </select></td>
        </tr>
        <tr>
            <th>状态：</th>
            <td>
                <label><input name="status" type="radio" value="1" <?php if($cateinfo['status']): ?>checked="checked"<?php endif; ?> /> 显示</label>
                <label><input name="status" type="radio" value="0" <?php if(!$cateinfo['status']): ?>checked="checked"<?php endif; ?> /> 隐藏</label>
            </td>
        </tr>
        <tr>
            <th>排序：</th>
            <td><input type="text" name="sort" size="20" value="<?php echo $cateinfo['sort'];?>" /></td>
        </tr>
    </table>
    <div class="padding">
    <input type="submit" class="submit-btn" value="保存修改" />
    </div>
</form>
<script type="text/javascript">
function viewfiles(){
    uploadWin.open('image',1,function(vReturnValue){
        $('#cover').val(vReturnValue.path);
    });
}
function changeCateType(){
    var typeval = $('select[name="type"]').val();
    $('#list_inputs,#index_inputs,#detail_inputs,#page_inputs').hide();
    if(typeval == 'page'){
        $('#page_inputs').show();
        $('#model_sel').hide();
        $('#edit_inputs').hide();
        return ;
    }

    $('#model_sel').show();
    $('#edit_inputs').show();
    $('#detail_inputs').show();
    if(typeval == 'list'){
        $('#list_inputs').show();
    }
    if(typeval == 'index'){
        $('#index_inputs').show();
    }
}
$(function(){
    changeCateType();
    $('select[name="type"]').change(function(){
        changeCateType();
    });

    picArea();
    tab('.tab li','.tabc');
});
</script>