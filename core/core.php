<?php
defined('IN_MWEB') or die('access denied');

date_default_timezone_set ('Asia/Shanghai');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(dirname(__FILE__)).DS);
define('CORE_PATH',ROOT_DIR.'core'.DS);
define('DATA_PATH',ROOT_DIR.'data'.DS);

define('CURRENT_TIME',isset($_SERVER['REQUEST_TIME'])?$_SERVER['REQUEST_TIME']:time());

define('MAGIC_GPC',get_magic_quotes_gpc());
defined('MEMORY_LIMIT_ON') or define('MEMORY_LIMIT_ON', true);

defined('LOG_PATH') or define('LOG_PATH', DATA_PATH.'logs'.DS); //项目日志目录
/* 日志的默认设置 */
defined('LOG_RECORD') or define('LOG_RECORD',false);// 默认不记录日志
defined('LOG_TYPE') or define('LOG_TYPE',3);// 日志记录类型 0 系统 1 邮件 3 文件 4 SAPI 默认为文件方式
defined('LOG_DEST') or define('LOG_DEST','');// 日志记录目标
defined('LOG_EXTRA') or define('LOG_EXTRA','');// 日志记录额外信息
defined('LOG_LEVEL') or define('LOG_LEVEL','EMERG,ALERT,CRIT,ERR');// 允许记录的日志级别
defined('LOG_FILE_SIZE') or define('LOG_FILE_SIZE',2097152);// 日志文件大小限制


/*这里是一些核心函数*/
require_once(CORE_PATH.'common.php');
/**
 * 获取核心配置
 * 多级可以用.分隔
 */
function C($name,$default=''){
    static $config=array();

    if(!$config){
        $config = include(DATA_PATH.'config.php');
    }

    $delimiter='.';
    if (false === strpos($name, $delimiter)) {
        return isset($config[$name]) ? $config[$name] : $default;
    }
    $name = explode($delimiter, $name);

    $ret = $config;
    foreach ($name as $key) {
        if (!isset($ret[$key])) return $default;
        $ret = $ret[$key];
    }

    return $ret;
}
/**
 * 设置和获取统计数据
 * 使用方法:
 * <code>
 * N('db',1); // 记录数据库操作次数
 * N('read',1); // 记录读取次数
 * echo N('db'); // 获取当前页面数据库的所有操作次数
 * echo N('read'); // 获取当前页面读取次数
 * </code> 
 * @param string $key 标识位置
 * @param integer $step 步进值
 * @return mixed
 */
function N($key, $step=0) {
    static $_num    = array();
    if (!isset($_num[$key])) {
        $_num[$key] = 0;
    }
    if (empty($step))
        return $_num[$key];
    else
        $_num[$key] = $_num[$key] + (int) $step;
}
//载入模型
function M($table){
    static $models=array();
    if(isset($models[$table])){
        return $models[$table];
    }

    $obj = new Model($table);
    $models[$table] = $obj;
    return $obj;
}
//检查并解析路由
function checkRoute($url){
    $enable_rewrite = getSetting('enable_rewrite',false);
    $enable_route = getSetting('enable_route',false);
    $rewrite_style = getSetting('rewrite_style',false);

    if(!$enable_rewrite){
        return false;
    }
    
    if(!$enable_route){
        $routes = array();
    }else{
        $routes_setting = include(DATA_PATH.'routes.php');
        $routes = $routes_setting['data'];
    }

    foreach ($routes as $key => $value) {
        $need_replace = $value['needreplace'];
        $rule = $value['regex'];
        if(preg_match($rule, $url, $matches)){
            foreach($need_replace as $k=>$v){
                $v = ltrim($v,':');
                $value['params'][$v] = $matches[$k+1];
            }
            foreach ($value['params'] as $kk => $vv) {
                $_GET[$kk] = $vv;
                if(!isset($_REQUEST[$kk])){
                    $_REQUEST[$kk] = $vv;
                }
            }
            return true;//匹配到了
        }
    }
    if($rewrite_style == 1){
        //匹配默认的路由规则
        $params = explode('/', $url);
        $shortid = false;
        foreach( $params as $p => $v ){
            switch($p){
                case 0:$_GET['app']=$v;break;
                case 1:$_GET['m']=$v;
                    if(empty($_GET['m'])){
                        $_GET['m']='index';
                    }
                    break;
                case 2: 
                    if(((int) $v)>0){
                        $_GET['id']=$v;

                        $shortid = true;
                        break;
                    }
                default:
                    if($shortid && $p%2==0){
                        break;
                    }elseif(!$shortid && $p%2!=0){
                        break;
                    }
                    $_GET[$v] = $params[$p+1];
                    if(!isset($_REQUEST[$v])){
                        $_REQUEST[$v] = $params[$p+1];
                    }
                    break;
            }
        }
    }elseif($rewrite_style == 2){
        $params = explode('/', $url);
        if($params[0]){
            $_GET['app'] = $params[0];
        }
        if(isset($params[1])){
            $_GET['m'] = $params[1];
        }
    }elseif($rewrite_style == 3){
        $params = explode('/', $url);
        if($params[0] && isset($params[1])){
            $_GET['app'] = $params[0];
            $_GET['m'] = $params[1];
        }
        if(isset($params[2]) && $params[2]){
            $pars = explode('-',$params[2]);
            $shortid = false;
            foreach( $pars as $p => $v ){
                switch($p){
                    case 0: 
                        if(((int) $v)>0){
                            $_GET['id']=$v;

                            $shortid = true;
                            break;
                        }
                    default:
                        if($shortid && $p%2==0){
                            break;
                        }elseif(!$shortid && $p%2!=0){
                            break;
                        }
                        $_GET[$v] = $pars[$p+1];
                        if(!isset($_REQUEST[$v])){
                            $_REQUEST[$v] = $pars[$p+1];
                        }
                        break;
                }
            }
        }
    }
    return true;
}
//根据路由替换URL
function replaceRoute($app,$action,$params,$extra){
    static $routes = array();
    if(!$routes){
        $routes = include(DATA_PATH.'routes.php');
    }

    if(!$routes['data']){
        return false;
    }
    if(!in_array($app.'.'.$action, $routes['mods'])){
        return false;
    }

    $oldparams = $params;
    $params = $params+$extra;

    $params['app'] = $app;
    $params['m'] = $action;

    $matched_rule = '';
    $repalce_arr = array();

    $flag = false;
    foreach ($routes['data'] as $key => $rule) {
        if($rule['params']['app'] != $params['params']['app'] && $rule['params']['m'] != $params['m']){//如果app及m不匹配直接退出
            continue;
        }
        $need_replace = $rule['needreplace'];
        $flag = true;
        foreach($rule['params'] as $k=>$r){
            if($r==':@'){
                continue;
            }
            if(!isset($params[$k]) || $params[$k]===''){
                $flag = false;
                break;
            }
            if($r!='@@' && $params[$k]!=$r){
                $flag = false;
                break;
            }
        }
        if($flag){
            $matched_rule = $key;
            $repalce_arr = $need_replace;
            break;
        }
    }

    if($flag){
        $url = $matched_rule;
        foreach ($repalce_arr as $value) {
            $key = ltrim($value,':');
            $url = str_replace('{'.$value.'}', $params[$key], $url);
            if(isset($oldparams[$key])){
                unset($oldparams[$key]);
            }
        }
        if(isset($oldparams['id']) && $repalce_arr){//TODO: 可能id不是唯一需要过滤的
            unset($oldparams['id']);
        }
        $querystr = http_build_query($oldparams);
        return $url.($querystr?'?'.$querystr:'');
    }
    return false;
}

//生成URL
function U($app,$action,$param = array(),$extraparam=array(),$entry = 'default'){
    $base_url = C('base_url');
    $url = $base_url.'index.php';
    $is_admin = false;
    $enable_rewrite = getSetting('enable_rewrite',false);
    $rewrite_style = getSetting('rewrite_style',false);
    $enable_route = getSetting('enable_route',false);

    if(ADMINPAGE && $entry=='default'){
        $url = $base_url.'admin.php';
        $is_admin = true;
    }elseif($entry == 'admin'){
        $url = $base_url.'admin.php';
        $is_admin = true;
    }else{
        if($app=='base' && $action=='index'){
            $url = $base_url;
        }
        if(!is_array($param)){
            parse_str($param,$param);
        }
        if($enable_rewrite && $enable_route){
            $repalced_url = replaceRoute($app,$action,$param,$extraparam);
            if($repalced_url){
                return $base_url.$repalced_url;
            }
        }
    }

    if($enable_rewrite && !$is_admin){
        if($rewrite_style == 1){
            $str = '';
            foreach($param as $k=>$v){
                $str .= '/'.$k.'/'.$v;
            }
            $str=preg_replace('/^\/id\//','/',$str);
            
            if($app=='base' && $action=='index' && empty($str)){
                $ac = '';
            }else{
                $ac = $app.'/'.$action;
            }
            
            $url = $base_url.$ac.$str;
        }elseif($rewrite_style == 2){
            if($app=='base' && $action=='index'){
                $url = $base_url;
            }else{
                $url = $base_url.$app.'/'.$action;
            }
            if(is_array($param)){
                $param_str = http_build_query($param);
            }else{
                $param_str = $param;
            }
            $url .= ($param_str?'?'.$param_str:'');
        }elseif($rewrite_style == 3){
            $str = '';
            foreach($param as $k=>$v){
                $str .= '-'.$k.'-'.$v;
            }
            $str=preg_replace('/^\-id\-/','-',$str);
            
            if($app=='base' && $action=='index' && empty($str)){
                $ac = '';
            }else{
                $ac = $app.'/'.$action.'/';
            }
            
            $url = $base_url.$ac.ltrim($str,'-');
        }
    }else{
        if($app=='base' && $action=='index'){
            $url .= '';
        }elseif($app == 'base'){
            $url .= '?m='.$action;
        }else{
            $url .= '?app='.$app.'&m='.$action;
        }
        
        if(is_array($param)){
            $param_str = http_build_query($param);
        }else{
            $param_str = $param;
        }
        $url .= ($param_str?(strpos($url,'?')!==false?'&':'?').$param_str:'');
    }
    return $url;
}

//单例加载App
function app($app){
    static $apps=array();
    if(isset($apps[$app])){
        return $apps[$app];
    }

    $path = ROOT_DIR.'apps/'.$app.'/';
    $appClassName = ucfirst($app).'Class';
    if(!class_exists($appClassName,false)){
        require_once $path.'/'.strtolower($app).'.class.php';
    }
    if(!class_exists($appClassName,false)){
        return false;
    }
    $obj = new $appClassName();
    $apps[$app] = $obj;
    return $obj;
}
/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function toGuidString($mix) {
    if (is_object($mix) && function_exists('spl_object_hash')) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }
    return md5($mix);
}
/**
 * 销毁所有不允许的全局变量
 */
function unsetGlobals() {
  if (ini_get('register_globals')) {
    $allowed = array('_ENV' => 1, '_GET' => 1,'_SESSION'=>1, '_POST' => 1, '_COOKIE' => 1, '_FILES' => 1, '_SERVER' => 1, '_REQUEST' => 1, 'GLOBALS' => 1);
    foreach ($GLOBALS as $key => $value) {
      if (!isset($allowed[$key])) {
        unset($GLOBALS[$key]);
      }
    }
  }
}


/*输出错误*/
function trace($value='',$label='',$level='DEBUG') {
    if(APP_DEBUG && $level!='INFO') {//调试模式ERR抛出异常
        include(CORE_PATH.'tpls/error.php');
        exit;
    }
    $info = '['.$label.'] '.$value;
    log::record($info,$level,LOG_RECORD);
    
    log::save();
    if( in_array($level,explode(',', 'EMERG,ALERT,CRIT,ERR')) ){
        exit;
    }
}

/**
 * 递归实现对数组加入转义
 *
 * @param array $arr 
 * @return array
 * @author 保 灵
 */
function arrAddslashes($arr){
    if(is_array($arr)){
        return array_map('arrAddslashes',$arr);
    }else{
        return addslashes($arr);
    }
}
/**
 * 递归实现去除数组转义
 *
 * @param array $arr 
 * @return array
 * @author 保 灵
 */
function arrStripslashes($arr){
    if(is_array($arr)){
        return array_map('arrStripslashes',$arr);
    }else{
        return stripslashes($arr);
    }
}

function isPost(){
    if(strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
        return true;
    }
    return false;
}

function getGet($key,$default=''){
    if(isset($_GET[$key])){
        if(!MAGIC_GPC)
        {
            return arrAddslashes($_GET[$key]);
        }
        return $_GET[$key];
    }
    return $default;
}

function getPost($key,$default=''){
    if(isset($_POST[$key])){
        if(!MAGIC_GPC)
        {
            return arrAddslashes($_POST[$key]);
        }
        return $_POST[$key];
    }
    return $default;
}

function getCookie($key,$default=''){
    if(isset($_COOKIE[$key])){
        if(!MAGIC_GPC)
        {
            return arrAddslashes($_COOKIE[$key]);
        }
        return $_COOKIE[$key];
    }
    return $default;
}

function getRequest($key,$default=''){
    if(isset($_REQUEST[$key])){
        if(!MAGIC_GPC)
        {
            return arrAddslashes($_REQUEST[$key]);
        }
        return $_REQUEST[$key];
    }
    return $default;
}

function getPosts(){
    if(!MAGIC_GPC)
    {
        return arrAddslashes($_POST);
    }
    return $_POST;
}

function getRequests(){
    if(!MAGIC_GPC)
    {
        return arrAddslashes($_REQUEST);
    }
    return $_REQUEST;
}

function getGets(){
    if(!MAGIC_GPC)
    {
        return arrAddslashes($_GET);
    }
    return $_GET;
}
function getCookies(){
    if(!MAGIC_GPC)
    {
        return arrAddslashes($_COOKIE);
    }
    return $_GET;
}
//自动加载
function __autoload($className) {
    if (class_exists($className, false) || interface_exists($className, false)) {
        return true;
    }
    $path = CORE_PATH.'class/'.strtolower($className).'.class.php';
    if(file_exists($path)){
        require_once $path;
    }else{
        trace("Class $className not found!",'Core','ERR');
    }
    $ret = (class_exists($className, false) || interface_exists($className, false));
    if(!$ret){
        trace("Class $className not found!",'Core','ERR');
    }
    return true;
}
//运行前台部分
function run(){
    global $_G;

    G('begin');

    @session_start();

    //初始化基本设置
    $_G['settings'] = app('base')->getAllSettings();

    //初始化缓存
    $cache = Cache::instance();
    //判断是否开启缓存
    $q = getGet('q');
    $html_cache_opened = $_G['settings']['html_cache_time'] > 0 && preg_match('/(\.html|\.htm)$/', $q);
    $html_cache_key = md5('html_cache_'.$q);
    if($html_cache_opened){//页面需要缓存
        $cache_content = $cache->get($html_cache_key);
        if($cache_content){//如果缓存命中
            echo $cache_content;
            exit;
        }
        ob_start();
    }
    
    //当前启用的风格
    $current_theme = $_G['settings']['current_theme'];
    $view_dir = ROOT_DIR.'templates'.DS.$current_theme;
    
    //初始化视图
    $view = new View(array('views_dir'=>$view_dir));
    $_G['runtime']['view'] = $view;//将该对象加入到全局变量中的运行时
    $_G['runtime']['cache'] = $cache;

    if($q){
        if(checkRoute($q)){
            unset($_GET['q']);
            unset($_REQUEST['q']);
        }else{
            show404();
        }
    }

    //初始化用户登录状态
    $_G['user'] = app('user')->init();

    $app = getGet('app')?getGet('app'):'base';
    $m = getGet('m')? getGet('m'):'index';

    //过滤非法的字符
    $app = preg_replace('/[^a-z0-9\-_]/is', '', $app);
    $m = preg_replace('/[^a-z0-9\-_]/is', '', $m);

    $_G['uri'] = array(
        'app' => $app,
        'm' => $m
    );
    $_G['get'] = $_GET;

    $file_path = ROOT_DIR.'apps'.DS.$app.DS.'site'.DS.$m.'.php';

    if(file_exists($file_path)){
        include($file_path);
    }else{
        show404();
    }

    if($html_cache_opened){
        $cache_content = ob_get_clean();
        echo $cache_content;
        $cache->set($html_cache_key,$cache_content,array('life_time'=>$_G['settings']['html_cache_time']));
    }
}
//执行后台
function runAdmin(){
    global $_G;

    @session_start();
    G('begin');
    $app = getGet('app')?getGet('app'):'base';
    $m = getGet('m')? getGet('m'):'index';

    //过滤非法的字符
    $app = preg_replace('/[^a-z0-9\-_]/is', '', $app);
    $m = preg_replace('/[^a-z0-9\-_]/is', '', $m);
    
    $_G['user'] = app('user')->init();
    $_G['uri'] = array(
        'app' => $app,
        'm' => $m
    );
    $_G['settings'] = app('base')->getAllSettings();

    if(!app('user')->checkAdminSession($_G['user']) && !($app=='user' && $m == 'login') && !($app=='base' && $m == 'captcha')){
        redirect(U('user','login'));
    }

    $file_path = ROOT_DIR.'apps'.DS.$app.DS.'admin'.DS.'actions'.DS.$m.'.php';
    $view_dir = ROOT_DIR.'apps'.DS.$app.DS.'admin'.DS.'views';

    //初始化缓存
    $cache = Cache::instance();
    //初始化视图
    $view = new View(array('views_dir'=>$view_dir));

    //修饰器：后台页面的主题框架
    $view->decorate(
        new View(
            array(
                'views_dir'=>ROOT_DIR.'apps'.DS.'base'.DS.'admin'.DS.'views'
            )
        ),
        '_tpl.php');

    $_G['runtime']['view'] = $view;//将该对象加入到全局变量中的运行时
    $_G['runtime']['cache'] = $cache;
    
    $view->assign('current_menu',$_G['uri']['app'].'_'.$_G['uri']['m']);

    if(file_exists($file_path)){
        include($file_path);
    }else{
        trace('404 Page Not Found!','ROUTE','ERR');
    }
}