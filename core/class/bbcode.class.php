<?php
defined('IN_MWEB') || exit('Access denied!');

/**
* BBCode
* format text marked up with bbcode tags to html
*/

class Bbcode {
    private static function _format_bbcode($string){
        while(preg_match('|\[([a-zA-Z]+)=?(.*?)\](.*?)\[/\1\]|s',$string, $part, PREG_OFFSET_CAPTURE)){
            $part[2][0] = str_replace('"', "", $part[2][0]);
            $part[2][0] = str_replace("'", "", $part[2][0]);
            $part[3][0] = self::_format_bbcode($part[3][0]);

            $tag = strtolower($part[1][0]);
            switch($tag){

                //处理加粗 斜体 下划线的元素
                case 'b':
                case 'i':
                case 'u':
                $replace = sprintf('<%s> %s </%s>', $tag, $part[3][0], $tag);
                break;

                //处理代码元素
                case 'code':
                $replace = '<pre>'.$part[3][0].'</pre>';
                break;

                //处理电子邮件元素
                case 'email':
                $replace = sprintf('<a href="mailto:%s">%s</a>', $part[3][0], $part[3][0]);
                break;

                //处理大小样式
                case 'size':
                $replace = sprintf('<span style="font-size: %spx">%s</span>', $part[2][0], $part[3][0]);
                break;

                //处理颜色样式
                case 'color':
                $replace = sprintf('<span style="color: %s">%s</span>', $part[2][0], $part[3][0]);
                break;

                //处理引用元素
                case 'quote':
                $replace = (empty($part[2][0])) ? ('<blockquote>'.$part[3][0].'</blockquote>') : sprintf('<blockquote>%s wrote:<br />%s' .
                            '</blockquote>', $part[2][0], $part[3][0]);
                break;

                //处理图像元素
                case 'img':
                $replace = '<img src="'.$part[3][0].'" alt="" />';
                break;

                //处理超链接
                case 'url':
                $replace = sprintf('<a href="%s">%s</a>', (!empty($part[2][0])) ? $part[2][0] : $part[3][0], $part[3][0]);
                break;

                case 'list':
                $replace = str_replace("\n[*]",'[*]',trim($part[3][0]));
                $replace = str_replace("\r[*]",'[*]',$replace);
                $replace = str_replace('[*]','</li><li>',$replace);
                $replace = '<x>'.$replace;
                switch($part[2][0]){
                    case '1':
                    $replace = str_replace('<x></li>','<ol style="list-style-type: decimal">',$replace.'</li></ol>');
                    break;

                    case 'A':
                    $replace = str_replace('<x></li>','<ol style="list-style-type: upper-alpha">',$replace.'</li></ol>');
                    break;

                    case 'a':
                    $replace = str_replace('<x></li>','<ol style="list-style-type: lower-alpha">',$replace.'</li></ol>');
                    break;

                    default:
                    $replace = str_replace('<x></li>','<ul>',$replace.'</li></ul>');
                    break;
                }
                break;
                default:
                $replace = $part[3][0];
                break;
            }
            $string = substr_replace($string, $replace, $part[0][1], strlen($part[0][0]));

        }
        return $string;

    }

    public static function format($string){
        $string = BBCode::_format_bbcode($string);

        //$string = str_replace("\r\n\r\n", '</p><p>', $string);
        //$string = str_replace("\n\n", '</p><p>', $string);
        $string = str_replace("\r\n", '<br />', $string);
        $string = str_replace("\n", '<br />', $string);
        //$string = '<p>'.$string.'</p>';
        return $string;
    }
}