
<div class="main-head">
    <h3>用户列表</h3>
    <a href="<?php echo U('user','index','a=add');?>" class="i-add">新增用户</a>
</div>
<div class="search"><!--搜索-->
    <form method="post">
      ID/用户名/昵称/Email：<input type="text" size="20" name="keyword" value="<?php echo $search['keyword'];?>" />
      <input type="submit" class="submit-btn" value="搜索" />
    </form>
</div>
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>编号</th><th>登录名</th><th>昵称</th><th>Email</th><th>积分</th><th>注册时间</th><th>注册ip</th><th>最后登录</th><th>最后登录ip</th><th>权限</th><th>操作</th>
  </tr>
  <?php
  foreach ($rows as $key => $value): ?>
  <tr <?php if($key%2==0):?>class="odd"<?php endif;?>> 
    <td>
      <?php echo $value['id']; ?>
    </td>
    <td>
      <?php echo $value['username']; ?>
    </td>
    <td>
      <?php echo $value['nickname']; ?>
    </td>
    <td>
      <?php echo $value['email']; ?>
    </td>
    <td>
      <?php echo $value['points']; ?>
    </td>
    <td>
      <?php echo date('Y-m-d H:i',$value['regtime']); ?>
    </td>
    <td>
      <?php echo $value['regip']; ?>
    </td>
    <td>
      <?php echo date('Y-m-d H:i',$value['logintime']); ?>
    </td>
    <td>
      <?php echo $value['loginip']; ?>
    </td>
    <td>
      <?php 
      if($value['level']=='99'){
        echo '超级管理员';
      }elseif($value['level']=='88'){
        echo '管理员';
      }else{
        echo $value['level']; 
      }
      ?>
    </td>
    <td> 
        <a href="<?php echo U('user','index','a=edit&id='.$value['id']);?>" title="编辑"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="编辑"></a>
        <a href="<?php echo U('user','index','a=del&id='.$value['id']);?>" onclick="return del_one(this,'确定要删除该用户么？');" title="删除"><img src="<?php echo S('base','admin/images/b-del.png')?>" alt="删除"></a>
    </td> 
  </tr>
  <?php endforeach ?>
</tbody>
</table>
<?php echo $pagestr; ?>