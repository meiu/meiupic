<?php
defined('IN_MWEB') or die('access denied');

Class SmsClass{
    public function sendSMS($mobile,$content,$type=1){
        $setting = app('base')->getSetting('sms_setting',true);

        if($type == 1){
            $content = '【'.$setting['sign'].'】'.str_replace('#验证码#', $content, $setting['yzmtpl']);
        }else{
            $content = '【'.$setting['sign'].'】'.$content;
        }

        if($setting['api_type'] == 'dingdong'){
            return $this->sendByDingdong($mobile,$content,$setting['api_key'],$type);
        }elseif($setting['api_type'] == 'yunpian'){
            return $this->sendByYunpian($mobile,$content,$setting['api_key'],$type);
        }else{
            return true;
        }
    }

    private function sendByDingdong($mobile,$content,$apikey,$type=1){
        $data = array(
            'apikey'=>$apikey,
            'mobile'=> $mobile,
            'content'=>$content
        );
        if($type==1){
            $url = 'https://api.dingdongcloud.com/v1/sms/sendyzm';
        }else{
            $url = 'https://api.dingdongcloud.com/v1/sms/sendtz';
        }

        $return = $this->httpPost($url,$data);
        $json = @json_decode($return,true);

        if($json && $json['code']==1){
            $this->addLog($mobile,$content,true,'');
            return true;
        }
        $this->addLog($mobile,$content,false,@$json['msg']);
        return false;
    }

    private function sendByYunpian($mobile,$content,$apikey,$type=1){
        $data = array(
            'apikey'=>$apikey,
            'mobile'=> $mobile,
            'text'=>$content
        );

        $url = 'https://sms.yunpian.com/v2/sms/single_send.json';
        $apikey = '088becdfab23f2976eeb9fe1db2b7d39';

        $data = http_build_query($data);
        $return = $this->httpPost($url,$data);

        $json = @json_decode($result,true);
        
        if($json && $json['code']==0){
            $this->addLog($mobile,$content,true,'');
            return true;
        }
        $this->addLog($mobile,$content,false,@$json['msg']);
        return false;
    }

    private function addLog($mobile,$content,$result=false,$resultmsg=''){
        $m = M('sms_logs');
        $m->insert(array(
            'mobile' => $mobile,
            'content' => $content,
            'sendtime' => CURRENT_TIME,
            'result' => $result?'success':'failed',
            'result_content' => $resultmsg
        ));
    }

    private function httpPost($url, $post = null){
        $context = array(); 
        if (is_array($post)){ 
            ksort($post); 
            $context['http'] = array( 
                'method' => 'POST', 
                'header'  => 'Content-type: application/x-www-form-urlencoded',  
                'content' => http_build_query($post)
            );
        }
        return file_get_contents($url, false, stream_context_create($context)); 
    }

    public function sendCode($mobile,$code,& $msg){
        $m = M('sms_codes');
        $row = $m->findRow("mobile=".$m->escape($mobile));

        if($row){
            if(CURRENT_TIME - $row['lasttime']<60){
                $msg = '操作过于频繁，请稍后再次操作!';
                return false;
            }
            if($row['send_count']>=10){
                $msg = '您今日操作过于频繁!';
                return false;
            }
        }

        if($row){
            //如果不是今日send_cound置1，否则加一
            if(date('Y-m-d',$row['lasttime'])==date('Y-m-d')){
                $send_count = $row['send_count']+1;
            }else{
                $send_count = 1;
            }
            $m->updateW("mobile=".$m->escape($mobile),array('code'=>$code,'lasttime'=>CURRENT_TIME,'send_count'=>$send_count));
        }else{
            $m->insert(array("mobile"=>$mobile,'code'=>$code,'lasttime'=>CURRENT_TIME,'send_count'=>1));
        }

        if($this->sendSMS($mobile,$code,1)){
            $msg = '发送成功!';
            return true;
        }else{
            $msg = '发送失败!';
            return false;
        }
    }

    public function checkCode($mobile,$code){
        if(!$code){
            return false;
        }
        $m = M('sms_codes');
        $row = $m->findRow('mobile='.$m->escape($mobile));

        if(!$row){
            return false;
        }
        if($row['lasttime']-CURRENT_TIME > 60*15){
            return false;
        }
        if($row['code'] != $code){
            return false;
        }
        return true;
    }
}