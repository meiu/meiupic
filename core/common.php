<?php
/**
 * 记录和统计时间（微秒）和内存使用情况
 * 使用方法:
 * <code>
 * G('begin'); // 记录开始标记位
 * // ... 区间运行代码
 * G('end'); // 记录结束标签位
 * echo G('begin','end',6); // 统计区间运行时间 精确到小数后6位
 * echo G('begin','end','m'); // 统计区间内存使用情况
 * 如果end标记位没有定义，则会自动以当前作为标记位
 * 其中统计内存使用需要 MEMORY_LIMIT_ON 常量为true才有效
 * </code>
 * @param string $start 开始标签
 * @param string $end 结束标签
 * @param integer|string $dec 小数位或者m 
 * @return mixed
 */
function G($start,$end='',$dec=4) {
    static $_info       =   array();
    static $_mem        =   array();
    if(is_float($end)) { // 记录时间
        $_info[$start]  =   $end;
    }elseif(!empty($end)){ // 统计时间和内存使用
        if(!isset($_info[$end])) $_info[$end]       =  microtime(TRUE);
        if(MEMORY_LIMIT_ON && $dec=='m'){
            if(!isset($_mem[$end])) $_mem[$end]     =  memory_get_usage();
            return number_format(($_mem[$end]-$_mem[$start])/1024);          
        }else{
            return number_format(($_info[$end]-$_info[$start]),$dec);
        }       
            
    }else{ // 记录时间和内存使用
        $_info[$start]  =  microtime(TRUE);
        if(MEMORY_LIMIT_ON) $_mem[$start]           =  memory_get_usage();
    }
}
/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function getClientIp($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = ip2long($ip);
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

//跳转，默认header跳转，
function redirect($url,$time=0,$msg=''){
    $url = str_replace(array("\n", "\r"), '', $url);
    if(empty($msg))
        $msg  =  "This page will redirect to {$url} in {$time} seconds！";
    
    if (!headers_sent()) {
        if(0===$time) {
            header("Location: ".$url);
        }else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    }else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if($time!=0)
            $str   .=   $msg;
        exit($str);
    }
}

function alert($msg='',$ret=false,$redirect='',$others = array()){
    if(getPost('ajaxform') || getGet('isajax') || getPost('isajax')){
        $data = array();
        $data['ret'] = $ret;
        if($msg){
            $data['msg'] = $msg;
        }
        if($redirect){
            $data['redirect'] = $redirect;
        }
        $art_dialog_id = getGet('winid');
        if($art_dialog_id){
            $data['winid'] = $art_dialog_id;
        }

        $data = array_merge($data,$others);
        echo json_encode($data);
        exit;
    }else{
        header("Content-Type:text/html;charset=utf-8");
        echo '<script language="javascript">';
        if($msg){
            echo 'alert("'.$msg.'");';
        }
        if($redirect){
            echo 'top.window.location.href="'.$redirect.'";';
        }else{
            echo 'history.back();';
        }
        echo '</script>';
        exit;
    }
}

/**
 * 截取字符串
 * @param  string $string 原始字符串
 * @param  int $length    截取的长度
 * @param  string $dot    后面加点
 * @return string         截取后的
 */
function cutstr($string, $length, $dot = ' ...', $charset = 'utf-8') {
        if(strlen($string) <= $length) {
            return $string;
        }

        $pre = chr(1);
        $end = chr(1);
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);

        $strcut = '';
        if(strtolower($charset) == 'utf-8') {

                $n = $tn = $noc = 0;
                while($n < strlen($string)) {

                        $t = ord($string[$n]);
                        if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                                $tn = 1; $n++; $noc++;
                        } elseif(194 <= $t && $t <= 223) {
                                $tn = 2; $n += 2; $noc += 2;
                        } elseif(224 <= $t && $t <= 239) {
                                $tn = 3; $n += 3; $noc += 2;
                        } elseif(240 <= $t && $t <= 247) {
                                $tn = 4; $n += 4; $noc += 2;
                        } elseif(248 <= $t && $t <= 251) {
                                $tn = 5; $n += 5; $noc += 2;
                        } elseif($t == 252 || $t == 253) {
                                $tn = 6; $n += 6; $noc += 2;
                        } else {
                                $n++;
                        }

                        if($noc >= $length) {
                                break;
                        }

                }
                if($noc > $length) {
                        $n -= $tn;
                }

                $strcut = substr($string, 0, $n);

        } else {
                for($i = 0; $i < $length; $i++) {
                        $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
                }
        }

        $strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

        $pos = strrpos($strcut, chr(1));
        if($pos !== false) {
                $strcut = substr($strcut,0,$pos);
        }
        if ($dot && $strcut != $string) {
            $strcut .= $dot;
        }
        return $strcut;
}
/**
 * 认证加密
 *
 * @param String $string
 * @param String $operation
 * @param String $key
 * @param Int $expiry
 * @return String
 */
function authcode($string, $operation = 'DECODE', $key, $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
     $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
     $j = ($j + $box[$i] + $rndkey[$i]) % 256;
     $tmp = $box[$i];
     $box[$i] = $box[$j];
     $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
     $a = ($a + 1) % 256;
     $j = ($j + $box[$a]) % 256;
     $tmp = $box[$a];
     $box[$a] = $box[$j];
     $box[$j] = $tmp;
     $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
     if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
         return substr($result, 26);
     } else {
         return '';
     }
    } else {
     return $keyc.str_replace('=', '', base64_encode($result));
    }
}

function arrval($arr,$val){
    return isset($arr[$val])?$arr[$val]:'';
}
//文件后缀
function fileext($file){
    if(strpos($file,'.') === false){
        return '';
    }
    $arr = explode('.', $file);
    $ext = array_pop($arr);

    return strtolower($ext);
}
//目录内容
function dirlist($dir,$rootdir){
    $dirs = array();

    if($dir){
        $realdir = $rootdir.DS.$dir;
    }else{
        $realdir = $rootdir;
    }

    if($directory = @dir($realdir)) {
        while ($file = $directory->read()) {
            if($file==".." ) {
              $fullpath=dirname($dir);
              $dirs[] = array(
                'path'=>$fullpath,
                'filename'=>$file,
                'type' => 'dir'
              );
            }elseif($file[0] != '.'){
              $fullpath= ($dir?$dir."/":'').$file;
              $dirs[] = array(
                'path'=>$fullpath,
                'filename'=>$file,
                'type' => is_dir($realdir.DS.$file)?'dir':fileext($file)
              );
            }
        }
        $directory->close();
        usort($dirs, 'sortByFilename');
        return $dirs;
    }else{
        return array();
    }
}
//绝对静态地址
function S($app,$path){
    global $_G;
    if($app == '_tpl'){//当app为_tpl时调用当前使用的模版的url地址
        return C('base_url').'templates/'.$_G['settings']['current_theme'].'/'.$path;
    }elseif($app == '_root'){
        return C('base_url').$path;
    }
    return C('public_url').$app.'/'.$path;
}
//相对模版的静态地址
function ST($path){
    return S('_tpl',$path);
}
//绝对上传地址
function D($path){
    return C('upload.url_pre').$path;
}
//输出类别的中文名
function echoType($value,$arr,$default=''){
    if(is_array($arr)){
        if(isset($arr[$value])){
            return $arr[$value];
        }
        return $default;
    }else{
        return C('types.'.$arr.'.'.$value,$default);
    }
}
//获取配置
function getSetting($name,$is_array=false){
    return app('base')->getSetting($name,$is_array);
}

//序列化函数
function sc_encode($arr,$addslashes = false){
    $str = serialize($arr);
    return ($addslashes?addslashes($str):$str);
}
//反序列化
function sc_decode($string){
    return unserialize($string);
}


/**
 * 该函数在插件中调用,挂载插件函数到预留的钩子上
 *
 * @param string $hook
 * @param string $actionFunc
 * @return boolearn
 */
function addAction($hook, $actionFunc) {
    global $meiuHooks;
    if (!@in_array($actionFunc, $meiuHooks[$hook])) {
        $meiuHooks[$hook][] = $actionFunc;
    }
    return true;
}

/**
 * 执行挂在钩子上的函数,支持多参数 eg:doAction('post_comment', $author, $email, $url, $comment);
 *
 * @param string $hook
 */
function doAction($hook) {
    global $meiuHooks;
    $args = array_slice(func_get_args(), 1);
    if (isset($meiuHooks[$hook])) {
        foreach ($meiuHooks[$hook] as $function) {
            $string = call_user_func_array($function, $args);
        }
    }
}
/**
 * 检查插件
 */
function checkPlugin($plugin,$app) {
    if (is_string($plugin) && preg_match("/^[\w\-]+$/", $plugin) && file_exists(ROOT_DIR . '/plugins/'.$app.'/' . $plugin.'/'.$plugin.'.php')) {
        return true;
    } else {
        return false;
    }
}
/**
 * 获取插件信息
 */
function getPluginData($plugin,$app) {
    $pluginPath = ROOT_DIR . 'plugins/'.$app.'/';
    $pluginFile = $pluginPath . $plugin . '/' . $plugin . '.php';

    $pluginData = implode('', file($pluginFile));
    preg_match("/Plugin Name:(.*)/i", $pluginData, $plugin_name);
    preg_match("/Version:(.*)/i", $pluginData, $version);
    preg_match("/Plugin URL:(.*)/i", $pluginData, $plugin_url);
    preg_match("/Description:(.*)/i", $pluginData, $description);
    preg_match("/Author:(.*)/i", $pluginData, $author_name);
    preg_match("/Author URL:(.*)/i", $pluginData, $author_url);

    $setting = file_exists($pluginPath . $plugin . '/' . $plugin . '_setting.php') ? true : false;

    $plugin_name = isset($plugin_name[1]) ? strip_tags(trim($plugin_name[1])) : '';
    $version = isset($version[1]) ? strip_tags(trim($version[1])) : '';
    $description = isset($description[1]) ? strip_tags(trim($description[1])) : '';
    $plugin_url = isset($plugin_url[1]) ? strip_tags(trim($plugin_url[1])) : '';
    $author = isset($author_name[1]) ?strip_tags( trim($author_name[1])) : '';
    $author_url = isset($author_url[1]) ? strip_tags(trim($author_url[1])) : '';

    return array(
        'Name' => $plugin_name,
        'Version' => $version,
        'Description' => $description,
        'Url' => $plugin_url,
        'App' => $app,
        'Author' => $author,
        'AuthorUrl' => $author_url,
        'Setting' => $setting,
        'Plugin' => $plugin,
    );
}

//可编辑的图片列表
function editImgList($arr,$id){
    if(is_array($arr)){
        $html = '';
        foreach($arr as $value){
            $html .= '<li class="epic"><div class="outerb"><div class="img"><img src="'.D($value['img']).'" width="120" /></div></div><div class="txt"><input type="text" value="'.$value['txt'].'" name="txt_'.$id.'" /></div><input type="hidden" value="'.$value['img'].'" name="'.$id.'"> <a href="javascript:void(0)" onclick="delEpic(this,\''.$value['img'].'\')">[删除]</a>';
            $html .= '</li>';
        }
        return $html;
    }
    return '';
}

function getPicsPosts($imgs,$txts){
    $arr = array();
    foreach ($imgs as $key => $value) {
        $arr[] = array(
            'img' =>$value,
            'txt' =>isset($txts[$key])?$txts[$key]:''
        );
    }
    return sc_encode($arr);
}

//如果需要50%概率请传入50
function lucker($dot){
    $dot = intval($dot);
    $dot = max($dot,0);//min dot = 0
    $dot = min($dot,100);//max dot = 100
    $one = rand(1,100);
    $total = range(1,100);
    shuffle($total);
    $range = array();
    for($i=0; $i<$dot; $i++) {
        $range[] = $total[$i];
    }
    return in_array($one,$range);
}
/*自定义的排序*/
function sortFields($a,$b){
    //if ((int)$a['sort'] == (int)$b['sort']) return 0;
    return ((int)$a['sort'] >= (int)$b['sort']) ? 1 : -1;
}
/**/
function sortByFilename($a,$b){
    return strnatcmp($a['filename'],$b['filename']);
}

function fromArr($fields,$arr){
    $inarr = explode(',',$fields);
    foreach($arr as $k=>$v){
        if(!in_array($k,$inarr)){
            unset($arr[$k]);
        }
    }
    return $arr;
}
/**
 * 生成缩略图并返回缩略图地址
 * @param  string  $path 图片地址
 * @param  int  $w    生成的缩略图宽度
 * @param  int  $h    生成的缩略图高度
 * @param  integer $t    缩放方式 1,裁切;2,按比例缩放;3,强制缩放到某个尺寸
 * @return string 路径
 */
function thumb($path,$w=100,$h=100,$t=1,$smallpic = 'nopreview.gif'){
    $upload_setting = C('upload');
    $realpath = $upload_setting['dirpath'].$path;
    $imgurl = $upload_setting['url_pre'].$path;

    if(empty($path) || !file_exists($realpath)) return S('base','images/'.$smallpic);

    $file_ext= fileext($path);

    $newimgpath = "{$path}_{$w}_{$h}_{$t}.{$file_ext}";

    $newrealpath = $upload_setting['dirpath'].$newimgpath;
    $newimgurl   = $upload_setting['url_pre'].$newimgpath;

    if(file_exists($newrealpath)){
        return $newimgurl;
    }

    list($width_t, $height_t, $type, $attr) = getimagesize($realpath);
    if($w>=$width_t && $h>=$height_t) return $imgurl;

    $img = image::instance();

    $img->load($realpath);
    if($t == 1){
        $img->resizeCut($w,$h);
    }elseif($t == 2){
        $img->resizeScale($w,$h);
    }else{
        $img->resize($w,$h);
    }
    $img->save($newrealpath);

    //将缩略图记录到数据库
    $filesize = filesize($newrealpath);
    $insert_data = array(
        'name' => basename($newimgpath),
        'filetype' => 'image',
        'ext' => $file_ext,
        'path' => $newimgpath,
        'size' => $filesize,
        'isthumb' => 1,
        'addtime' => time()
    );
    M('upfiles')->insert($insert_data);

    return $newimgurl;
}

/**
 * 字符串过滤
 * @param  string $str 需要过滤的
 * @return string
 */
function safestr($str){
    return htmlspecialchars($str,ENT_QUOTES);
}
//模型设置里用得到
function explodeStr($delimiter,$string){
    $arr = explode($delimiter, $string);
    $ret1 = $ret2 = $arr[0];
    if(isset($arr[1])){
        $ret2 = $arr[1];
    }
    return array('k'=>$ret1,'v'=>$ret2);
}
/*相关验证*/
function isEmail($email){
    return preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $email);
}
function isMobile($value)
{
    return preg_match('/^1\d{10}$/i', $value);
}
/*相关验证部分结束*/

function sendSmtpEmail($from,$to,$title,$content){
    $email_config = C('email');

    $email_config['protocol'] = 'smtp';
    $email_config['wordwrap'] = TRUE;
    $email_config['mailtype'] = 'html';
    
    $email = new Email;

    $email->initialize($email_config)->from($from)->to($to)->subject($title)->message($content);
    return $email->send();
}
//检查权限
function checkPriv($privstr,$priv,$authorid = ''){
    global $_G;
    $cur_priv = sc_decode($privstr);
    $allow_priv = $cur_priv[$priv];
    if($allow_priv == -1){
        return true;
    }
    if(!$_G['user']){
        return false;
    }
    if($_G['user']['level'] >= $cur_priv[$priv]){
        return true;
    }
    if($priv == 'edit' && $cur_priv['author_edit'] && $_G['user']['id'] == $authorid ){
        return true;
    }
    return false;
}
//安全获取内容
function safeContent($content){
    $val = strip_tags($content,'<p><br><ol><ul><li><a><em><s><blockquote><span><strong><img>');

   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed  
   // this prevents some character re-spacing such as <java\0script>  
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs  
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);  
     
   // straight replacements, the user should never need these since they're normal characters  
   // this prevents like <IMG SRC=@avascript:alert('XSS')>  
   $search = 'abcdefghijklmnopqrstuvwxyz'; 
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';  
   $search .= '1234567890!@#$%^&*()'; 
   $search .= '~`";:?+/={}[]-_|\'\\'; 
   for ($i = 0; $i < strlen($search); $i++) { 
      // ;? matches the ;, which is optional 
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 
    
      // @ @ search for the hex values 
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ; 
      // @ @ 0{0,7} matches '0' zero to seven times  
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ; 
   } 
    
   // now the only remaining whitespace attacks are \t, \n, and \r 
   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base'); 
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'); 
   $ra = array_merge($ra1, $ra2); 
    
   $found = true; // keep replacing as long as the previous round replaced something 
   while ($found == true) { 
      $val_before = $val; 
      for ($i = 0; $i < sizeof($ra); $i++) { 
         $pattern = '/'; 
         for ($j = 0; $j < strlen($ra[$i]); $j++) { 
            if ($j > 0) { 
               $pattern .= '(';  
               $pattern .= '(&#[xX]0{0,8}([9ab]);)'; 
               $pattern .= '|';  
               $pattern .= '|(&#0{0,8}([9|10|13]);)'; 
               $pattern .= ')*'; 
            } 
            $pattern .= $ra[$i][$j]; 
         } 
         $pattern .= '/i';  
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag  
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags  
         if ($val_before == $val) {  
            // no replacements were made, so exit the loop  
            $found = false;  
         }  
      }  
   }  
   return $val;  
}
/*
获取汉字的拼音
 */
function getPinyin($str,$ishead=0,$isclose=1)
{
    global $pinyins;
    $restr = "";
    $str = trim($str);
    $str = @iconv('utf-8', 'gb2312', $str);
    $slen = strlen($str);
    if($slen<2) return $str;
    if(count($pinyins)==0){
        $fp = fopen(CORE_PATH."data/pinyin.db","r");
        while(!feof($fp)){
            $line = trim(fgets($fp));
            $pinyins[$line[0].$line[1]] = substr($line,3,strlen($line)-3);
        }
        fclose($fp);
    }
    for($i=0;$i<$slen;$i++){
        if(ord($str[$i])>0x80)
        {
            $c = $str[$i].$str[$i+1];
            $i++;
            if(isset($pinyins[$c])){
                if($ishead==0) $restr .= $pinyins[$c];
                else $restr .= $pinyins[$c][0];
            }else $restr .= "-";
        }else if( preg_match("/[a-z0-9]/i",$str[$i]) ){ $restr .= $str[$i]; }
        else{ $restr .= "-";  }
    }
    if($isclose==0) unset($pinyins);
    return $restr;
}
/**
 * 上传文件
 * @param  FILE  $name  文件域
 * @param  string  $type  类别，图片，附件等
 * @param  boolean $multi 是否是多图
 * @param  &string $error 如果有错误
 * @return string|array   上传成功后的图片地址等
 */
function uploadFile($name,$type,$multi=false,& $error){
    $names = $_FILES[$name]['name'];
    $types = $_FILES[$name]['type'];
    $sizes = $_FILES[$name]['size'];
    $tmp_names = $_FILES[$name]['tmp_name'];
    $errors = $_FILES[$name]['error'];

    //获取上传设置
    $filetype = C('upfiles.'.$type);
    if(!$filetype){
        $error = '不支持的文件类型！';
        return null;
    }

    $upload_setting = C('upload');
    $date = date('Ymd');
    $attachdir = $type.'/'.$date;
    if(!is_dir($upload_setting['dirpath'].$attachdir)){
        @mkdir($upload_setting['dirpath'].$attachdir,0755,true);
    }
    $m_upfile = M('upfiles');

    if($multi){
        $upload_files = array();
        if(!isset($_FILES[$name])){
            return $upload_files;
        }
        foreach ($names as $key => $value) {
            if(empty($value)) continue;

            $fileext = fileext($value);
            if(!in_array($fileext,explode(',', $filetype['ext']))){//如果不是支持的文件类别直接清除临时文件
                @unlink($tmp_names[$key]);
                $error = '不支持的文件格式，请上传'.$filetype['ext'].'等格式！';
                continue;
            }
            if($errors[$key]>0){
                $error = '上传文件失败！';
                continue;
            }

            $path = $attachdir.'/'.str_replace('.','',microtime(true)).rand(10,99).'.'.$fileext;
            $realpath = $upload_setting['dirpath'].$path;

            if(@move_uploaded_file($tmp_names[$key],$realpath)){
                @chmod($realpath,0755);

                $filesize = $sizes[$key];
                $insert_data = array(
                    'name' => $names[$key],
                    'filetype' => $type,
                    'ext' =>  $fileext,
                    'path' => $path,
                    'size' => $filesize,
                    'isthumb' => 0,
                    'addtime' => time()
                );
                $m_upfile->insert($insert_data);
                $upload_files[] = $path;
            }else{
              $error = '上传文件失败，可能目录权限有问题！';
              continue;
            }
        }
        return $upload_files;
    }else{
        if(empty($names)){
          $error = '没有选择上传文件！';
          return null;
        }

        $fileext = fileext($names);
        if(!in_array($fileext,explode(',', $filetype['ext']))){//如果不是支持的文件类别直接清除临时文件
            @unlink($tmp_names);
            $error = '不支持的文件格式，请上传'.$filetype['ext'].'等格式！';
            return null;
        }
        if($errors>0){
            $error = '上传文件失败！';
            return null;
        }

        $path = $attachdir.'/'.str_replace('.','',microtime(true)).rand(10,99).'.'.$fileext;
        $realpath = $upload_setting['dirpath'].$path;

        if(@move_uploaded_file($tmp_names,$realpath)){
            @chmod($realpath,0755);

            $filesize = $sizes;
            $insert_data = array(
                'name' => $names,
                'filetype' => $type,
                'ext' =>  $fileext,
                'path' => $path,
                'size' => $filesize,
                'isthumb' => 0,
                'addtime' => time()
            );
            $m_upfile->insert($insert_data);
            return $path;
        }else{
            $error = '上传文件失败，可能目录权限有问题！';
            return null;
        }
        
    }
}
/*显示相关信息*/
function showInfo($message,$link,$autoredirect=2){
    global $_G;
    $_G['runtime']['view']->assign('message',$message);
    $_G['runtime']['view']->assign('link',$link);
    $_G['runtime']['view']->assign('autoredirect',$autoredirect);

    $_G['runtime']['view']->display('common/showinfo.php');
    exit;
}
function show404(){
    global $_G;

    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found"); 

    $_G['runtime']['view']->display('common/404.php');
    exit;
}

function parse_kw($content){
    $content=preg_replace_callback("/\[kw\](.+?)\[\/kw\]/is","make_link_kw",$content); 
    return $content;
}

function make_link_kw($matches){
    $url = '/search/?kw=#kw#';
    $kw = strip_tags($matches[1]);
    $kw_len = strlen($kw);
    if( $kw_len < 30 && $kw_len > 0){
        return '<strong><a href="'.str_replace('#kw#',urlencode($kw),$url).'" target="_blank">'.$kw.'</a></strong>';
    }else{
        return $kw;
    }
}

function layout($data,$tpl){
    global $_G;
    return $_G['runtime']['view']->fetch('layout/'.$tpl.'.php',$data);
}

function hideStr($string, $bengin=0, $len = 4, $type = 0, $glue = "@") {
    if (empty($string))
        return false;
    $array = array();
    if ($type == 0 || $type == 1 || $type == 4) {
        $strlen = $length = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, "utf8");
            $string = mb_substr($string, 1, $strlen, "utf8");
            $strlen = mb_strlen($string);
        }
    }
    if ($type == 0) {
        for ($i = $bengin; $i < ($bengin + $len); $i++) {
            if (isset($array[$i]))
                $array[$i] = "*";
        }
        $string = implode("", $array);
    }else if ($type == 1) {
        $array = array_reverse($array);
        for ($i = $bengin; $i < ($bengin + $len); $i++) {
            if (isset($array[$i]))
                $array[$i] = "*";
        }
        $string = implode("", array_reverse($array));
    }else if ($type == 2) {
        $array = explode($glue, $string);
        $array[0] = hideStr($array[0], $bengin, $len, 1);
        $string = implode($glue, $array);
    } else if ($type == 3) {
        $array = explode($glue, $string);
        $array[1] = hideStr($array[1], $bengin, $len, 0);
        $string = implode($glue, $array);
    } else if ($type == 4) {
        $left = $bengin;
        $right = $len;
        $tem = array();
        for ($i = 0; $i < ($length - $right); $i++) {
            if (isset($array[$i]))
                $tem[] = $i >= $left ? "*" : $array[$i];
        }
        $array = array_chunk(array_reverse($array), $right);
        $array = array_reverse($array[0]);
        for ($i = 0; $i < $right; $i++) {
            $tem[] = $array[$i];
        }
        $string = implode("", $tem);
    }
    return $string;
}