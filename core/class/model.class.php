<?php
defined('IN_MWEB') || exit('Access denied!');

class Model{
    protected $_table = '';
    protected $_pk = 'id';
    protected $_fields = '*';
    protected $db = null;

    public function __construct($table){
        $this->_table = $table;
        $this->db = DB::instance();
    }

    public function findRow($conditions = array()){
        return $this->select($conditions)->getRow();
    }

    public function findAll($conditions = array()){
        return $this->select($conditions)->getAll();
    }

    /**
     * Find result
     *
     * @param array $conditions
     * @return db
     */
    public function select($conditions = array())
    {
        if (is_string($conditions)) $conditions = array('where' => $conditions);

        $conditions += array('fields'=>$this->_fields);

        return $this->db->select($this->_table,$conditions);
    }

    /**
     * Insert
     *
     * @param array $data
     * @param string $table
     * @return boolean
     */
    public function insert($data,$replace=false,$table = null)
    {
        if (null == $table) $table = $this->_table;

        $result = $this->db->insert($table,$data,$replace);
        return $result;
    }

    public function loadDefault(){
        $result = $this->db->fields($this->_table);

        $data = array();
        foreach ($result as $key => $value) {
            $data[$value['Field']] = $value['Default'];
        }
        return $data;
    }

    /**
     * Update
     *
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data)
    {
        $where = $this->_pk . '=' . (is_int($id) ? $id : $this->escape($id));

        $result = $this->db->update($this->_table,$data, $where);
        return $result;
    }

    /*批量删除*/
    public function deleteMany($ids, $col = null){
        if (is_null($col)) $col = $this->_pk;

        $where = $col . ' in ('.implode(',',$ids).')';

        $result = $this->db->delete($this->_table,$where);
        return $result;
    }
    /*批量更新*/
    public function updateW($where,$data){
        $result = $this->db->update($this->_table,$data, $where);
        return $result;
    }
    //
    public function deleteW($where){
        $result = $this->db->delete($this->_table,$where);
        return $result;
    }
    /**
     * 载入一条数据. 跟findRow类似
     * @param  int|string $id  id
     * @param  string $fields 调用的字段
     * @param  string $col    筛选的字段
     * @return array
     */
    public function load($id,$fields='*',$col=''){
        if(!$col){
            $col = $this->_pk;
        }
        return $this->select(array('fields' => $fields,'where'=>$col.'='.(is_int($id) ? $id : $this->escape($id))))->getRow();
    }
    /**
     * Delete
     *
     * @param string $where
     * @param string $table
     * @return boolean
     */
    public function delete($id, $col = null)
    {
        if (is_null($col)) $col = $this->_pk;

        $where = $col . '=' . (is_int($id) ? $id : $this->escape($id));

        $result = $this->db->delete($this->_table,$where);
        return $result;
    }
    /**
     * Count result
     *
     * @param string $where
     * @param string $table
     * @return int
     */
    public function count($where='1', $table = null)
    {
        if (null == $table) $table = $this->_table;

        $result = $this->db->count( $table,$where);
        return $result;
    }
    /**
     * Escape string
     *
     * @param string $str
     * @return string
     */
    public function escape($str,$addquote=true)
    {
        return $this->db->escape($str,$addquote);
    }
    /**
     * Last insert ID
     * @return int
     */
    public function insertId(){
        return $this->db->insertId();
    }

    /**
     * Set table Name
     *
     * @param string $table
     */
    public function table($table = null)
    {
        if (!is_null($table)) {
            $this->_table = $table;
            return $this;
        }

        return $this->_table;
    }
}