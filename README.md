MeiuPic 3.0
=======

# 关于
MeiuPic升级为多用户相册系统了！可以轻松构建图虫、500px之类的图片社区。

> 目前该版本仅仅达到基本可用，想要尝鲜的朋友，可以尝试安装体验一下。不推荐直接部署到正式环境中使用。

## 安装说明


1. 下载源代码，可通过以下两种方式：
    - GitHub: 直接下载zip格式的即可
    - 使用Git: git clone git@github.com:meiu/meiupic.git
2. 在服务器中手动创建数据库，并将`install/install.sql`导入到数据库中。
3. 上传代码至服务器。
4. 修改配置文件 `data/config.php`,设置您的数据库相关配置：
```php
'database' => array(
        'adapter'  => 'pdomysql',
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'dbuser'   => 'root',
        'dbpass'   => '',
        'dbname'   => 'meiupic',
        'pconnect' => false,
        'charset'  => 'utf8',
        'pre'      => ''
    ),
```
5. 启用地址重写。 
    - Apache 中如果启用了地址重写了应该自动就能生效。
    - Nginx 的地址重写规则如下：
```
location / {
    if (!-e $request_filename) {
        rewrite ^/(.*)$ /index.php?q=$1&$args last;
        break;
    }
}
```
6. 使用用户名:`admin` 密码:`admin` 体验吧。
7. 后台访问地址为: http://域名/admin/

### 注意如果您不是放在网站根目录中

1. 需要修改`data/config.php`中的
```php
    'base_url' => '/目录/',
    'admin_base' => '/目录/admin/',
    'public_url' => '/目录/public/',
    'storage' => array(
        'adapter' => 'file', //存储引擎，目前支持file,qiniu两种
        'setting' => array(
            'dirpath' => ROOT_DIR.'public/upfiles/',
            'url_pre' => '/目录/public/upfiles/'
        )
    ),
```
2. 如果使用Nginx，地址重写规则也要相应修改
```
location / {
    if (!-e $request_filename) {
        rewrite ^/目录/(.*)$ /目录/index.php?q=$1&$args last;
        break;
    }
}
```

### 如何使用七牛存储来存图片
可以通过修改配置文件直接支持七牛存储
```php
    'storage' => array(
        'adapter' => 'qiniu', //存储引擎，目前支持file,qiniu两种
        'setting' => array(
            'bucket' => 'bucket名字',
            'access_key' => '请填写七牛的access_key',
            'secret_key' => '请填写七牛的secret_key',
            'url_pre' => 'http://七牛bucket绑定的域名/',
            'area' => 'z0' //这里输入七牛的区域
        )
    ),
```

## 祝您使用愉快！
如果发现了bug，欢迎在issues中提交。