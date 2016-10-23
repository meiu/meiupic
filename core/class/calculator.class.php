<?php

defined('IN_MWEB') || exit('Access denied!');
class calculator {
    public function run($expression){
        $_stack  = array('#');
        $_rpnexp = array();
        $_operator = array('(', '+', '-', '*', '/', ')');
        $_priority = array('#' => 0, '(' => 10, '+' => 20, '-' => 20, '*' => 30, '/' => 30);

        $len = strlen($expression);
        
        for($i = 0; $i < $len; $i++) {
            $char = substr($expression, $i, 1);
            
            if ($char == '(') {
                $_stack[] = $char;
                continue;
            } else if ( ! in_array($char, $_operator)) {
                $data.=$char;
                if($i+1<$len)
                {
                    $next = substr($expression, $i+1, 1);
                    if(in_array($next, $_operator)||is_null($next))
                    {
                        $_rpnexp[] = $data;
                        $data=null;
                    }
                }
                else
                {
                    $_rpnexp[] = $data;
                    $data=null;
                }
                continue;
            } else if ($char == ')') {
                for($j = count($_stack); $j >= 0; $j--) {
                    $tmp = array_pop($_stack);
                    if ($tmp == "(") {
                        break;  
                    } else {
                        $_rpnexp[] = $tmp;
                    }
                }
                continue;
            } else if ($_priority[$char] <= $_priority[end($_stack)]) {
                $_rpnexp[] = array_pop($_stack);
                $_stack[]  = $char;
                continue;
            } else {
                $_stack[] = $char;
                continue;
            }
        }
        
        for($i = count($_stack); $i >= 0; $i--) {
            if (end($_stack) == '#') break;
            $_rpnexp[] = array_pop($_stack);
        }
        $mystack=array();   
        foreach($_rpnexp as $ret)
        {
            if($ret=="+")
            {
                $tmp_a=array_pop($mystack); 
                $tmp_b=array_pop($mystack); 
                $mystack[]=$tmp_a+$tmp_b;
            }
            else if($ret=="-")
            {
                $tmp_a=array_pop($mystack); 
                $tmp_b=array_pop($mystack); 
                $mystack[]=$tmp_b-$tmp_a;
            }
            else if($ret=="*")
            {
                $tmp_a=array_pop($mystack); 
                $tmp_b=array_pop($mystack); 
                $mystack[]=$tmp_b*$tmp_a;
            }
            else if($ret=="/")
            {
                $tmp_a=array_pop($mystack); 
                $tmp_b=array_pop($mystack); 
                $mystack[]=$tmp_b/$tmp_a;
            }
            else
            {
                $mystack[]=$ret;
            }
        }
        return $mystack[0]; 
    }
}