<?php

require_once dirname(__FILE__) . '/DataAccess.php';
require_once dirname(__FILE__) . '/DataAccessResult.php';

class Dao {

    /**
     * Private
     * $da stores data access object
     */
    protected $da;

    /**
     * Constructs the Dao
     * @param $da instance of the DataAccess class
     */
    public function __construct(& $da) {
        $this->da = $da;
    }

    /**
     * For SELECT queries
     * @param $sql the query string
     * @return mixed either false if error or object DataAccessResult
     */
    public function & query($sql, $params = array()) {
        $result = &$this->da->result($sql, $params);
        if ($error = $result->isError()) {
            throw new Exception($error);
            return false;
        } else {
            return $result;
        }
    }

    /**
     * For INSERT, UPDATE and DELETE queries
     * @param $sql the query string
     * @return boolean true if success
     */
    public function execute($sql, $params = array()) {
        $result = $this->da->result($sql, $params);
        if ($error = $result->isError()) {
            throw new Exception($error);
            return false;
        } else {
            return true;
        }
    }
    
    public function listTables() {
        $result = $this->query('SHOW TABLES');
        if ($result === false) {
            return false;
        } else {
            $rows = array();
            while ($row = $result->fetchArray(PDO::FETCH_NUM)) {
                $rows[] = $row[0];
            }
        }

        return $rows;
    }

    /**
     * 
     * Enter description here ...
     */
    public function begin() {
        return $this->da->begin();
    }

    /**
     * 
     * Enter description here ...
     */
    public function commit() {
        return $this->da->commit();
    }

    /**
     * 
     * Enter description here ...
     */
    public function rollback() {
        return $$this->da->rollback();
    }

}