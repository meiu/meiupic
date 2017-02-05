<?php

defined('IN_MWEB') || exit('Access denied!');

class StorageQiniu extends Storage
{    
    const QINIU_UP_HOST    = 'http://up.qiniu.com';
    const QINIU_RS_HOST    = 'http://rs.qiniu.com';

    private $_setting = array(
        'bucket' => 'meiupic',
        'access_key' => '',
        'secret_key' => '',
        'url_pre' => ''
    );

    public function __construct($config){
        if(is_array($config)){
            $this->_setting = array_merge($this->_setting, $config);
        }
    }

    protected function upload($file, $name, $token = null)
    {
        if ( NULL === $token ) 
        {
            $token = $this->uploadToken($this->_setting['bucket']);
        }

        if ( !file_exists($file) ) 
        {
            return false;
        }
        $hash = hash_file('crc32b', $file);
        $array = unpack('N', pack('H*', $hash));

        $postFields = array(
            'key'   => $name,
            'crc32' => sprintf('%u', $array[1]),
        );

        list($contentType, $body) = $this->getMultiData($token, $file, $postFields);

        $curl = new curl;
        $curl->headers = array(
            'Content-type: '.$contentType
        );
        return $curl->post(self::QINIU_UP_HOST,$body);
    }

    protected function uploadToken($config = array())
    {
        if ( is_string($config) ) 
        {
            $scope = $config;
            $config = array();
        }
        else
        {
            $scope = $config['scope'];
        }
        $config['scope'] = $scope;
        //硬编码，需修改。
        $config['deadline'] = time() + 3600;
        foreach ( $this->activeUploadSettings($config) as $key => $value ) 
        {
            if ( $value ) 
            {
                $config[$key] = $value;
            }
        }

        //build token
        $body = json_encode($config);
        $body = $this->encode($body);
        $sign = hash_hmac('sha1', $body, $this->_setting['secret_key'], true);
        return $this->_setting['access_key'] . ':' . $this->encode($sign) . ':' .$body;
    }

    protected function getMultiData($token, $file, array $params)
    {
        if (isset($params['key']) && empty($params['key'])) {
            unset($params['key']);
        }
        $fields = array_merge(['token' => $token], $params);
        if (!file_exists($file)) {
            sprintf('%s does not exists.', $file);
            exit;
        }
        $fileInfo = pathinfo($file);
        $fname = isset($fields['key']) ? $fields['key'] : $fileInfo['basename'];
        $files = [
            [
                'file', 
                $fname, 
                file_get_contents($file)
            ],
        ];
        return $this->buildMultipartForm($fields, $files);
    }

    public function delete($file)
    {
        $bucket = $this->_setting['bucket'];

        $url = self::QINIU_RS_HOST .'/delete/' . $this->encode("$bucket:$file");

        $token = $this->accessToken($url);
        
        $curl = new curl;
        $curl->headers = array(
            'Authorization: QBox '. $token,
            'Content-Type:   application/x-www-form-urlencoded'
        );
        $result = $curl->post($url,'');
        if($result){
            return false;
        }else{
            return true;
        }
    }

    protected function buildMultipartForm($fields, $files)
    {
        $data = [];
        $mimeBoundary = md5(microtime());
        foreach ($fields as $name => $value) {
            array_push($data, '--' . $mimeBoundary);
            array_push($data, sprintf(
                'Content-Disposition: form-data; name="%s"', 
                $name
            ));
            array_push($data, '');
            array_push($data, $value);
        }
        foreach ($files as $file) {
            array_push($data, '--' . $mimeBoundary);
            list($name, $fileName, $fileBody) = $file;
            $fileName = $this->escapeQuotes($fileName);
            array_push($data, sprintf(
                'Content-Disposition: form-data; name="%s"; filename="%s"', 
                $name, 
                $fileName)
            );
            array_push($data, 'Content-Type: application/octet-stream');
            array_push($data, '');
            array_push($data, $fileBody);
        }
        array_push($data, '--' . $mimeBoundary . '--');
        array_push($data, '');
        $body = implode("\r\n", $data);
        $contentType = 'multipart/form-data; boundary=' . $mimeBoundary;
        return [$contentType, $body];
    }
    protected function escapeQuotes($string)
    {
        $find = ["\\", "\""];
        $replace = ["\\\\", "\\\""];
        return str_replace($find, $replace, $string);
    }
    protected function encode($str){
        $trans = array("+" => "-", "/" => "_");
        return strtr(base64_encode($str), $trans);
    }

    public function accessToken($url, $body = false)
    {
        $url = parse_url($url);
        $result = '';
        if (isset($url['path'])) {
            $result = $url['path'];
        }
        if (isset($url['query'])) {
            $result .= '?' . $url['query'];
        }
        $result .= "\n";
        if ($body) {
            $result .= $body;
        }
        $sign = hash_hmac('sha1', $result, $this->_setting['secret_key'], true);
        return $this->_setting['access_key'] . ':' . $this->encode($sign);
    }

    public function uploadSettings()
    {
        return array(
            'scope','deadline','callbackUrl', 'callbackBody', 'returnUrl',
            'returnBody', 'asyncOps', 'endUser', 'exclusive', 'detectMime',
            'fsizeLimit', 'saveKey', 'persistentOps', 'persistentNotifyUrl'
        );
    }

    protected function activeUploadSettings($array)
    {
        return array_intersect_key($array, array_flip($this->uploadSettings()));
    }

    public function save($src,$dst,$delete_src=true){
        $result = $this->upload($src,$dst);
        $result = @json_decode($result,true);
        if($result && isset($result['key'])){
            //删除原文件
            if($delete_src){
                @unlink($src);
            }
            return true;
        }else{
            return false;
        }
    }

    public function localfile($path){
        $localpath = C('tmp_dir').'/'.md5($path).'.tmp';
        if(file_exists($localpath)){
            return $localpath;
        }
        //获取远程的图片
        $content = file_get_contents($this->_setting['url_pre'].$path);
        file_put_contents($localpath,$content);
        
        return $localpath;
    }

    public function getUrl($path){
        return $this->_setting['url_pre'].$path;
    }

    public function getThumb($path,$w,$h,$t,$placeholder){
        return $this->_setting['url_pre'].$path."?imageView/$t/w/$w/h/$h";
    }
}