<?php $this->display('space/mini_head.php'); ?>

<div class="mini-container">
    <div class="content">
        <form class="form-basic ajaxform" method="post">
            <div class="line-wrap">             
                <label class="label">图集名</label>
                <div class="input-wrap">
                    <input type="text" class="nomal-input" name="name" value="<?php echo $info['name'];?>" autocomplete="off" data-target="form-basic">
                    <i></i>
                </div>
            </div>
            <div class="line-wrap">
                <label class="label">图集权限</label>
                <div class="input-wrap">
                    <label><input type="checkbox" name="priv_type" value="1" <?php if($info['priv_type']==1) echo 'checked="checked"';?> /> 仅自己可见</label>
                    <i></i>
                </div>
            </div>
            <div class="line-wrap">             
                <label class="label">图集描述</label>
                <div class="input-wrap">
                    <textarea name="description" rows="5" class="nomal-textarea"><?php  echo $info['description'];?></textarea>
                    <i></i>
                </div>
            </div>
            <input type="submit" id="dosubmit" style="display:none;" value="保存修改" />
        </form>
    </div>
</div>
<?php $this->display('space/mini_foot.php'); ?>