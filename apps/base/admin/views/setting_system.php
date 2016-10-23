<div class="main-head">
    <h3>系统信息及缓存</h3>
</div>
<table>
    <tbody>
        <tr class="th">
            <th colspan="2">缓存管理</th>
        </tr>
        <tr>
            <td colspan="2">
                <form style="padding:10px;" action="<?php echo U('base','setting','a=clearcache');?>" method="post" class="ajaxform">
                    <input type="submit" class="submit-btn" value="清空缓存" />
                </form>
            </td>
        </tr>
        <tr class="th">
            <th colspan="2">系统信息</th>
        </tr>
        <tr>
            <td>
                服务器
            </td>
            <td>
                <?php echo php_uname("s");?>
            </td>
        </tr>
        <tr>
            <td>
                PHP 版本
            </td>
            <td>
                <?php echo PHP_VERSION;?>
            </td>
        </tr>
    </tbody>
</table>