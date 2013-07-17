<?php

class GenericDao extends Dao {

    protected $tableName = null;
    protected $fileds = "*";

    /**
     *
     * Enter description here ...
     * @param unknown_type $tableName
     */
    public function __construct($tableName, $da) {
        $this->tableName = $tableName;
        parent::__construct($da);
    }

    /**
     * 
     * Enter description here ...
     */
    public function setTable($tableName) {
        $this->tableName = $tableName;
        $this->fields = "*";
    }

    /**
     * (non-PHPdoc)
     * @see development/rapidp/library/database/mysql/Dao::query()
     */
    public function & query($sql, $params = array(), $rs = false) {
        $rows = array();
        $result = & parent::query($sql, $params);
        if ($result === false) {
            return false;
        } else {
            if ($rs == true) {
                return $result;
            }
            while ($row = $result->fetchAssoc()) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     *
     * Enter description here ...
     * @param $params
     */
    public function find($params = array()) {
        $this->params = $params;
        if (!empty($params['fields'])) {
            $this->fileds = (is_array($params['fields'])) ? implode(',', $params['fields']) : $params['fields'];
        }

        $values = array();
        $sqlFoundRows = '';
        $join = '';
        $conditions = '';
        $group = '';
        $having = '';
        $order = '';
        $limit = '';

        if (!empty($params['found_rows']) && $params['found_rows'] === true) {
            $sqlFoundRows .= 'SQL_CALC_FOUND_ROWS';
        }

        if (!empty($params['join'])) {
            foreach ($params['join'] as $v) {
                $join .= ' ' . strtoupper($v[0]) . ' JOIN ' . $v[1] . ' ON ' . $v[2] . ' ';
            }
        }

        if (!empty($params['conditions'])) {
            if (is_array($params['conditions'])) {
                if (!empty($params['conditions'][0])) {
                    $conditions .= $params['conditions'][0];
                }
                if (!empty($params['conditions'][1]) && is_array($params['conditions'][1])) {
                    foreach ($params['conditions'][1] as $v) {
                        $values[] = $v;
                    }
                }
            } else {
                $conditions .= $params['conditions'];
            }
        }

        if (!empty($params['having'])) {
            if (is_array($params['having'])) {
                if (!empty($params['having'][0])) {
                    $having .= $params['having'][0];
                }
                if (!empty($params['having'][1]) && is_array($params['having'][1])) {
                    foreach ($params['having'][1] as $v) {
                        $values[] = $v;
                    }
                }
            } else {
                $having .= $params['having'];
            }
        }

        if (!empty($params['order'])) {
            $order .= (is_array($params['order'])) ? implode(',', $params['order']) : $params['order'];
        }

        if (!empty($params['group'])) {
            $group .= (is_array($params['group'])) ? implode(',', $params['group']) : $params['group'];
        }
        if (!empty($params['limit']) && (int) $params > 0) {
            $page = (isset($params['page'])) ? (int) $params['page'] : 0;
            if ($page <= 0) {
                $limit .= $params['limit'];
            } else {
                $offset = ($page - 1) * (int) $params['limit'];
                $limit .= $offset . ',' . $params['limit'];
            }
        }

        $conditions = ($conditions != '') ? ' WHERE ' . $conditions : $conditions;
        $group = ($group != '') ? ' GROUP BY ' . $group : $group;
        $having = ($having != '') ? ' HAVING  ' . $having : $having;
        $order = ($order != '') ? ' ORDER BY ' . $order : $order;
        $limit = ($limit != '') ? ' LIMIT ' . $limit : $limit;
        $sql = "SELECT " . $sqlFoundRows . " " . $this->fileds . " FROM `" . $this->tableName . "` " . $join . $conditions . " " . $group . " " . $having . " " . $order . " " . $limit;

        return $this->query($sql, $values);
    }

    /**
     *
     * Enter description here ...
     */
    public function findFirst($params) {
        $params['limit'] = 1;
        if (isset($params['page'])) {
            unset($params['page']);
        }
        $data = $this->find($params);
        if ($data === false) {
            return false;
        } else {
            return (isset($data[0])) ? $data[0] : array();
        }
    }

    public function insert($params) {
        
        $fields = array();
        $data = array();
        $aryFields = $this->da->listFields($this->tableName);
        foreach ($aryFields as $field) {
            $fields[] = $field['Field'];
        }
        $f = array();
        $o = array();
        $v = array();
        foreach ($fields as $field) {
            if (isset($params[$field])) {
                $f[] = $field;
                $o[] = "?";
                $v[] = $params[$field];
            }
        }

        $sql = "INSERT INTO `" . $this->tableName . "` (`" . implode("`,`", $f) . "`) VALUES (" . implode(",", $o) . ")";
        
        
        if ($this->execute($sql, $v) === true) {
            return $this->da->insertId();
        } else {
            return false;
        }
    }

    public function update($params, $conditions) {
        $fields = array();
        $aryFields = $this->da->listFields($this->tableName);
        foreach ($aryFields as $field) {
            $fields[] = $field['Field'];
        }
        $f = array();
        $values = array();
        foreach ($fields as $field) {
            if (isset($params[$field])) {
                $f[] = '`' . $field . '` = ?';
                $values[] = $params[$field];
            }
        }
        $c = '';
        if (!empty($conditions)) {
            if (is_array($conditions)) {
                if (!empty($conditions[0])) {
                    $c .= $conditions[0];
                }
                if (!empty($conditions[1]) && is_array($conditions[1])) {
                    foreach ($conditions[1] as $v) {
                        $values[] = $v;
                    }
                }
            } else {
                $c .= $conditions;
            }
        }
        $c = ($c != '') ? ' WHERE ' . $c : $c;
        $sql = "UPDATE `" . $this->tableName . "` SET " . implode(",", $f) . $c;
        if ($this->execute($sql, $values) === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $data
     */
    public function save($params) {
        $fields = array();
        $aryFields = $this->da->listFields($this->tableName);
        foreach ($aryFields as $field) {
            $fields[] = $field['Field'];
        }
        $f = array();
        $o = array();
        $v = array();
        foreach ($fields as $field) {
            if (isset($params[$field])) {
                $f[] = $field;
                $o[] = "?";
                $v[] = $params[$field];
            }
        }

        $sql = "INSERT INTO `" . $this->tableName . "` (`" . implode("`,`", $f) . "`) VALUES (" . implode(",", $o) . ") ON DUPLICATE KEY UPDATE ";

        $f = array();
        foreach ($fields as $field) {
            if (isset($params[$field])) {
                $f[] = $field . ' = ?';
                $v[] = $params[$field];
            }
        }

        $sql .= implode(',', $f);
        if ($this->execute($sql, $v) === true) {
            return $this->da->insertId();
        } else {
            return false;
        }
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $params
     */
    public function remove($conditions) {
        $c = '';
        $values = array();
        if (!empty($conditions)) {
            if (is_array($conditions)) {
                if (!empty($conditions[0])) {
                    $c .= $conditions[0];
                }
                if (!empty($conditions[1]) && is_array($conditions[1])) {
                    foreach ($conditions[1] as $v) {
                        $values[] = $v;
                    }
                }
            } else {
                $c .= $conditions;
            }
        }
        $c = ($c != '') ? ' WHERE ' . $c : $c;
        $sql = "DELETE FROM `" . $this->tableName . "` " . $c;

        if ($this->execute($sql, $values) === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Enter description here ...
     */
    public function truncate() {
        return $this->execute("TRUNCATE TABLE `" . $this->tableName . "`");
    }

    /**
     *
     * Enter description here ...
     */
    public function foundRows() {
        $sql = "SELECT FOUND_ROWS() as totalRow";
        $rs = $this->query($sql, array());
        if ($rs === false) {
            return false;
        } else {
            return (int) $rs[0][0]['totalRow'];
        }
    }

}