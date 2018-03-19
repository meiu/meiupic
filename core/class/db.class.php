<?php
defined('IN_MWEB') || exit('Access denied!');

class DB{
    private $_sql = null;


    public static function instance($dbconfig = ''){
        static $instance;
        $key = toGuidString($dbconfig);
        if (is_null($instance) || !isset($instance[$key])) 
            $instance[$key] = self::factory($dbconfig);
        return $instance[$key];
    }

    public static function factory($dbconfig = ''){
        $db_config = self::parseConfig($dbconfig);
        $filename = strtolower($db_config['adapter']);
        $class = 'DB'.ucfirst($filename);
        if(is_file(CORE_PATH.'driver/db/'.$filename.'.php')){
            require_once(CORE_PATH.'driver/db/'.$filename.'.php');
            $db = new $class($db_config);
        }else{
            trace('数据库Driver('.$db_config['adapter'].')未找到！','Core','ERR');
        }
        return $db;
    }

    /**
     * 分析数据库配置信息，支持数组和DSN
     * @access private
     * @param mixed $db_config 数据库配置信息
     * @return string
     */
    private static function parseConfig($db_config='') {
        if (!empty($db_config) && is_string($db_config)) {
            // 如果DSN字符串则进行解析
            $db_config = self::parseDSN($db_config);
        }elseif(is_array($db_config)) { // 数组配置
            $db_config = array_change_key_case($db_config);
        }elseif(empty($db_config)) {
            $db_config= C('database');
        }
        return $db_config;
    }
    /**
     * DSN解析
     * 格式： mysql://username:passwd@localhost:3306/DbName
     * @static
     * @access public
     * @param string $dsnStr
     * @return array
     */
    public static function parseDSN($dsnStr) {
        if( empty($dsnStr) ){return false;}
        $info = parse_url($dsnStr);
        if($info['scheme']){
            $dsn = array(
            'adapter'      =>  $info['scheme'],
            'dbuser'       =>  isset($info['user']) ? $info['user'] : '',
            'dbpass'       =>  isset($info['pass']) ? $info['pass'] : '',
            'host'         =>  isset($info['host']) ? $info['host'] : '',
            'port'         =>  isset($info['port']) ? $info['port'] : '',
            'dbname'       =>  isset($info['path']) ? substr($info['path'],1) : ''
            );
        }else {
            preg_match('/^(.*?)\:\/\/(.*?)\:(.*?)\@(.*?)\:([0-9]{1, 6})\/(.*?)$/',trim($dsnStr),$matches);
            $dsn = array (
            'adapter'      =>  $matches[1],
            'dbuser'       =>  $matches[2],
            'dbpass'       =>  $matches[3],
            'host'         =>  $matches[4],
            'port'         =>  $matches[5],
            'dbname'       =>  $matches[6]
            );
        }
        $dsn['pconnect'] =  false; //兼容配置信息数组
        $dsn['charset'] =  'utf8';
        $dsn['pre'] =  '';
        return $dsn;
    }

    /**
     * 获取sql，如果sql为空返回$this->_sql;
     * @param  string $sql
     * @return string
     */
    private function getSql($sql = null){
        if($sql == null){
            $sql = $this->_sql;
        }
        if($sql == null){
            trace('Sql不能为空！','DB','WARN');
        }
        return $sql;
    }
    /**
     * 获取所有记录
     * @param  string $sql sql语句，如果为空直接调用$this->_sql
     * @return array
     */
    public function getAsIndex($key,$sql = null){
        $sql = $this->getSql($sql);

        $res = $this->query($sql);
        
        $data = array();
        while ($row = $this->fetch($res)) {
            $data[$row[$key]] = $row;
        }
        $this->free($res);
           
        return $data;
    }
    /**
     * 获取所有记录
     * @param  string $sql sql语句，如果为空直接调用$this->_sql
     * @return array
     */
    public function getAll($sql = null){
        $sql = $this->getSql($sql);

        $res = $this->query($sql);
        
        $data = array();
        while ($row = $this->fetch($res)) {
            $data[] = $row;
        }
        $this->free($res);
           
        return $data;
    }
    /**
     * 获取第一条一个字段的值
     * @param  string $sql sql语句，如果为空直接调用$this->_sql
     * @return mixed
     */
    public function getOne($sql = null)
    {
        $sql = $this->getSql($sql);
        $res = $this->query($sql);
        $row = $this->fetch($res,'NUM');
        $this->free($res);
        return isset($row[0]) ? $row[0] : null;
    }

    /**
     * 执行查询，返回第一条记录
     *
     * @param string $sql
     *
     * @return array
     */
    public function getRow($sql = null)
    {
        $sql = $this->getSql($sql);
        $res = $this->query($sql);

        $row = $this->fetch($res);
        $this->free($res);
        return $row?$row:array();
    }

    /**
     * 执行查询，返回结果集的指定列
     *
     * @param int $col 要返回的列，0 为第一列
     * @param string $sql
     * 
     * @return array
     */
    public function getCol($col = 0,$sql = null)
    {
        $sql = $this->getSql($sql);
        $res = $this->query($sql);

        $data = array();
        while ($row = $this->fetch($res,'NUM')) {
            $data[] = $row[$col];
        }
        $this->free($res);
        return $data;
    }
    /**
     * 获取关联数组
     * @param  string $sql sql语句，如果为空直接调用$this->_sql
     * @return array
     */
    public function getAssoc($sql = null){
        $sql = $this->getSql($sql);

        $res = $this->query($sql);

        $data = array();
        while ($row = $this->fetch($res,'NUM')) {
            $data[$row[0]] = $row[1];
        }
        $this->free($res);
        return $data;
    }
    /**
     * 组装成sql语句
     * @param string $table
     * @param array $conditions
     * @return object DB
     */
    public function select($table,$conditions='1'){

        $result = array();

        if (is_string($conditions)) $conditions = array('where' => $conditions);

        $conditions = $conditions + array(
            'fields' => '*',
            'where' => 1,
            'order' => null,
            'start' => -1,
            'limit' => -1
        );

        extract($conditions);

        if(strpos($table, '#')!==false){
            $table = str_replace('#', '_mytbl::', $table);
        }else{
            $table = '_mytbl::'.$table;
        }
        $sql = "SELECT {$fields} FROM $table WHERE $where";

        if ($order)
            $sql .= " ORDER BY {$order}";

        if (0 <=$start && 0 < $limit){
            $sql .= " LIMIT {$start}, {$limit}";
        }elseif(0 < $limit){
            $sql .= " LIMIT {$limit}";
        }
        $this->_sql = $sql;

        return $this;
    }

    /**
     * Insert
     *
     * @param string $table
     * @param array $data
     * @return boolean
     */
    public function insert($table,$data, $replace = false)
    {
        $table = '_mytbl::'.$table;

        $keys = '';
        $values = '';
        foreach ($data as $key => $value) {
            $keys .= $this->quoteField($key).",";
            $values .= $this->escape($value) . ",";
        }
        $sql = ($replace?"REPLACE":"INSERT")." INTO $table (" . substr($keys, 0, -1) . ") VALUES (" . substr($values, 0, -1) . ");";
        return $this->query($sql);
    }

    /**
     * Update table
     * @param string $table
     * @param array $data
     * @param string $where
     * @return int
     */
    public function update( $table, $data, $where = '0')
    {
        $table = '_mytbl::'.$table;

        $tmp = array();
        foreach ($data as $key => $value) {
            if(is_array($value) && $value[0]=='exp'){
                $tmp[] = $this->quoteField($key). "=" . $this->escape($value[1],false);
            }else{
                $tmp[] = $this->quoteField($key). "=" . $this->escape($value);
            }
        }
        $str = implode(',', $tmp);

        $sql = "UPDATE $table SET " . $str . " WHERE $where";

        return $this->query($sql);
    }

    /**
     * Delete from table
     * @param string $table
     * @param string $where
     * @return int
     */
    public function delete($table,$where = '0')
    {
        $table = '_mytbl::'.$table;

        $sql = "DELETE FROM $table WHERE $where";
        return $this->query($sql);
    }

    /**
     * Count num rows
     *
     * @param string $table
     * @param string $where
     * @return int
     */
    public function count($table,$where = '1')
    {
        $table = '_mytbl::'.$table;

        $sql = "SELECT count(1) AS cnt FROM $table WHERE $where";
        $result = $this->getOne($sql);
        return empty($result) ? 0 : $result;
    }
    /**
     * 过滤数据库不安全的字符
     * @param  string $value
     * @return string
     */
    public function escape($value,$addquote=true){
        if($addquote){
            return "'".addslashes($value)."'";
        }
        return addslashes($value);
    }

    protected function _preparseTable($SQL){
        return str_replace('_mytbl::', $this->dbinfo['pre'], $SQL);
    }
    
}