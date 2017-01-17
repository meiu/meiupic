<link rel="stylesheet" href="<?php echo S('album','admin/css/main.css');?>" />

<form action="" method="post">
    筛选分类：
    <select name="cate_id" class="chosen">
        <option value="">请选择分类</option>
        <?php foreach ($cates as $key => $value): ?>
            <option value="<?php echo $value['id'];?>" <?php if($search['cate_id']==$value['id']){ echo 'selected="selected"';}?>><?php echo $value['spacer'].$value['name'];?></option>
        <?php endforeach ?>
    </select>
    相册名：
    <input type="text" name="name" value="<?php echo $search['name'];?>" />
    <input type="submit" value="搜索" class="submit-btn" />
</form>
<form id="submit_form" action="<?php echo U('album','index','a=domove');?>" class="ajaxform" method="post">
    <div class="innercol grid">
    <div class="gallary_wrap">
        <?php foreach ($rows as $key => $value): ?>
            <div class="gallary_item album mini">
                <div class="item">
                    <div class="pic_box">
                        <table>
                            <tbody>
                            <tr>
                                <td>
                                    <a href="javascript:void(0)"><em><?php echo $value['photos_num'];?> 张</em><?php if($value['cover_path']): ?><img alt="<?php echo $value['name'];?>" src="<?php echo thumb($value['cover_path'],180,180,2);?>"><?php else:?><img src="<?php echo S('base','images/nophoto.gif');?>" /><?php endif;?></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="info">
                    <div class="title">
                        <label><input class="id_sel selitem" name="album_id" type="radio" value="<?php echo $value['id']; ?>" />
                        <?php echo $value['name'];?>
                        </label>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
            
            <div class="clear"></div>
        </div>
    </div>
    <?php echo $pagestr; ?>
    <input type="hidden" name="ids" value="<?php echo $search['ids'];?>" />
    <input type="submit" id="dosubmit" style="display:none;" value="保存修改" />
</form>
<script>
    $('.gallary_item a').click(function(){
        $(this).closest('.gallary_item').find('input[type="radio"]').click();
    });
</script>