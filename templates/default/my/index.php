<?php $this->display('my/head.php'); ?>
<div>
<?php 
$applist = app('my')->getAppMenus();

foreach($applist as $value){
    $filepath = ROOT_DIR.'apps'.DS.$value['id'].DS.'site'.DS.'my.inc.php';
    if(file_exists($filepath)){
        include($filepath);
    }
}
?>
</div>
<?php $this->display('my/foot.php'); ?>