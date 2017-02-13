<link rel="stylesheet" href="<?php echo S('album','admin/css/main.css');?>" />
<script src="<?php echo S('base','lightbox/js/lightbox.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo S('base','lightbox/css/lightbox.min.css');?>" />
<script>
    lightbox.option({
      'albumLabel': "%1 / %2",
      'disableScrolling':true
    });
</script>
<div class="main-head">
    <h3>回收站</h3>
    <a href="<?php echo U('album','recyclebin','a=clear');?>" class="i-clear" onclick="return opt_one(this,'确定要清空回收站么？')">清空回收站</a>
</div>
<?php if($photoNum || $albumNum):?>
<div class="taba">
    <ul class="cl">
      <li <?php if($search['type']==1): ?>class="active"<?php endif; ?>><a href="<?php echo U('album','recyclebin'); ?>">相册 (<?php echo $albumNum;?>)</a></li>
      <li <?php if($search['type']==2): ?>class="active"<?php endif; ?>><a href="<?php echo U('album','recyclebin','type=2'); ?>">图片 (<?php echo $photoNum;?>)</a></li>
    </ul>
</div>
<?php endif; ?>
<div class="innercol grid">
    <div class="gallary_wrap">
        <?php if($rows): ?>
        <?php foreach ($rows as $key => $value): ?>
        <div class="gallary_item <?php if($search['type']==1): ?>album<?php endif; ?>">
            <div class="item">
                <div class="pic_box">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <?php if ($search['type']==2): ?>
                                    <a href="<?php echo D($value['path']);?>" data-lightbox="images" data-title="<?php echo $value['name'];?>">
                                    <?php if($value['priv_type']==1): ?>
                                        <span class="lock"></span>
                                    <?php endif; ?>
                                    <img alt="<?php echo $value['name'];?>" src="<?php echo thumb($value['path'],180,180,2);?>"></a>
                                <?php else: ?>
                                    <a href="<?php echo D($value['cover_path']);?>" data-lightbox="images" data-title="<?php echo $value['name'];?>">
                                    <?php if($value['priv_type']==1): ?>
                                        <span class="lock"></span>
                                    <?php endif; ?>
                                    <em><?php echo $value['photos_num'];?> 张</em>
                                    <img alt="<?php echo $value['name'];?>" src="<?php echo thumb($value['cover_path'],180,180,2);?>"></a>
                                <?php endif ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pic_ctl">
                    <ul class="btns">
                        <li><a href="<?php echo U('album','recyclebin','a=del&type='.$search['type'].'&id='.$value['id']);?>" onclick="return opt_one(this,'确定要彻底删除么？')" title="彻底删除"><img src="<?php echo S('base','admin/images/b-del.png')?>" alt="删除"></a></li>
                        <li><a href="<?php echo U('album','recyclebin','a=restore&type='.$search['type'].'&id='.$value['id']);?>" onclick="return opt_one(this,'确定要还原么？')" title="还原"><img src="<?php echo S('base','admin/images/b-return.png')?>" alt="还原"></a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="info">
                <div class="title">
                    <input class="id_sel selitem" name="ids[]" type="checkbox" value="<?php echo $value['id']; ?>" />
                    <span class="name"><?php echo $value['name'];?></span>
                </div>
                <div class="info_col">
                    <p class="gray"><?php echo date('Y-m-d',$value['create_time']);?> By <?php echo $value['nickname'];?></p>
                </div>
            </div>
        </div>
        <?php endforeach ?>
        <?php elseif(!$photoNum && !$albumNum):?>
            <div class="nodata">回收站是空的！</div>
        <?php elseif($search['type']==2): ?>
            <div class="nodata">回收站里还没有任何图片！</div>
        <?php else: ?>
            <div class="nodata">回收站里还没有任何相册！</div>
        <?php endif; ?>
        <div class="clear"></div>
    </div>
</div>
<div class="bottom_bar">
  操作: <label><input type="checkbox" onclick="$('.id_sel').prop('checked',this.checked);" /> 全选</label> 将选中项 <a href="javascript:void(0)" onclick="multi_opt('<?php echo U('album','recyclebin','a=del&type='.$search['type']);?>','您是要删除选定项么？')">彻底删除</a> <a href="javascript:void(0)" onclick="multi_opt('<?php echo U('album','recyclebin','a=restore&type='.$search['type']);?>','您是要还原选定项么？')">还原</a>
</div>
<?php echo $pagestr; ?>