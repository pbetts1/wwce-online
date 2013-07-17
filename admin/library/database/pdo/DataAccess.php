<?php

/**
 *  A simple class for querying MySQL
 */
class DataAccess {

    /**
     * Private
     * $db stores a database resource
     */
    private $link = false;
    private $db;
    private $stmt;
    private $error = null;

    /**
     * Constucts a new DataAccess object
     * @param $host string hostname for dbserver
     * @param $user string dbserver user
     * @param $pass string dbserver user password
     * @param $db string database name
     */
    public function __construct($host, $user, $pass, $db) {
        try {
            $this->db = $db;
            $this->link = new PDO('mysql:host=' . $host . ';dbname=' . $this->db, $user, $pass);
        } catch (PDOException $e) {
            $this->error = 'Connection failed: ' . $e->getMessage();
        }
    }

    /**
     * Fetches a query resources and stores it in a local member
     * @param $sql string the database query to run
     * @return object DataAccessResult
     */
    public function & result($sql, $params = array()) {
        $this->stmt = $this->link->prepare($sql);
        if ($this->stmt->execute($params) === false) {
            $errors = $this->stmt->errorInfo();
            $this->error = $errors[2];
        }
        $da = new DataAccessResult($this, $this->stmt);
        return $da;
    }

    /**
     * 
     * Enter description here ...
     */
    public function insertId($name = null) {
        return $this->link->lastInsertId($name);
    }

    /**
     * 
     * Enter description here ...
     * @param $charset
     */
    public function setCharset($charset) {
        $this->link->exec("set names utf8");
    }

    /**
     * 
     * Enter description here ...
     * @param $tableName
     */
    public function listFields($tableName) {
        $fields = array();
        $this->stmt = $this->link->prepare("SHOW COLUMNS FROM `" . $tableName . "`");
        $this->stmt->execute();
        while ($field = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
            $fields[] = $field;
        }
        $this->stmt = null;

        return $fields;
    }

    /**
     * 
     * Enter description here ...
     * @param unknown_type $result
     */
    public function freeResult($result = null) {
        $this->stmt->closeCursor();
    }

    /**
     * 
     * Enter description here ...
     */
    public function clientInfo() {
        return $this->link->getAttribute(PDO::ATTR_CLIENT_VERSION);
    }

    /**
     * 
     * Enter description here ...
     */
    public function getHostInfo() {
        return $this->link->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    /**
     * 
     * Enter description here ...
     */
    public function get_server_info() {
        return $this->link->getAttribute(PDO::ATTR_SERVER_INFO);
    }

    /**
     * Close mysql linkection
     */
    public function close() {
        $this->link = null;
    }

    /**
     * 
     * Enter description here ...
     */
    public function begin() {
        return $this->link->beginTransaction();
    }

    /**
     * 
     * Enter description here ...
     */
    public function commit() {
        return $this->link->commit();
    }

    /**
     * 
     * Enter description here ...
     */
    public function rollback() {
        return $this->link->rollBack();
    }

    /**
     * Returns any MySQL errors
     * @return string a MySQL error
     */
    public function isError() {
        return $this->error;
    }

}