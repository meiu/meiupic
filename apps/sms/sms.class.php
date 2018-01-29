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

    public function sendByDingdong($mobile,$content,$apikey,$type=1){
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

    public function sendByYunpian($mobile,$content,$apikey,$type=1){
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

    public function httpPost($url, $post = null){
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
}