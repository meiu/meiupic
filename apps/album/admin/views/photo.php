<link rel="stylesheet" href="<?php echo S('album','admin/css/main.css');?>" />
<div class="main-head">
    <h3>图片列表</h3>
    <a href="javascript:void(0)" onclick="NewPhotos()" class="i-add">添加图片</a>
</div>
<div class="search">
            <form method="post" action="<?php echo U('album','index');?>">
            	<?php if($search['aid']): ?>
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
            	<?php endif; ?>
                图片名：
                <input type="text" placeholder="输入图片名称" size="20" value="<?php echo $search['name'];?>" name="name">
                用户ID:
                <input type="text" placeholder="输入用户ID" size="10" value="<?php echo $search['uid'];?>" name="uid">

                <input type="submit" value="搜索" class="submit-btn">
            </form>
        </div>
<div class="innercol grid">
    <div class="gallary_wrap">
        <?php foreach ($rows as $key => $value): ?>
        <div class="gallary_item">
            <div class="item">
                <div class="pic_box">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <a href="#"><?php if($value['albumname']): ?><label><?php echo $value['albumname'];?></label><?php endif; ?><img alt="<?php echo $value['name'];?>" src="<?php echo thumb($value['path'],180,180,2);?>"></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pic_ctl">
                    <ul class="btns">
                        <li><a href="<?php echo U('album','index','a=trash&id='.$value['id']);?>" onclick="return opt_one(this,'确定移动到回收站？')" title="移动到回收站"><img src="<?php echo S('base','admin/images/b-trash.png')?>" alt="删除"></a></li>
                        <li><a href="javascript:void(0)" onclick="MuiShow('<?php echo U('album','index','a=edit&id='.$value['id']);?>','编辑图片')" title="修改"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="修改"></a></li>
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
        
        <div class="clear"></div>
    </div>
</div>
<div class="bottom_bar">
  操作: <label><input type="checkbox" onclick="$('.id_sel').prop('checked',this.checked);" /> 全选</label> 将选中项 <a href="javascript:void(0)" onclick="multi_opt('<?php echo U('album','index','a=trash&isajax=1');?>','您是要删除选定项么？')">移动到回收站</a> <a href="javascript:void(0)" onclick="multi_show('<?php echo U('album','index','a=move');?>','移动图片')">移动到相册</a>
</div>
<?php echo $pagestr; ?>
<script>
	function NewPhotos(){
		uploadWin.open('image',100,function(vReturnValue){//每次允许100张
            var ids = '';
            for(var i in vReturnValue){
                ids += vReturnValue[i].id+','
            }
            //保存为图片
            MuiShow("<?php echo U('album','index','a=add');?>&ids="+ids,'添加图片');
	    });
	}
</script>