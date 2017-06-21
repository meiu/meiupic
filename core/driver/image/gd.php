<?php
defined('IN_MWEB') || exit('Access denied!');
class ImageGd extends image{
    /**
     * 图片文件句柄
     *
     * @var image
     */
    private $image = null;

    /**
     * 图片类型
     *
     * @var imagetype
     */
    private $image_type = null;
    
    private $image_quality=90;

    private $true_color = false;

    private $supportType = null;

    /**
     * 装载图像
     *
     * @param string $filename 文件完整路径
     * @return void
     */
    public function load($filename) {
        if($this->image){
            $this->close();
        }

        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG ) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng($filename);
        }else{
            return false;
        }
        if(function_exists("imagecopyresampled") && function_exists("imagecreatetruecolor") && $this->image_type != IMAGETYPE_GIF){
            $this->true_color = true;
        }
        return true;
    }
    
    public function supportType(){
        if($this->supportType){
            return $this->supportType;
        }
        $exts = array();
        if(function_exists('imagecreatefromjpeg')){
            $exts[] = 'jpg';
            $exts[] = 'jpeg';
        }
        if(function_exists('imagecreatefromgif')){
            $exts[] = 'gif';
        }
        if(function_exists('imagecreatefrompng')){
            $exts[] = 'png';
        }
        $this->supportType = $exts;
        return $this->supportType;
    }
    
    public function setQuality($q){
        if($q>0)
            $this->image_quality = $q;
    }
    /**
     * 返回扩展名
     * 
     * @return string 扩展名
     */
    public function getExtension(){
        if( $this->image_type == IMAGETYPE_JPEG ) return 'jpg';
        elseif( $this->image_type == IMAGETYPE_GIF ) return 'gif';
        elseif( $this->image_type == IMAGETYPE_PNG ) return 'png';
    }

    /**
     * 将图形对象保存成文件
     *
     * @param string $filename 文件名
     * @param int $image_type 文件类型
     * return volid
     */
    public function save($filename,$image_type = null) {
        if(!$image_type){
            $image_type = $this->image_type;
        }else{
            if($image_type == 'jpg'){
                $image_type = IMAGETYPE_JPEG;
            }elseif($image_type == 'png'){
                $image_type = IMAGETYPE_PNG;
            }else{
                $image_type = IMAGETYPE_GIF;
            }
        }

        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image,$filename,$this->image_quality);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            imagegif($this->image,$filename);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            $pngQuality = ($this->image_quality - 100) / 11.111111;
            $pngQuality = round(abs($pngQuality));

            imagepng($this->image,$filename,$pngQuality);
        }
    }
    
    /**
     * 将图像输出到数据流
     *
     * @param int $image_type 文件类型
     * @return void
     */
    public function output() {
        $image_type = $this->image_type;
        if( $image_type == IMAGETYPE_JPEG ) {
            header('Content-Type: image/jpeg');
            imagejpeg($this->image,NULL,$this->image_quality);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            header('Content-type: image/gif');
            imagegif($this->image);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            header('Content-type: image/png');
            imagepng($this->image);
        }
    }

    /**
     * 获得图像宽度
     *
     * @return int 图像宽度
     */
    public function getWidth() {
        return imagesx($this->image);
    }

    /**
     * 获得图像高度
     *
     * @return int 图像高度
     */
    public function getHeight() {
        return imagesy($this->image);
    }

    /**
     * 等比例缩小到指定高度
     * 
     * @param int $height 指定高度
     */
    public function resizeToHeight($height) {
        $imgHeight = $this->getHeight();
        if($height>=$imgHeight) return;

        $ratio = $height / $imgHeight;
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }
    /**
     * 等比例缩小到指定宽度
     * 
     * @param int $width 指定宽度
     */
    public function resizeToWidth($width) {
        $imgWidth = $this->getWidth();
        if($width>=$imgWidth) return;
        $ratio = $width / $this->getWidth();
        $height = $this->getHeight() * $ratio;
        $this->resize($width,$height);
    }

    /**
     * 指定最大宽度和最大高度
     * @param int $w 最大宽度
      * @param int $h 最大高度
     */
    public function resizeScale($w=0,$h=0){
        if($w == 0 && $h>0){
            return $this->resizeToHeight($h);
        }
        if($h == 0 && $w>0){
            return $this->resizeToWidth($w);
        }
        if($w == 0 && $h==0){
            return false;
        }
        $maxwidth = $w;
        $maxheight = $h;
        
        $width = $this->getWidth();
        $height = $this->getHeight();
        
        $RESIZEWIDTH = $RESIZEHEIGHT = false;
        if($maxwidth && $width > $maxwidth){
            $widthratio = $maxwidth/$width;
            $RESIZEWIDTH=true;
        }
        if($maxheight && $height > $maxheight){
            $heightratio = $maxheight/$height;
            $RESIZEHEIGHT=true;
        }
        if($RESIZEWIDTH && $RESIZEHEIGHT){
            if($widthratio < $heightratio){
                return $this->resizeToWidth($w);
            }else{
                return $this->resizeToHeight($h);
            }
        }elseif($RESIZEWIDTH){
            return $this->resizeToWidth($w);
        }elseif($RESIZEHEIGHT){
            return $this->resizeToHeight($h);
        }
    }

    //缩小并剪切
    public function resizeCut($w,$h){
        $left = 0;
        $top = 0;

        $width = $this->getWidth();
        $height = $this->getHeight();

        if($w == $width && $h == $height){
            return true;
        }

        $ratio_o = $width/$height;
        $ratio_t = $w/$h;

        if($width>$w && $height > $h){
            if($ratio_o == $ratio_t){
                return $this->resize($w,$h);
            }

            if($ratio_o > $ratio_t){
                $this->resizeToHeight($h);
                $left = ceil(($h/$height * $width - $w)/2);
            }else{
                $this->resizeToWidth($w);
                $top = ceil(($w/$width * $height - $h)/2);
            }
            return $this->cut($w,$h,$left,$top);
        }elseif($width<$w && $height < $h){
            return true;
        }else{
            if($width < $w){
                $top = ceil(($height - $h)/2);
                return $this->cut($width,$h,$left,$top);
            }else{
                $left = ceil(($width - $w)/2);
                return $this->cut($w,$height,$left,$top);
            }
        }
    }

    public function close(){
        imagedestroy($this->image);
    }
    /**
     * 改变图像尺寸
     * 
     * @param int $width 指定宽度
     * @param int $height 指定高度
     */
    public function resize($width,$height) {
        if($this->true_color && $this->image_type == IMAGETYPE_PNG){
            $newim = imagecreatetruecolor($width, $height);
            $alpha = imagecolorallocatealpha($newim, 0, 0, 0, 127);
            imagefill($newim, 0, 0, $alpha);
            imagecopyresampled($newim, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
            imagesavealpha($newim, true);
        }elseif($this->true_color && $this->image_type == IMAGETYPE_JPEG){
            $newim = imagecreatetruecolor($width, $height);
            imagecopyresampled($newim, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        }else{
            $newim = imagecreate($width, $height);
            imagecopyresized($newim, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        }
        imagedestroy($this->image);
        $this->image = $newim;
    }

    /**
     * 裁剪图像
     *
     * @param int $width 指定宽度
     * @param int $height 指定高度
     */
    public function cut($width,$height,$left = 0,$top = 0){
        if($this->true_color && $this->image_type == IMAGETYPE_PNG){
            $new_image = imagecreatetruecolor($width, $height);
            $alpha = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $alpha);
            imagecopy($new_image, $this->image, 0, 0, $left, $top, $width, $height);
            imagesavealpha($new_image, true);
        }elseif($this->true_color && $this->image_type == IMAGETYPE_JPEG){
            $new_image = imagecreatetruecolor($width, $height);
            imagecopy($new_image, $this->image, 0, 0, $left, $top, $width, $height);
        }else{
            $new_image = imagecreate($width, $height);
            imagecopy($new_image, $this->image, 0, 0, $left, $top, $width, $height);
        }
        imagedestroy($this->image);
        $this->image = $new_image;
    }
    
    /*
    旋转图片
    */
    public function rotate($dgree){
        $tran = imagecolortransparent($this->image,NULL);
        $new_image = imagerotate($this->image, $dgree , $tran);
        imagedestroy($this->image);
        $this->image = $new_image;
    }
    
    public function waterMarkSetting($param){
        $this->param = $param;
    }
    
    public function waterMarkImg(){
        if(empty($this->param['water_mark_image']) || !file_exists($this->param['water_mark_image'])){
            return false;
        }
    
        $water_info = getimagesize($this->param['water_mark_image']);
        $w = $water_info[0];//取得水印图片的宽
        $h = $water_info[1];//取得水印图片的高
        switch($water_info[2])//取得水印图片的格式
        {
            case 1:$water_im = imagecreatefromgif($this->param['water_mark_image']);break;
            case 2:$water_im = imagecreatefromjpeg($this->param['water_mark_image']);break;
            case 3:$water_im = imagecreatefrompng($this->param['water_mark_image']);break;
            default:return false;
        }
        $ground_w = $this->getWidth();
        $ground_h = $this->getHeight();
    
        if( $ground_w<$w || $ground_h<$h ){
            return false;
        }
        switch($this->param['water_mark_pos'])
        {
            case 0://随机
            $posX = rand(5,($ground_w - $w - 5));
            $posY = rand(5,($ground_h - $h - 5));
            break;
            case 1://1为顶端居左
            $posX = 5;
            $posY = 5;
            break;
            case 2://2为顶端居中
            $posX = ($ground_w - $w) / 2;
            $posY = 5;
            break;
            case 3://3为顶端居右
            $posX = $ground_w - $w -5;
            $posY = 5;
            break;
            case 4://4为中部居左
            $posX = 5;
            $posY = ($ground_h - $h) / 2;
            break;
            case 5://5为中部居中
            $posX = ($ground_w - $w) / 2;
            $posY = ($ground_h - $h) / 2;
            break;
            case 6://6为中部居右
            $posX = $ground_w - $w - 5;
            $posY = ($ground_h - $h) / 2;
            break;
            case 7://7为底端居左
            $posX = 5;
            $posY = $ground_h - $h - 5;
            break;
            case 8://8为底端居中
            $posX = ($ground_w - $w) / 2;
            $posY = $ground_h - $h - 5;
            break;
            case 9://9为底端居右
            $posX = $ground_w - $w - 5;
            $posY = $ground_h - $h - 5;
            break;
            default://随机
            $posX = rand(5,($ground_w - $w - 5));
            $posY = rand(5,($ground_h - $h - 5));
            break;
        }
        //设定图像的混色模式
        imagealphablending($this->image, true);
        /*if(function_exists('imagecopymerge') && $this->param['water_mark_opacity'] != 0){
            @imagecopymerge($this->image, $water_im, $posX, $posY, 0, 0, $w,$h,$this->param['water_mark_opacity']);
        }else{*/
            imagecopy($this->image, $water_im, $posX, $posY, 0, 0, $w,$h);//拷贝水印到目标文件
        //}
        imagedestroy($water_im);
    }
    
    public function waterMarkFont(){
        if($this->param['water_mark_color']){
            $color = $this->param['water_mark_color'];
        }else{
            $color = '#000000';
        }
        $r = hexdec( substr( $color, 1, 2 ) );
        $g = hexdec( substr( $color, 3, 2 ) );
        $b = hexdec( substr( $color, 5, 2 ) );
        
        if($this->param['water_mark_opacity']>0 && $this->param['water_mark_opacity']<100){
            $fontcolor = imagecolorallocatealpha( $this->image, $r, $g, $b ,$this->param['water_mark_opacity']);
        }else{
            $fontcolor = imagecolorallocate( $this->image, $r, $g, $b );
        }
        
        $box = ImageTTFBBox(
            $this->param['water_mark_fontsize'],
            $this->param['water_mark_angle'],
            $this->param['water_mark_font'],
            $this->param['water_mark_string']);
        $ground_w = $this->getWidth();
        $ground_h = $this->getHeight();
        $h = max($box[1], $box[3]) - min($box[5], $box[7]);
        $w = max($box[2], $box[4]) - min($box[0], $box[6]);
        $ax = min($box[0], $box[6]) * -1;
        $ay = min($box[5], $box[7]) * -1;
        switch($this->param['water_mark_pos'])
        {
            case 0://随机
            $posX = rand(5,($ground_w - $w - 5));
            $posY = rand(5,($ground_h - $h - 5));
            break;
            case 1://1为顶端居左
            $posX = 5;
            $posY = 5;
            break;
            case 2://2为顶端居中
            $posX = ($ground_w - $w) / 2;
            $posY = 5;
            break;
            case 3://3为顶端居右
            $posX = $ground_w - $w -5;
            $posY = 5;
            break;
            case 4://4为中部居左
            $posX = 5;
            $posY = ($ground_h - $h) / 2;
            break;
            case 5://5为中部居中
            $posX = ($ground_w - $w) / 2;
            $posY = ($ground_h - $h) / 2;
            break;
            case 6://6为中部居右
            $posX = $ground_w - $w - 5;
            $posY = ($ground_h - $h) / 2;
            break;
            case 7://7为底端居左
            $posX = 5;
            $posY = $ground_h - $h - 5;
            break;
            case 8://8为底端居中
            $posX = ($ground_w - $w) / 2;
            $posY = $ground_h - $h - 5;
            break;
            case 9://9为底端居右
            $posX = $ground_w - $w - 5;
            $posY = $ground_h - $h - 5;
            break;
            default://随机
            $posX = rand(5,($ground_w - $w - 5));
            $posY = rand(5,($ground_h - $h - 5));
            break;
        }

        imagettftext($this->image,
             $this->param['water_mark_fontsize'],
             $this->param['water_mark_angle'],
             $posX + $ax,
             $posY + $ay,
             $fontcolor,
             $this->param['water_mark_font'],
             $this->param['water_mark_string']);
    }
    
    public function waterMark(){
        //读取水印文件
        if($this->param['water_mark_type'] == 'image'){
            $this->waterMarkImg();
        }elseif($this->param['water_mark_type'] == 'font'){
            $this->watermarkFont();
        }
        return false;
    }
}
