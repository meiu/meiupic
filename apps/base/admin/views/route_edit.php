<a href="<?php echo U('base','setting','a=route');?>" >&lt;&lt;返回</a><br>
 
<div class="notice">
  注：路由可以使用匹配参数,如 articles/{dirname}/{id}.html。映射参数格式为app=app&m=m&key1=value1&key2=value2,其中"app"及"m"是必须的！
</div>
<form id="submit_form" class="ajaxform" method="post">
    <div class="text_label">
        路由：
    </div>
    <div class="text_ipt">
        <input type="text" name="route" size="60" value="<?php echo $route_info['route'];?>" />
    </div>
    <div class="text_label">
        映射参数：
    </div>
    <div class="text_ipt">
        <input type="text" name="params" size="60" value="<?php echo $route_info['params'];?>" />
    </div>
    <div><br>
    <input type="submit" class="submit-btn" value="保存" />
    </div>
</form>