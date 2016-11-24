<link rel="stylesheet" href="<?php echo S('album','admin/css/main.css');?>" />
<div class="main-head">
    <h3>相册列表</h3>
    <a href="javascript:void(0)" onclick="MuiShow('<?php echo U('album','album','a=add');?>','创建相册')" class="i-add">添加相册</a>
</div>
<div class="search">
            <form method="post" action="<?php echo U('album','album');?>">
                筛选分类：
                <select name="cate_id" class="chosen">
                    <option value="">选择分类</option>
                    <?php 
                    foreach ($cates as $key => $value): ?>
                     <option value="<?php echo $value['id'];?>" <?php if($search['cate_id']==$value['id']){echo 'selected="selected"';} ?>><?php echo $value['spacer'].$value['name'];?></option>
                    <?php endforeach ?>
                </select>
                相册名：
                <input type="text" placeholder="输入相册名称" size="20" value="<?php echo $search['name'];?>" name="name">
                用户ID:
                <input type="text" placeholder="输入用户ID" size="10" value="<?php echo $search['uid'];?>" name="uid">
                <input type="submit" value="搜索" class="submit-btn">
            </form>
        </div>
<div class="innercol grid">
    <div class="gallary_wrap">
        <?php foreach ($rows as $key => $value): ?>
        <div class="gallary_item album">
            <div class="item">
                <div class="pic_box">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <a href="<?php echo U('album','photo','aid='.$value['id']);?>"><?php if($value['cover_path']): ?><img alt="<?php echo $value['name'];?>" src="<?php echo $value['cover_path'];?>"><?php else:?><img src="<?php echo S('base','images/nophoto.gif');?>" /><?php endif;?></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pic_ctl">
                    <ul class="btns">
                        <li><a href="<?php echo U('album','album','a=trash&id='.$value['id']);?>" onclick="return opt_one(this,'确定移动到回收站？')" title="移动到回收站"><img src="<?php echo S('base','admin/images/b-trash.png')?>" alt="删除"></a></li>
                        <li><a href="javascript:void(0)" onclick="MuiShow('<?php echo U('album','album','a=edit&id='.$value['id']);?>','编辑相册')" title="修改"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="修改"></a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="info">
                <div class="title">
                    <input class="id_sel selitem" name="ids[]" type="checkbox" value="<?php echo $value['id']; ?>" />
                    <span class="name"><a href="<?php echo U('album','photo','aid='.$value['id']);?>"><?php echo $value['name'];?></a></span>
                </div>
                <div class="info_col">
                    <p class="gray"><?php echo $value['photos_num'];?>张照片 ,<a href="<?php echo U('comment','album','type=album&id='.$value['id']);?>" class="gray"><?php echo $value['comments_num'];?>评论</a> ,<a href="<?php echo U('album','index','cate_id='.$value['cate_id']);?>"><?php echo $value['catename'];?></a> </p>
                    <p class="gray"><?php echo date('Y-m-d',$value['create_time']);?> By <a href="<?php echo U('album','album','uid='.$value['uid']);?>"><?php echo $value['nickname'];?></a></p>
                </div>
            </div>
        </div>
        <?php endforeach ?>
        
        <div class="clear"></div>
    </div>
</div>
<div class="bottom_bar">
  操作: <label><input type="checkbox" onclick="$('.id_sel').prop('checked',this.checked);" /> 全选</label> 将选中项 <a href="javascript:void(0)" onclick="multi_opt('<?php echo U('album','index','a=trash&isajax=1');?>','您是要删除选定项么？')">移动到回收站</a>
</div>
<?php echo $pagestr; ?>