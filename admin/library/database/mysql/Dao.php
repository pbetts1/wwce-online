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
        $arySql = explode('?', $sql);
        $sql = "";
        foreach ($arySql as $k => $v) {
            if (isset($params[$k]))
                $v .= "'" . $this->da->realEscapeString($params[$k]) . "'";
            $sql .= $v;
        }
        $result = &$this->da->result($sql);
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
        $arySql = explode('?', $sql);
        $sql = "";
        foreach ($arySql as $k => $v) {
            if (isset($params[$k]))
                $v .= "'" . $this->da->realEscapeString($params[$k]) . "'";
            $sql .= $v;
        }
        $result = $this->da->result($sql);
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
            while ($row = $result->fetchArray(MYSQL_NUM)) {
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
        return $this->execute("START TRANSACTION");
    }

    /**
     * 
     * Enter description here ...
     */
    public function commit() {
        return $this->execute("COMMIT");
    }

    /**
     * 
     * Enter description here ...
     */
    public function rollback() {
        return $this->execute("ROLLBACK");
    }

}