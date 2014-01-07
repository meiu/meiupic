<!--<div class="toolbar">
    <a href="" class="i-add">新增风格</a>
</div>-->
<div class="themelist cl">
    <?php foreach ($rows as $key => $value): ?>
    <div class="item <?php if($_G['settings']['current_theme'] == $value['id']){ echo 'using';} ?>">
        <span class="status"></span>
        <div class="thumb">
            <a href="<?php echo U('base','template','a=enable&theme='.$value['id']);?>" onclick="return switchTheme(this);" title="切换到该风格" name="<?php echo $value['name'];?>">
            <img src="<?php echo C('base_url').'templates/'.$value['id'].'/preview.jpg';?>" width="150" height="150" />
            </a>
        </div>
        <div class="title"><?php echo $value['name'];?></div>
        <div class="opt">
        <a href="<?php echo U('base','template','a=filelist&theme='.$value['id']);?>" title="编辑"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="编辑">编辑</a>
        </div>
    </div>
    <?php endforeach ?>
</div>
<script type="text/javascript">
function switchTheme(a){
    var name = $(a).attr('name');
    if($(a).parent().parent().hasClass('using')){
        return false;
    }

    art.dialog.confirm('确定要使用风格"'+name+'"么？', function () {
        var url=$(a).attr('href');
        $.post(url,{_:Math.random(),'ajaxform':1},function(data){
            ajaxAlert(data,1,false);
        },'json');
    }, function () {
    });
    return false;
}
</script>