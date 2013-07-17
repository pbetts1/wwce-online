<?php

/**
 *  Fetches MySQL database rows as objects
 */
class DataAccessResult {

    /**
     * Private
     * $da stores data access object
     */
    private $da;

    /**
     * Private
     * $query stores a query resource
     */
    private $result;

    function __construct(&$da, $result) {
        $this->da = &$da;
        $this->result = $result;
    }

    /**
     * Returns an array from query row or false if no more rows
     * @return mixed
     */
    function fetchArray($resultType = MYSQL_ASSOC) {
        if ($row = mysql_fetch_array($this->result, $resultType)) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 
     * Enter description here ...
     */
    function fetchAssoc() {
        if ($row = mysql_fetch_assoc($this->result)) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 
     * Enter description here ...
     */
    public function fetchRow() {
        if ($row = mysql_fetch_row($this->result)) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 
     * Enter description here ...
     * @param $className
     * @param $params
     */
    function fetchObject($className = null, array $params = null) {
        if ($object = mysql_fetch_object($this->result, $className, $params)) {
            return $object;
        } else {
            return false;
        }
    }
    /**
     * 
     * @return type
     */
    public function getResult(){
        
        return $this->result;
    }
    /**
     * Returns the number of rows affected
     * @return int
     */
    function numRows() {
        return mysql_num_rows($this->result);
    }

    /**
     * Returns false if no errors or returns a MySQL error message
     * @return mixed
     */
    function isError() {
        $error = $this->da->isError();
        if (!empty($error))
            return $error;
        else
            return false;
    }

}