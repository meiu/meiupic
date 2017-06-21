<?php
/**
 * $Id: mysqli.php 83 2012-04-17 03:15:45Z lingter $
 * 
 * @author : Lingter
 * @support : http://www.meiu.cn
 * @copyright : (c)2010 meiu.cn lingter@gmail.com
 */

defined('IN_MWEB') || exit('Access denied!');
Class DBPdomysql extends Db{
    /**
     * 数据库连接信息
     *
     * @var Array
     */
    var $dbinfo=null;
    /**
     * 数据库连接句柄
     *
     * @var resource
     */
    var $conn = null;
    /**
     * 最后一次数据库操作的错误信息
     *
     * @var mixed
     */

    var $lasterr = null;
    /**
     * 最后一次数据库操作的错误代码
     *
     * @var mixed
     */
    var $lasterrcode=null;
    /**
     * 指示事务是否启用了事务
     *
     * @var int
     */
    var $_transflag = false;
    /**
     * 启用事务处理情况下的错误
     *
     * @var Array
     */
    var $_transErrors = array();
            
    function __construct($dbinfo){
        if(is_array($dbinfo)){
            $this->dbinfo=$dbinfo;
        }else{
            trace('读取Mysql数据库配置错误！','DB','ERR');
        }
    }

    /**
     * 数据库连接
     *
     * @param Array $dbinfo
     * @return boolean
     */
    function connect($dbinfo=false) {
        
        if ($this->conn && $dbinfo == false) { return true; }
        
        if (!$dbinfo) {
            $dbinfo = $this->dbinfo;
        } else {
            $this->dbinfo = $dbinfo;
        }

        if (!isset($dbinfo['dbpass'])){ $dbinfo['dbpass'] = ''; }
        
        if(!$dbinfo['dbname']) {
            trace('丢失数据库名！','DB','ERR');
        }
        
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s',
            $dbinfo['host'],
            isset($dbinfo['port']) ? $dbinfo['port'] : 3306,
            $dbinfo['dbname']
        );

        $this->conn = new PDO($dsn, $dbinfo['dbuser'], $dbinfo['dbpass']);

        if (!$this->conn){
            trace('连接至数据库服务器失败：('.$dbinfo['host'].','.$dbinfo['dbuser'].')！','DB','ERR');
        }
        
        if (isset($dbinfo['charset']) && $dbinfo['charset'] != '') {
            $charset = $dbinfo['charset'];
        } 
        
        if($this->version() > '4.1' && $charset != '') {
            $this->conn->exec("SET names '".$charset."'");
        }

        if($this->version() > '5.0') {
            $this->conn->exec('SET sql_mode=""');
        }
        
        return true;
    }

    /**
     * 关闭数据库连接
     *
     */
    function close() {
        $this->conn = null;
    }
    
    function quoteField($tableName){
        if (substr($tableName, 0, 1) == '`') { return $tableName; }
        return '`' . $tableName . '`';
    }
    
    function escape($value,$addquote=true){
        if(!$this->conn){
            $this->connect();
        }
        
        if (is_bool($value)) { return $value ? 1:0; }
        if (is_null($value)) { return 'NULL'; }
        
        $value = stripslashes($value);

        if($addquote){
            return $this->conn->quote($value);
        }
        return addslashes($value);
    }
    /**
     * 直接查询Sql
     *
     * @param String $SQL
     * @return Mix
     */
    function query($SQL) {
        if(!$this->conn){
            $this->connect();
        }
        $SQL = $this->_preparseTable($SQL);
        $query = $this->conn->prepare($SQL);
        N('db',1);

        if (!$query){
            $error = $this->conn->errorInfo();
            $this->lasterr = $error[2];
            $this->lasterrcode = $error[0];
            if($this->_transflag){
                $this->_transErrors[]['sql'] = $SQL;
                $this->_transErrors[]['errcode'] = $this->lasterrcode;
                $this->_transErrors[]['err'] = $this->lasterr;
            }else{
                trace( $SQL .' ERROR_INFO:'.$this->lasterrcode.','.$this->lasterr,'DB','SQL');
            }
            return false;
        }else{
            $query->execute();
            $this->lasterr = null;
            $this->lasterrcode = null;

            $this->queryID = $query;

            return $query;
        }
    }

    public function fields($table){
        $rows = $this->getAll('SHOW COLUMNS FROM '.$table);
        return $rows;
    }
    
    function free($query){
        $query->closeCursor();
    }

    /**
     * Fetch one row result
     *
     * @param string $type
     * @return mixd
     */
    public function fetch($query,$type = 'ASSOC')
    {
        $type = strtoupper($type);

        switch ($type) {
            case 'ASSOC':
                $func = PDO::FETCH_ASSOC;
                break;
            case 'NUM':
                $func = PDO::FETCH_NUM;
                break;
            case 'OBJECT':
                $func = PDO::FETCH_OBJ;
                break;
            default:
                $func = PDO::FETCH_ASSOC;
        }

        return $query->fetch($func);
    }
    /**
     * 返回最近一次数据库操作受到影响的记录数
     *
     * @return int
     */
    function affectedRows() {
        if(isset($this->queryID) && $this->queryID)
        return $this->queryID->rowCount();
    }

    /**
     * 获取记录集条数
     *
     * @param resouce $query
     * @return Int
     */
    function numRows($query) {
        $rows = $query->rowCount();
        return $rows;
    }
    /*function numRows($sql) {
        return $this->getOne('select count(*) from ('.$sql.') as numtable');
    }*/
    /**
     * 获取当前mysql的版本号
     *
     * @return String
     */
    function version() {
        return $this->conn->getAttribute(PDO::ATTR_SERVER_VERSION);
    }
    /**
     * 获得刚插入数据的ID号
     *
     * @return Int
     */
    function insertId() {
        $id = $this->conn->lastInsertId();
        return $id;
    }
    /**
     * 启动事务
     */
    function startTrans()
    {
        $rs = $this->query('START TRANSACTION');
        $this->_transflag = true;
        $this->_transErrors = array();
        return $rs;
    }

    /**
     * 提交事务
     *
     */
    function commit()
    {
        $this->_transflag = false;
        $rs = $this->query('COMMIT');
        return $rs;
    }
    /**
     * 回滚事务
     *
     */
    function rollback(){
        $this->_transflag = false;
        $rs = $this->query('ROLLBACK');
        return $rs;
    }
    
    function getTransErrors(){
        $errors = $this->_transErrors;
        if(is_array($errors)){
            foreach($errors as $error){
                trace($error['sql'] .' ERROR_INFO:'.$error['errcode'].','.$error['err'],'DB','ERR');
            }
        }
    }
}
