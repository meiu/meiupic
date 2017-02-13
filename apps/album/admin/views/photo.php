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
    <h3><?php if(!empty($albumInfo)){ echo '《'.$albumInfo['name'].'》的'; } ?>图片列表</h3>
    <?php if(!empty($albumInfo)):?>
    <a href="<?php echo U('album','album');?>" style="margin-right:20px;">&lt;&lt;返回</a>
    <?php endif; ?>
    <a href="javascript:void(0)" onclick="MuiShow('<?php echo U('album','upload','aid='.$search['aid']);?>','添加图片',910,395);" class="i-add">添加图片</a>

</div>
<div class="search">
            <form method="post" action="<?php echo U('album','index');?>">
            	<?php if(!empty($albumInfo)): ?>
				<input type="hidden" name="aid" value="<?php echo $search['aid'];?>" />
            	<?php else: ?>
                筛选分类：
                <select name="cate_id" class="chosen">
                    <option value="">选择分类</option>
                    <?php 
                    foreach ($cates as $key => $value): ?>
                     <option value="<?php echo $value['id'];?>" <?php if($search['cate_id']==$value['id']){echo 'selected="selected"';} ?>><?php echo $value['spacer'].$value['name'];?></option>
                    <?php endforeach ?>
                </select>
                <label>不属于任何相册：<input type="checkbox" name="notinablum" value="1" <?php if($search['notinablum']){echo 'checked';}?> /> </label>
            	用户ID:
                <input type="text" placeholder="输入用户ID" size="10" value="<?php echo $search['uid'];?>" name="uid">
                <?php endif; ?>
                图片名：
                <input type="text" placeholder="输入图片名称" size="20" value="<?php echo $search['name'];?>" name="name">
                <input type="submit" value="搜索" class="submit-btn">
            </form>
        </div>
<div class="innercol grid">
    <div class="gallary_wrap">
        <?php if($rows): ?>
        <?php foreach ($rows as $key => $value): ?>
        <div class="gallary_item">
            <div class="item">
                <div class="pic_box">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <a href="<?php echo D($value['path']);?>" data-lightbox="images" data-title="<?php echo $value['name'];?>">
                                <?php if(isset($albumInfo) && $albumInfo['cover_id']==$value['id']): ?>
                                    <em>封面</em>
                                <?php endif; ?>
                                <?php if($value['priv_type']==1): ?>
                                    <span class="lock"></span>
                                <?php endif; ?>
                                <?php if($value['albumname']): ?>
                                    <label><?php echo $value['albumname'];?></label>
                                <?php endif; ?>
                                <img alt="<?php echo $value['name'];?>" src="<?php echo thumb($value['path'],180,180,2);?>"></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pic_ctl">
                    <ul class="btns">
                        <li><a href="<?php echo U('album','index','a=trash&id='.$value['id']);?>" onclick="return opt_one(this,'确定移动到回收站？')" title="移动到回收站"><img src="<?php echo S('base','admin/images/b-trash.png')?>" alt="删除"></a></li>
                        <li><a href="<?php echo U('album','index','a=edit&id='.$value['id']);?>" onclick="return MuiShow(this,'编辑图片')" title="修改"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="修改"></a></li>
                        <?php if(isset($albumInfo) && $albumInfo['cover_id']!=$value['id']): ?>
                        <li><a href="<?php echo U('album','index','a=setcover&id='.$value['id']);?>" onclick="return opt_one(this,'确定设为封面？')" title="设为封面"><img src="<?php echo S('base','admin/images/hascover.png')?>" alt="封面"></a></li>
                        <?php endif; ?>
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
                    <p class="gray"><?php echo $value['hits'];?>浏览 ,<a href="<?php echo U('album','index','cate_id='.$value['cate_id']);?>"><?php echo $value['catename'];?></a> </p>
                    <p class="gray"><?php echo date('Y-m-d',$value['create_time']);?> By <a href="<?php echo U('album','index','uid='.$value['uid']);?>"><?php echo $value['nickname'];?></a></p>
                </div>
            </div>
        </div>
        <?php endforeach ?>
        <?php elseif($search['aid']): ?>
            <div class="nodata">当前相册还没有任何图片！</div>
        <?php else: ?>
             <div class="nodata">没找到任何图片！</div>
        <?php endif; ?>
        <div class="clear"></div>
    </div>
</div>
<div class="bottom_bar">
  操作: <label><input type="checkbox" onclick="$('.id_sel').prop('checked',this.checked);" /> 全选</label> 将选中项 <a href="javascript:void(0)" onclick="multi_opt('<?php echo U('album','index','a=trash');?>','您是要删除选定项么？')">移动到回收站</a> <a href="javascript:void(0)" onclick="multi_show('<?php echo U('album','index','a=move');?>','移动图片',560,500)">移动到相册</a>
</div>
<?php echo $pagestr; ?>