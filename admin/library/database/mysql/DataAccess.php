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

    /**
     * Constucts a new DataAccess object
     * @param $host string hostname for dbserver
     * @param $user string dbserver user
     * @param $pass string dbserver user password
     * @param $db string database name
     */
    public function __construct($host, $user, $pass, $db) {
        $this->db = $db;
        $this->link = @mysql_connect($host, $user, $pass);
        if ($this->link !== false){
            mysql_select_db($db, $this->link);
        }
    }

    /**
     * Fetches a query resources and stores it in a local member
     * @param $sql string the database query to run
     * @return object DataAccessResult
     */
    public function & result($sql) {
    	$da = new DataAccessResult($this, mysql_query($sql, $this->link));
        return $da;
    }

    /**
     * 
     * Enter description here ...
     */
    public function insertId() {
        return mysql_insert_id($this->link);
    }

    /**
     * 
     * Enter description here ...
     */
    public function clientEncoding() {
        return mysql_client_encoding($this->link);
    }

    /**
     * 
     * Enter description here ...
     * @param $charset
     */
    public function setCharset($charset) {
        mysql_set_charset($charset, $this->link);
    }

    /**
     * 
     * Enter description here ...
     * @param $str
     */
    public function realEscapeString($str) {
        return mysql_real_escape_string($str, $this->link);
    }

    /**
     * 
     * Enter description here ...
     */
//    public function listTables(){
//        $tables = array();
//        $result = mysql_list_tables($this->db,$this->link);
//        $num_rows = mysql_num_rows($result);
//        for ($i = 0; $i < $num_rows; $i++) {
//            $tables[] = mysql_tablename($result, $i);
//        }
//        
//        return $tables;
//    }
    /**
     * 
     * Enter description here ...
     * @param $tableName
     */
    public function listFields($tableName) {
        $fields = array();
        $result = mysql_query("SHOW COLUMNS FROM `" . $tableName . "`", $this->link);
        if (mysql_num_rows($result) > 0) {
            while ($field = mysql_fetch_assoc($result)) {
                $fields[] = $field;
            }
        }

        return $fields;
    }

    /**
     * 
     * Enter description here ...
     * @param unknown_type $result
     */
    public function freeResult($result) {
        mysql_free_result($result);
    }

    /**
     * 
     * Enter description here ...
     */
    public function clientInfo() {
        return mysql_get_client_info();
    }

    /**
     * 
     * Enter description here ...
     */
    public function getHostInfo() {
        return mysql_get_host_info($this->link);
    }

    /**
     * 
     * Enter description here ...
     */
    public function getProtoInfo() {
        return mysql_get_proto_info($this->link);
    }

    /**
     * 
     * Enter description here ...
     */
    public function get_server_info() {
        return mysql_get_server_info($this->link);
    }

    /**
     * 
     * Enter description here ...
     */
    public function info() {
        return mysql_info($this->link);
    }

    /**
     * Close mysql linkection
     */
    public function close() {
        mysql_close($this->link);
    }

    /**
     * Returns any MySQL errors
     * @return string a MySQL error
     */
    public function isError() {
        if ($this->link !== false){
            return mysql_error($this->link);
        }else{
            return 'Could not connect to mysql server. Please the config again.';
        }
    }

}