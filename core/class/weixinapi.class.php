<?php

defined('IN_MWEB') || exit('Access denied!');

class Weixinapi {
    private $appId;
    private $appSecret;
    private $cache;
    private $url;

    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->cache = Cache::instance();

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $this->url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();

        $url = $this->url;
        $timestamp = time();

        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
          "appId"     => $this->appId,
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        $cache_key = 'wx_jsapi_ticket_'.$this->appId;

        $data = $this->cache->get($cache_key);
        if (!$data) {
          $accessToken = $this->getAccessToken();
          $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
          $res = json_decode($this->httpGet($url));
          $ticket = $res->ticket;
          if ($ticket) {
            $this->cache->set($cache_key,$ticket,array('life_time' => 7000));
          }
        } else {
          $ticket = $data;
        }
        return $ticket;
    }

    private function getAccessToken() {
        $cache_key = 'wx_access_token_'.$this->appId;
        $data = $this->cache->get($cache_key);
        if (!$data) {
          $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
          $res = @json_decode($this->httpGet($url));
          $access_token = $res->access_token;
          if ($access_token) {
            $this->cache->set($cache_key,$access_token,array('life_time' => 7000));
          }
        } else {
          $access_token = $data;
        }
        return $access_token;
    }

    //授权
    public function authorize(){
        $openid = getCookie("WX_OPENID");
        $authorizeUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId.'&redirect_uri='.urlencode($this->url).'&response_type=code&scope=snsapi_userinfo&state=12234#wechat_redirect';

        if($openid){
            $key = 'access_token_'.$this->appId.'_'.$openid;
            $data = $this->cache->get($key);
            if(!$data){
                redirect( $authorizeUrl );
            }
            if($data['current_time']+$data['expires_in']<CURRENT_TIME){//用refreshtoken换取
                $data = @json_decode($this->httpGet("https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$this->appId."&grant_type=refresh_token&refresh_token=".$data['refresh_token']),true);
                if(isset($data['errcode'])){//重新授权吧
                    redirect( $authorizeUrl );
                }
                $data['current_time'] = CURRENT_TIME;
                //重新写入缓存
                $this->cache->set($key,$data,array('life_time' => 7*86400));
            }
            //根据access_token换取用户信息
            return $this->getUserInfo($data['access_token'],$data['openid']);
        }elseif(getGet('state')){//返回的数据
            $code = getGet('code');
            if(!$code){
                exit('OAuth Error!');
            }
            //获取accessToken
            $data = $this->getOauthAccessToken($code);
            return $this->getUserInfo($data['access_token'],$data['openid']);
        }else{
            redirect( $authorizeUrl );
        }
    }
    //返回页面时做出判断并获取用户信息
    private function getOauthAccessToken($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appId&secret=$this->appSecret&code=$code&grant_type=authorization_code";
        $res = json_decode($this->httpGet($url),true);

        if(isset($res['errcode'])){
            return false;
        }else{
            $res['current_time'] = CURRENT_TIME;
            //设置缓存
            $this->cache->set('access_token_'.$this->appId.'_'.$res['openid'],$res,array('life_time' => 7*86400));
            //setcookie
            $cookie_path = C('cookie.path');
            setcookie('WX_OPENID',$res['openid'],CURRENT_TIME+86400*7,$cookie_path);

            return $res;
        }
    }
    public function getUserInfo($accessToken,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$accessToken&openid=$openid&lang=zh_CN";
        $res = json_decode($this->httpGet($url),true);

        if(isset($res['errcode'])){
            return false;
        }else{
            return $res;
        }
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    
}

//$wxJs = new Weixinapi('wx609674c2d0df418f','dafa0fbed7c0d9bc147e02e9c7e3f7b1');
//准备js的签名相关
//$signPackage = $wxJs->getSignPackage();
