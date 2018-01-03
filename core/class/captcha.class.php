<?php
defined('IN_MWEB') || exit('Access denied!');

class Captcha{
    const CAPTCHA_VAR ='MYPHP_CAPTCHA_VALUE';
    const TTL_VAR     ='MYPHP_CAPTCHA_TTL_VALUE';
    private $verify_code='';
    private $_config=array(
            'seed'     => "345679aABbCcDdEeFGHhJj345KLMNPRsTVWXYz679",//验证码种子
            'font'     => '',
            'ttl'      => 120,//有效时间
            'width'    => 135,//生成的宽度
            'height'   => 53,//生成的高度
            'textleft' => 0,
            'length'   => 4, //验证码个数
            'fontsize' => 28,//字体大小
            'disturb'  => 160,//干扰象素
            'horizontal_disturb'  => true //水平方向干扰
        );

    public function __construct($config=null){
        isset($_SESSION) || session_start();

        if(is_array($config)){
            $this->_config = $config + $this->_config;
        }
    }

    public function check($code){
        $code = strtoupper($code);
        $value = isset($_SESSION[self::CAPTCHA_VAR])?$_SESSION[self::CAPTCHA_VAR]:'';
        $ttl = isset($_SESSION[self::TTL_VAR])?$_SESSION[self::TTL_VAR]:0;

        if(time() - $ttl > $this->_config['ttl'] ){
            return false;
        }
        if($value != $code){
            return false;
        }
        return true;
    }

    public function clear(){
        $_SESSION[self::CAPTCHA_VAR] = '';
        $_SESSION[self::TTL_VAR] = 0;
    }

    private function generate(){
        $str = $this->_config['seed'];

        $str_len = strlen($str);
        $length = $this->_config['length'];

        for($i=0;$i<$length;$i++){
            $this->verify_code .= $str[rand(0,$str_len-1)];
        }
        $this->verify_code = strtoupper($this->verify_code);

        $_SESSION[self::CAPTCHA_VAR] = $this->verify_code;
        $_SESSION[self::TTL_VAR] = time();
    }

    public function display(){
        $this->generate();
        
        $im_x = $this->_config['width'];
        $im_y = $this->_config['height'];

        $im = imagecreatetruecolor($im_x,$im_y);
        $text_c = ImageColorAllocate($im, mt_rand(0,120),mt_rand(0,120),mt_rand(0,120));

        $buttum_c = ImageColorAllocate($im,243, 251, 254);
        imagefill($im, 0, 0, $buttum_c);

        if(!$this->_config['font']){
            $font = dirname(dirname(__FILE__)).'/fonts/bookman.ttf';
        }else{
            $font = $this->_config['font'];
        }

        $text_length = strlen($this->verify_code);
        $array = array(-1,1);
        $size = $this->_config['fontsize'];

        for ($i=0;$i< $text_length;$i++)
        {
            $tmp =substr($this->verify_code,$i,1);
            $an = $array[array_rand($array)]*mt_rand(1,10);//角度
            $y = rand(0.6 * $this->_config['height'], 0.8 * $this->_config['height']);
            imagettftext($im, $size, $an, $this->_config['textleft']+$i*$size, $y, $text_c, $font, $tmp);
        }
        
        $distortion_im = imagecreatetruecolor($im_x, $im_y);
        imagefill($distortion_im, 0, 0, $buttum_c);

        for ( $i=0; $i<$im_x; $i++) {
            for ( $j=0; $j<$im_y; $j++) {
                $rgb = imagecolorat($im, $i , $j);
                if( (int)($i+10+sin($j/$im_y*2*M_PI)*10) <= imagesx($distortion_im)&& (int)($i+10+sin($j/$im_y*2*M_PI)*10) >=0 ) {
                    imagesetpixel ($distortion_im, (int)($i+10+sin($j/$im_y*2*M_PI-M_PI*0.1)*4) , $j , $rgb);
                }
            }
        }

        //加入干扰象素;
        $count = $this->_config['disturb'];//干扰像素的数量
        for($i=0; $i<$count; $i++){
            $randcolor = ImageColorallocate($distortion_im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagesetpixel($distortion_im, mt_rand()%$im_x , mt_rand()%$im_y , $randcolor);
        }

        if($this->_config['horizontal_disturb']){
            $rand = mt_rand(5,30);
            $rand1 = mt_rand(15,25);
            $rand2 = mt_rand(5,10);
            for ($yy=$rand; $yy<=+$rand+1; $yy++){
                for ($px=-80;$px<=80;$px=$px+0.1)
                {
                    $x=$px/$rand1;
                    if ($x!=0){ $y=sin($x);}
                    $py=$y*$rand2;
                    imagesetpixel($distortion_im, $px+80, $py+$yy, $text_c);
                }
            }
        }

        //设置文件头;
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Cache-Control: no-store, must-revalidate"); 
        header("Pragma: no-cache"); 
        Header("Content-type: image/png");
        ImagePNG($distortion_im);
        ImageDestroy($distortion_im);
        ImageDestroy($im);
    }
}