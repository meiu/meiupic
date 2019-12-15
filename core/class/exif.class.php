<?php
/**
 * $Id: exif.class.php 411 2012-10-11 06:51:29Z lingter@gmail.com $
 * 
 * Exif lib: get image exif infos
 *      
 * @author : Lingter
 * @support : http://www.meiu.cn
 * @copyright : (c)2010 - 2011 meiu.cn lingter@gmail.com
 */
class exif{
    
    function get_exif($file){
        if(!function_exists('exif_read_data')){
            return false;
        }
        $exif = @exif_read_data($file,"IFD0");
        if ($exif===false) {
            return false;
        }
        $exif_info = @exif_read_data($file,NULL,true);
        $exif_arr = $this->supported_exif();
        $new_exif = array();
        
        foreach($exif_arr as $k=>$v){
            $arr = explode('.',$v);
            if(isset($exif_info[$arr[0]])){
                if(isset($exif_info[$arr[0]][$arr[1]])){
                    $new_exif[$k] = $exif_info[$arr[0]][$arr[1]];
                }else{
                    $new_exif[$k] = false;
                }
            }else{
                $new_exif[$k] = false;
            }
            if($k=='Software' && !empty($new_exif['Software'])){
                $new_exif['Software'] = preg_replace('/([^a-zA-Z0-9_\-,\.\:&#@!\(\)\s]+)/i','',$new_exif['Software']);
            }
        }
        return $new_exif;
    }
    
    function supported_exif(){
        return array(
            'Make' => 'IFD0.Make',
            'Model' => 'IFD0.Model',
            'ApertureFNumber' => 'COMPUTED.ApertureFNumber',
            'ExposureTime' => 'EXIF.ExposureTime',
            'Flash' => 'EXIF.Flash',
            'FocalLength' => 'EXIF.FocalLength',
            'FocalLengthIn35mmFilm' => 'EXIF.FocalLengthIn35mmFilm',
            'ISOSpeedRatings' => 'EXIF.ISOSpeedRatings',
            'WhiteBalance' => 'EXIF.WhiteBalance',
            'ExposureBiasValue' => 'EXIF.ExposureBiasValue',
            'DateTimeOriginal' => 'EXIF.DateTimeOriginal',
            'FocusDistance' => 'COMPUTED.FocusDistance',
            'FileSize' => 'FILE.FileSize',
            'MimeType' => 'FILE.MimeType',
            'Width' => 'COMPUTED.Width',
            'Height' => 'COMPUTED.Height',
            'Orientation' => 'IFD0.Orientation',
            'XResolution' => 'IFD0.XResolution',
            'YResolution' => 'IFD0.YResolution',
            'ResolutionUnit' => 'IFD0.ResolutionUnit',
            'Software' => 'IFD0.Software',
            'DateTime' => 'IFD0.DateTime',
            'Artist' => 'IFD0.Artist',
            'Copyright' => 'IFD0.Copyright',
            'MaxApertureValue' => 'EXIF.MaxApertureValue',
            'FNumber' => 'EXIF.FNumber',
            'MeteringMode' => 'EXIF.MeteringMode',
            'LightSource' => 'EXIF.LightSource',
            'ColorSpace' => 'EXIF.ColorSpace',
            'ExposureMode' => 'EXIF.ExposureMode',
            'ExposureProgram' => 'EXIF.ExposureProgram',
            'DateTimeDigitized' => 'EXIF.DateTimeDigitized',
            'GPSLatitude' => 'GPS.GPSLatitude',
            'GPSLongitude' => 'GPS.GPSLongitude',
            'GPSLatitudeRef' => 'GPS.GPSLatitudeRef',
            'GPSLongitudeRef' => 'GPS.GPSLongitudeRef',
            'LensModel' => 'EXIF.UndefinedTag:0xA434'
        );
    }
    function parse_exif($infos){
        if(!$infos['Make'] && !$infos['Model']){
            return false;
        }

        $ExposureProgram  =  array("未定义", "手动", "标准程序", "光圈先决", "快门先决", "景深先决", "运动模式", "肖像模式", "风景模式");
  
        $Orientation = array("", '上/左', '上/右','下/右', '下/左', '左/上', '右/上', '右/下', '左/下');
        $ResolutionUnit = array("", "", 'in-ch','cm');
        $MeteringMode_arr  =  array(
		    "0"    =>  "未知",
		    "1"    =>  "平均",
		    "2"    =>  "中央重点平均测光",
		    "3"    =>  "点测",
		    "4"    =>  "分区",
		    "5"    =>  "评估",
		    "6"    =>  "局部",
		    "255"  =>  "其他"
		    );
		$Lightsource_arr  =  array(
			"0"    =>  "未知",
			"1"    =>  "日光",
			"2"    =>  "荧光灯",
			"3"    =>  "钨丝灯",
			"10"  =>  "闪光灯",
			"17"  =>  "标准灯光A",
			"18"  =>  "标准灯光B",
			"19"  =>  "标准灯光C",
			"20"  =>  "D55",
			"21"  =>  "D65",
			"22"  =>  "D75",
			"255"  =>  "其他"
		);
        $Flash_arr = array(
            0x00 => '关',
            0x01 => '开',
            0x05 => '打开(不探测返回光线)',
            0x07 => '打开(探测返回光线)',
            0x09 => '打开(强制)',
            0x0D => '打开(强制/不探测返回光线)',
            0x0F => '打开(强制/探测返回光线)',
            0x10 => '关闭(强制)',
            0x18 => '关闭(自动)',
            0x19 => '打开(自动)',
            0x1D => '打开(自动/不探测返回光线)',
            0x1F => '打开(自动/探测返回光线)',
            0x20 => '无闪光灯',
            0x41 => '打开(防红眼)',
            0x45 => '打开(防红眼/不探测返回光线)',
            0x47 => '打开(防红眼/探测返回光线)',
            0x49 => '打开(强制/防红眼)',
            0x4D => '打开(强制/防红眼/不探测返回光线)',
            0x4F => '打开(强制/防红眼/探测返回光线)',
            0x59 => '打开(自动/防红眼)',
            0x5D => '打开(自动/防红眼/不探测返回光线)',
            0x5F => '打开(自动/防红眼/探测返回光线)'
        );
        if(is_array($infos)){
        $new_img_info = array();
        foreach($infos as $k=>$info){
            if($info===false){
                continue;
            }
            switch($k){
                case 'Flash':
                    $new_img_info[$k] = isset($Flash_arr[$info])?$Flash_arr[$info]:'未知';
                    break;
                case 'FileSize':
                    $new_img_info[$k] = $this->bytes2u($info);
                    break;
                case 'FocalLength':
                    $new_img_info[$k] = $this->getNumber($info).'mm';
                    break;
                case 'FocalLengthIn35mmFilm':
                    $new_img_info[$k] = $info.'mm';
                    break;
                case 'FocusDistance':
                    $new_img_info[$k] = $info.'m';
                    break;
                case 'WhiteBalance':
                    $new_img_info[$k] = $info?'手动':'自动';
                    break;
                case 'ExposureBiasValue':
                    $new_img_info[$k] = $info.'EV';
                    break;
                case 'Orientation':
                    $new_img_info[$k] = $Orientation[$info];
                    break;
                case 'XResolution':
                    $new_img_info[$k] = $info.$ResolutionUnit[$infos["ResolutionUnit"]];
                    break;
                case 'YResolution':
                    $new_img_info[$k] = $info.$ResolutionUnit[$infos["ResolutionUnit"]];
                    break;
                case 'MaxApertureValue':
                    $new_img_info[$k] = $this->getNumber($info);
                    break;
                case 'MeteringMode':
                    $new_img_info[$k] = isset($MeteringMode_arr[$info])?$MeteringMode_arr[$info]:'未知';
                    break;
                case 'LightSource':
                    $new_img_info[$k] = isset($Lightsource_arr[$info])?$Lightsource_arr[$info]:'未知';
                    break;
                case 'ColorSpace':
                    $new_img_info[$k] = $info==1?"sRGB":"Uncalibrated";
                    break;
                case 'ExposureMode':
                    $new_img_info[$k] = $info?'手动':'自动';
                    break;
                case 'FNumber':
                    $new_img_info[$k] = $info;
                    break;
                    
                case 'ExposureProgram':
                    $new_img_info[$k] = isset($ExposureProgram[$info])?$ExposureProgram[$info]:'未知';
                    break;
                case 'GPSLatitude':
                    $dgree = $this->getGps($info);
                    $new_img_info[$k] = (isset($infos["GPSLatitudeRef"])?$infos["GPSLatitudeRef"].' ':'').$this->dgreeToNum($dgree);
                    break;
                case 'GPSLongitude':
                    $dgree = $this->getGps($info);
                    $new_img_info[$k] = (isset($infos["GPSLongitudeRef"])?$infos["GPSLongitudeRef"].' ':'').$this->dgreeToNum($dgree);
                    break;
                case 'GPSLatitudeRef':
                    continue;
                    break;
                case 'GPSLongitudeRef':
                    continue;
                    break;
                default:
                    $new_img_info[$k] = $info;
            }
        }
        }
        unset($new_img_info['ResolutionUnit']);
        return $new_img_info;
    }
    function bytes2u($size){
        $result = '';
        if($size < 1024)
        {
          $result = round($size, 2).' B';
        }
        elseif($size < 1024*1024)
        {
          $result = round($size/1024, 2).' KB';
        }
        elseif($size < 1024*1024*1024)
        {
          $result = round($size/1024/1024, 2).' MB';
        }
        elseif($size < 1024*1024*1024*1024)
        {
          $result = round($size/1024/1024/1024, 2).' GB';
        }
        else
        {
          $result = round($size/1024/1024/1024/1024, 2).' TB';
        }
        return $result;
    }
    function getNumber($str){
        if(is_numeric($str)){
            return $str;
        }elseif(strpos($str,'/')!== false){
            $arr = explode('/', $str);
            $result = $arr[0]/$arr[1];
            if(is_float($result)){
                return sprintf('%.1f',$result);
            }
            return $result;
        }else{
            return sprintf('%.1f',floatval($str));
        }
    }

    function getGps($exifCoord) 
	{
      if(is_array($exifCoord)){
    	  $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0; 
    	  $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0; 
    	  $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0; 
	  }else{
          if(strpos($exifCoord, ',')){
            list($degrees,$minutes,$seconds) = explode(',', $exifCoord);
          }elseif(strpos($exifCoord,';')){
            list($degrees,$minutes,$seconds) = explode(';', $exifCoord);
          }else{
            return array();
          }
      }
	  //normalize 
	  $minutes += 60 * ($degrees - floor($degrees)); 
	  $degrees = floor($degrees); 
	  
	  $seconds += 60 * ($minutes - floor($minutes)); 
	  $minutes = floor($minutes); 
	  
	  //extra normalization, probably not necessary unless you get weird data 
	  if($seconds >= 60) 
	  { 
	    $minutes += floor($seconds/60.0); 
	    $seconds -= 60*floor($seconds/60.0); 
	  } 
	  
	  if($minutes >= 60) 
	  { 
	    $degrees += floor($minutes/60.0); 
	    $minutes -= 60*floor($minutes/60.0); 
	  } 
	  
	  return array('degrees' => $degrees, 'minutes' => $minutes, 'seconds' => $seconds); 
	} 
	function gps2Num($coordPart) { 
	  $parts = explode('/', $coordPart); 
	  
	  if(count($parts) <= 0) 
	    return 0; 
	  if(count($parts) == 1) 
	    return $parts[0]; 
	  
	  return floatval($parts[0]) / floatval($parts[1]); 
	}
	function dgreeToNum($d){
	    $num = $d['degrees'] + ($d['minutes']+$d['seconds']/60)/60;
	    return round($num,8);
	}
}