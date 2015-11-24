<?php
namespace Lib;

class Db {

	private $url = 'mysql:host=localhost;dbname=news';
	private $username = 'root';
	private $pass = '';

    private $db = null;
    
    private $stmt = null;
    private $params = array();
    private $sql;
    
    public function __construct() 
    {
        $this->db = new \PDO($this->url, $this->username, $this->pass, [
			\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
		]);
    }
    
    /**
     * 
     * @param string $sql
     * @param array $params
     * @param options $pdoOptions
     * @return \Lib\Db
     */
    public function prepare($sql, $params = array(), $pdoOptions = array())
    {
        $this->stmt = $this->db->prepare($sql, $pdoOptions);
        $this->params = $params;
        $this->sql = $sql;
        
        return $this;
    }
    /**
     * 
     * @param array $params
     * @return \Lib\Db
     */
    public function execute($params = array())
    {
        if (!empty($params)) {
            $this->params = $params;
        }
        
        $this->stmt->execute($this->params);
        
        return $this;
    }
    
    public function fetchAllAssoc() {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function fetchRowAssoc() {
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function fetchAllNum() {
        return $this->stmt->fetchAll(\PDO::FETCH_NUM);
    }
    public function fetchRowNum() {
        return $this->stmt->fetch(\PDO::FETCH_NUM);
    }
    public function fetchAllObj() {
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    public function fetchRowObj() {
        return $this->stmt->fetch(\PDO::FETCH_OBJ);
    }
    public function fetchAllColunm($column) {
        return $this->stmt->fetchAll(\PDO::FETCH_COLUMN, $column);
    }
    public function fetchRowColumn($column) {
        return $this->stmt->fetch(\PDO::FETCH_COLUMN, $column);
    }
    public function fetchAllClass($class) {
        return $this->stmt->fetchAll(\PDO::FETCH_CLASS, $class);
    }
    public function fetchRowClass($class) {
        return $this->stmt->fetch(\PDO::FETCH_CLASS, $class);
    }
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
    public function getAffectedRows() {
        return $this->stmt->rowCount();
    }
    public function getSTMT() {
        return $this->stmt;
    }
}