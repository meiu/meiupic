<?php
/**
 * $Id: image_imagick.php 414 2012-10-25 05:12:48Z lingter@gmail.com $
 * 
 * @author : Lingter
 * @support : http://www.meiu.cn
 * @copyright : (c)2010 meiu.cn lingter@gmail.com
 */

class ImageImagick extends image{
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
        $this->image = new Imagick($filename);

        $this->image_type = strtoupper($this->image->getImageFormat());
        
        if(!$this->image)
            return false;
        else
            return true;
    }
    
    public function supportType(){
        return array('jpg','jpeg','gif','png','bmp');
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
        if( $this->image_type == 'JPEG' ) return 'jpg';
        elseif( $this->image_type == 'GIF' ) return 'gif';
        elseif( $this->image_type == 'PNG' ) return 'png';
        elseif( $this->image_type == 'BMP' ) return 'bmp';
    }

    /**
     * 将图形对象保存成文件
     *
     * @param string $filename 文件名
     * @param int $image_type 文件类型
     * return volid
     */
    public function save($filename) {
        if($this->image_type == 'GIF'){
            $this->image->writeImages($filename,true);
        }else{
            $this->image->writeImage($filename);
        }
    }
    
    /**
     * 将图像输出到数据流
     *
     * @param int $image_type 文件类型
     * @return void
     */
    function output() {
        $image_type = $this->image_type;
        if( $image_type == 'JPEG' ) {
            header('Content-Type: image/jpeg');
            echo $this->image->getImageBlob();
        } elseif( $image_type == 'GIF' ) {
            header('Content-type: image/gif');
            echo $this->image->getImagesBlob();
        } elseif( $image_type == 'PNG' ) {
            header('Content-type: image/png');
            echo $this->image->getImageBlob();
        } elseif( $image_type == 'BMP' ) {
            header('Content-type: image/bmp');
            echo $this->image->getImageBlob();
        }
    }

    /**
     * 获得图像宽度
     *
     * @return int 图像宽度
     */
    public function getWidth() {
        return $this->image->getImageWidth();
    }

    /**
     * 获得图像高度
     *
     * @return int 图像高度
     */
    public function getHeight() {
        return $this->image->getImageHeight();
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
     * 改变图像尺寸
     * 
     * @param int $width 指定宽度
     * @param int $height 指定高度
     */
    public function resize($w=0, $h=0) {
        if($this->image_type != 'GIF'){
            $this->image->thumbnailImage($w, $h);
        }else{
            $color_transparent = new ImagickPixel("transparent"); //透明色
            $dest = new Imagick();
            foreach($this->image as $frame){
                $page = $frame->getImagePage();
                $tmp = new Imagick(); 
                $tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
                $tmp->compositeImage($frame, Imagick::COMPOSITE_OVER, $page['x'], $page['y']);
                $tmp->thumbnailImage($w, $h);
                $dest->addImage($tmp);
                $dest->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
                $dest->setImageDelay($frame->getImageDelay());
                $dest->setImageDispose($frame->getImageDispose());
            }
            $dest->coalesceImages();
            $this->image->destroy();
            $this->image = $dest;
        }
    }
    
    function _scaleImage($x,$y,$cx,$cy) {
        list($nx,$ny)=array($x,$y);

        if ($x>=$cx || $y>=$cx) {

            if ($x>0) $rx=$cx/$x;
            if ($y>0) $ry=$cy/$y;

            if ($rx>$ry) {
                $r=$ry;
            } else {
                $r=$rx;
            }

            $nx=intval($x*$r);
            $ny=intval($y*$r);
        }   

        return array($nx,$ny);
    }
    /**
     * 指定最大宽度和最大高度
     * @param int $w 最大宽度
      * @param int $h 最大高度
     */
    function resizeScale($w=0,$h=0){
        list($width,$height) = $this->_scaleImage($this->getWidth(),$this->getHeight(),$w,$h);
        if($this->image_type != 'GIF'){
            $this->image->thumbnailImage($width, $height);
        }else{
            $color_transparent = new ImagickPixel("transparent"); //透明色
            $dest = new Imagick();
            foreach($this->image as $frame){
                $page = $frame->getImagePage();
                $tmp = new Imagick(); 
                $tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
                $tmp->compositeImage($frame, Imagick::COMPOSITE_OVER, $page['x'], $page['y']);
                $tmp->thumbnailImage($width, $height);
                $dest->addImage($tmp);
                $dest->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
                $dest->setImageDelay($frame->getImageDelay());
                $dest->setImageDispose($frame->getImageDispose());
            }
            $dest->coalesceImages();
            $this->image->destroy();
            $this->image = $dest;
        }
    }
    /**
     * 等比例缩小到指定宽度，并切成方形
     * 
     * @param int $v 指定宽度/高度
     */
    function square($v){
        if($this->image_type != 'GIF'){
            $this->image->cropThumbnailImage($v,$v);
        }else{
            $color_transparent = new ImagickPixel("transparent"); //透明色
            $dest = new Imagick();
            foreach($this->image as $frame){
                $page = $frame->getImagePage();
                $tmp = new Imagick(); 
                $tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
                $tmp->compositeImage($frame, Imagick::COMPOSITE_OVER, $page['x'], $page['y']);
                $tmp->cropThumbnailImage($v,$v);
                $dest->addImage($tmp);
                $dest->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
                $dest->setImageDelay($frame->getImageDelay());
                $dest->setImageDispose($frame->getImageDispose());
            }
            $dest->coalesceImages();
            $this->image->destroy();
            $this->image = $dest;
        }
    }
    //缩小并剪切
    function resizeCut($w,$h){
        if($this->image_type != 'GIF'){
            $this->image->cropThumbnailImage($w,$h);
        }else{
            $color_transparent = new ImagickPixel("transparent"); //透明色
            $dest = new Imagick();
            foreach($this->image as $frame){
                $page = $frame->getImagePage();
                $tmp = new Imagick(); 
                $tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
                $tmp->compositeImage($frame, Imagick::COMPOSITE_OVER, $page['x'], $page['y']);
                $tmp->cropThumbnailImage($w,$h);
                $dest->addImage($tmp);
                $dest->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
                $dest->setImageDelay($frame->getImageDelay());
                $dest->setImageDispose($frame->getImageDispose());
            }
            $dest->coalesceImages();
            $this->image->destroy();
            $this->image = $dest;
        }
    }
    
    function rotate($d){
        $d =$d * -1;
        $color_transparent = new ImagickPixel("transparent"); //透明色
        if($this->image_type == 'GIF'){
            $dest = new Imagick();
            foreach($this->image as $frame){
                $page = $frame->getImagePage();
                $tmp = new Imagick(); 
                $tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
                $tmp->compositeImage($frame, Imagick::COMPOSITE_OVER, $page['x'], $page['y']);
                
                $tmp->rotateImage($color_transparent,$d);
                $dest->addImage($tmp);
                $dest->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
                $dest->setImageDelay($frame->getImageDelay());
                $dest->setImageDispose($frame->getImageDispose());
            }
            $dest->coalesceImages();
            $this->image->destroy();
            $this->image = $dest;
        }else{
            $this->image->rotateImage($color_transparent,$d);
        }
    }
    
    function waterMarkSetting($param){
        $this->param = $param;
    }
    
    function waterMarkImg(){
           if(empty($this->param['water_mark_image']) || !file_exists($this->param['water_mark_image'])){
            return false;
        }
        
        $bg_h = $this->getHeight();
        $bg_w = $this->getWidth();
        
        $water_img = new Imagick($this->param['water_mark_image']);
        $water_h = $water_img->getImageHeight();
        $water_w = $water_img->getImageWidth();
        //if($bg_h < $water_h || $bg_w < $water_w )
        //{  
        //   return false;
        //}
        if($this->param['water_mark_opacity']){
            $water_img->setImageOpacity($this->param['water_mark_opacity']/100);
        }
        $draw = new ImagickDraw();
        switch($this->param['water_mark_pos']) {
            case 0:
            case 1:
                $gravity = Imagick::GRAVITY_NORTHWEST;//'NorthWest';
                break;
            case 2:
                $gravity = Imagick::GRAVITY_NORTH;//'North';
                break;
            case 3:
                $gravity = Imagick::GRAVITY_NORTHEAST;//'NorthEast';
                break;
            case 4:
                $gravity = Imagick::GRAVITY_WEST;//'West';
                break;
            case 5:
                $gravity = Imagick::GRAVITY_CENTER;//'Center';
                break;
            case 6:
                $gravity = Imagick::GRAVITY_EAST;//'East';
                break;
            case 7:
                $gravity = Imagick::GRAVITY_SOUTHWEST;//'SouthWest';
                break;
            case 8:
                $gravity = Imagick::GRAVITY_SOUTH;//'South';
                break;
            case 9:
                $gravity = Imagick::GRAVITY_SOUTHEAST;
                break;
        }
        $draw->setGravity($gravity);
        $draw->composite($water_img->getImageCompose(),0,0,50,0,$water_img);
        if($this->image_type == 'GIF'){
            $color_transparent = new ImagickPixel("transparent"); //透明色
            $dest = new Imagick();
            foreach($this->image as $frame){
                $page = $frame->getImagePage();
                $tmp = new Imagick(); 
                $tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
                $tmp->compositeImage($frame, Imagick::COMPOSITE_OVER, $page['x'], $page['y']);
                $tmp->drawImage($draw);
                $dest->addImage($tmp);
                $dest->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
                $dest->setImageDelay($frame->getImageDelay());
                $dest->setImageDispose($frame->getImageDispose());
            }
            $dest->coalesceImages();
            $this->image->destroy();
            $this->image = $dest;
        }else{
            $this->image->drawImage($draw);
        }
        
    }
    
    function waterMarkFont(){
        if($this->param['water_mark_color']){
            $color = $this->param['water_mark_color'];
        }else{
            $color = '#000000';
        }
        $r = hexdec( substr( $color, 1, 2 ) );
        $g = hexdec( substr( $color, 3, 2 ) );
        $b = hexdec( substr( $color, 5, 2 ) );
        $draw = new ImagickDraw();  
        $draw->setFillColor( new ImagickPixel($color) ); //设置填充颜色
        $draw->setFont($this->param['water_mark_font']); //设置文本字体，要求ttf或者ttc字体，可以绝对或者相对路径
        $draw->setFontSize($this->param['water_mark_fontsize']); //设置字号

        switch($this->param['water_mark_pos']) {
            case 0:
            case 1:
                $gravity = Imagick::GRAVITY_NORTHWEST;//'NorthWest';
                break;
            case 2:
                $gravity = Imagick::GRAVITY_NORTH;//'North';
                break;
            case 3:
                $gravity = Imagick::GRAVITY_NORTHEAST;//'NorthEast';
                break;
            case 4:
                $gravity = Imagick::GRAVITY_WEST;//'West';
                break;
            case 5:
                $gravity = Imagick::GRAVITY_CENTER;//'Center';
                break;
            case 6:
                $gravity = Imagick::GRAVITY_EAST;//'East';
                break;
            case 7:
                $gravity = Imagick::GRAVITY_SOUTHWEST;//'SouthWest';
                break;
            case 8:
                $gravity = Imagick::GRAVITY_SOUTH;//'South';
                break;
            case 9:
                $gravity = Imagick::GRAVITY_SOUTHEAST;
                break;
        }
        $draw->setGravity($gravity);
        
        if($this->image_type == 'GIF'){
            $color_transparent = new ImagickPixel("transparent");
            $dest = new Imagick();
            foreach($this->image as $frame){
                $page = $frame->getImagePage();
                $tmp = new Imagick(); 
                $tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
                $tmp->compositeImage($frame, Imagick::COMPOSITE_OVER, $page['x'], $page['y']);
                $tmp->annotateImage($draw,0,0,$this->param['water_mark_angle'],$this->param['water_mark_string']);
                $dest->addImage($tmp);
                $dest->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
                $dest->setImageDelay($frame->getImageDelay());
                $dest->setImageDispose($frame->getImageDispose());
            }
            $dest->coalesceImages();
            $this->image->destroy();
            $this->image = $dest;
        }else{
            if($this->param['water_mark_opacity']){
                $draw->setFillOpacity($this->param['water_mark_opacity']/100);
            }
            $this->image->annotateImage($draw,0,0,$this->param['water_mark_angle'],$this->param['water_mark_string']);
        }
        
    }
    
    function waterMark(){
        //读取水印文件
        if($this->param['water_mark_type'] == 'image'){
            $this->waterMarkImg();
        }elseif($this->param['water_mark_type'] == 'font'){
            $this->watermarkFont();
        }
        return false;
    }
    
    function close(){
        $this->image->destroy();
    }   
}