<?php

/**
 *  PDO Fetches MySQL database rows as objects
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
    function fetchArray($resultType = PDO::FETCH_ASSOC) {
        if ($row = $this->result->fetch($resultType)) {
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
        if ($row = $this->result->fetch(PDO::FETCH_ASSOC)) {
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
        if ($row = $this->result->fetch(PDO::FETCH_NUM)) {
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
    function fetchObject() {
        if ($row = $this->result->fetch(PDO::FETCH_OBJ)) {
            return $row;
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
        return $this->result->rowCount();
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