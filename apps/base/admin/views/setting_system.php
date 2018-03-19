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
                服务器操作系统
            </td>
            <td>
                <?php echo php_uname("s").' '.php_uname('r');?>
            </td>
        </tr>
        <tr>
            <td>Web服务器</td>
            <td><?php echo($_SERVER["SERVER_SOFTWARE"]); ?></td>
        </tr>
        <tr>
            <td>
                PHP 版本
            </td>
            <td>
                <?php echo PHP_VERSION;?>
            </td>
        </tr>
        <tr>
            <td>PHP运行方式</td>
            <td><?php echo ucwords(php_sapi_name());?></td>
        </tr>
        <tr>
            <td>Mysql版本</td>
            <td><?php echo DB::instance()->getOne('Select version()'); ?></td>
        </tr>
        <tr>
            <td>GD</td>
            <td><?php 
            if(function_exists("gd_info")){                 
                $gd = gd_info();
                $gdinfo = $gd['GD Version'];
            }else {
                $gdinfo = "未知";
            }
            echo $gdinfo;
            ?></td>
        </tr>
        <tr>
            <td>Imagick</td>
            <td><?php
            if(class_exists('Imagick')){
                $ver = Imagick::getVersion();
                echo $ver['versionString'];
            }else{
                echo '不支持';
            }
            ?></td>
        </tr>
        <tr>
            <td>
                最大上传限制
            </td>
            <td>
                <?php
                    echo get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"不允许上传附件";
                ?>
            </td>
        </tr>
        
    </tbody>
</table>