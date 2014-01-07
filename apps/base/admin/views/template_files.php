<a href="<?php echo U('base','template') ?>" >&lt;&lt;返回风格列表</a><br>

<form class="ajaxform" method="post" action="<?php echo U('base','template','a=newfile&theme='.$theme.'&dir='.$dir);?>">
  <table cellspacing="0">
    <tbody><tr>
        <td width="350">新建文件：
          <input type="text" name="file" size="20" value="" /> <span class="gray">(不含后缀)</span> 
        </td>
        
        <?php if($dir == 'content'): ?>
        <td width="100">
            <select name="type">
            <option value="">选择类别</option>
            <?php foreach ($tpl_type as $key => $value): ?>
              <option value="<?php echo $key;?>"><?php echo $value;?></option>
            <?php endforeach ?>
            </select>
        </td>
        <?php else: ?>
            <input type="hidden" name="type" value="other" />
        <?php endif; ?>
        <td><input type="submit" value="保存" class="submit-btn"></td>
      </tr>
    </tbody>
  </table>
</form>
<div id="breadcrumb">
  当前目录：<?php echo $current_dir;?>
</div>
<form class="ajaxform" action="<?php echo U('base','template','a=rename&theme='.$theme.'&dir='.$dir);?>" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>文件</th><th>名字</th><th>类别</th><th>操作</th>
  </tr>
  <?php
  foreach ($filelist as $key => $value): 
    if($dir=='' && $value['filename'] == 'config.php') continue; //跳过配置文件
  ?>
  <tr <?php if($key%2==0):?>class="odd"<?php endif;?>> 
    <td>
      <?php if(in_array($value['type'],array('js','css','html','htm','php'))): ?>
      <img src="<?php echo S('base','admin/images/b-page.png');?>" align="absmiddle" />
      <a href="<?php echo U('base','template','a=fileedit&theme='.$theme.'&file='.$value['path']);?>" title="编辑"><?php echo $value['filename'];?></a>
      <?php elseif($value['type'] == 'dir'): ?>
      <img src="<?php echo S('base','admin/images/b-dir.png');?>" align="absmiddle" />
      <a href="<?php echo U('base','template','a=filelist&theme='.$theme.'&dir='.$value['path']);?>" title="编辑"><?php echo $value['filename'];?></a>
      <?php else: ?>
      <img src="<?php echo S('base','admin/images/b-page.png');?>" align="absmiddle" />
      <?php echo $value['filename'];?>
      <?php endif; ?>
    </td>
    <td>
      <?php if($value['filename'] != '..'): ?>
      <input name="file_explan[<?php echo $value['filename'];?>]" type="text" value="<?php echo isset($value['file_explan'])?$value['file_explan']:'';?>" />
      <?php endif; ?>
    </td>
    <td><?php echo echoType($value['tpl_type'],'template','-');?></td>
    <td> 
        <?php if(in_array($value['type'],array('js','css','html','htm','php'))): ?>
        <a href="<?php echo U('base','template','a=fileedit&theme='.$theme.'&file='.$value['path']);?>" title="编辑"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="编辑"></a>
        <a href="<?php echo U('base','template','a=filedel&theme='.$theme.'&file='.$value['path']);?>" onclick="return del_one(this,'确定要删除该文件么？');" title="删除"><img src="<?php echo S('base','admin/images/b-del.png')?>" alt="删除"></a>
        <?php endif; ?>
    </td> 
  </tr>
  <?php endforeach ?>
</tbody>
</table>
<div>
<input type="submit" class="submit-btn" value="更新名称" />
</div>
</form>