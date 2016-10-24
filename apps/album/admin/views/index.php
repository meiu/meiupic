<link rel="stylesheet" href="<?php echo S('album','admin/css/main.css');?>" />
<div class="main-head">
    <h3>相册列表</h3>
    <a href="<?php echo U('album','index','a=add');?>" class="i-add">添加相册</a>
</div>
<div class="search">
            <form method="post" action="<?php echo U('album','index');?>">
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
                                <a href="<?php echo U('album','photo','aid='.$value['id']);?>"><img alt="<?php echo $value['name'];?>" src="<?php echo $value['cover_path'];?>"></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pic_ctl">
                    <ul class="btns">
                        <li><a href="javascript:void(0)" onclick="Mui.box.show('/demo2/index.php/albums-confirm_delete-id-10.pic',true);" title="移动到回收站"><img src="<?php echo S('base','admin/images/b-trash.png')?>" alt="删除"></a></li>
                        <li><a href="javascript:void(0)" onclick="Mui.box.show('/demo2/index.php/albums-modify-id-10.pic',true);" title="修改"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="修改"></a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="info">
                <div class="title">
                    <span class="name"><a href="<?php echo U('album','photo','aid='.$value['id']);?>"><?php echo $value['name'];?></a></span>
                </div>
                <div class="info_col">
                    <p class="gray"><?php echo $value['photos_num'];?>张照片 ,<a href="/demo2/index.php/photos-index-aid-12.pic#comments" class="gray"><?php echo $value['comments_num'];?>评论</a> ,<a href="<?php echo U('album','index','cate_id='.$value['cate_id']);?>"><?php echo $value['catename'];?></a> </p>
                    <p class="gray"><?php echo date('Y-m-d',$value['create_time']);?> By <a href="<?php echo U('album','index','uid='.$value['uid']);?>"><?php echo $value['nickname'];?></a></p>
                </div>
            </div>
        </div>
        <?php endforeach ?>
            
        <div class="clear"></div>
    </div>
</div>
<?php echo $pagestr; ?>