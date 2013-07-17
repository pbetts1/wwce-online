<?php

class ScrudDao extends GenericDao {
    
    public $p_fields;

    public function & query($sql, $params = array(),$rs = true) {
        $rows = array();
        $result = & parent::query($sql, $params, $rs);
        if ($result === false) {
            return false;
        } else {
            if ($rs == false){
                return $result;
            }
            while ($row = $result->fetchRow()) {
                $assoc = Array();
                foreach ($this->p_fields as $k => $field) {
                    $aryField = explode('.', $field);
                    $assoc[$aryField[0]][$aryField[1]] = $row[$k];
                }

                $rows[] = $assoc;
            }
        }

        return $rows;
    }
    public function foundRows() {
        $sql = "SELECT FOUND_ROWS() as totalRow";
        $rs = parent::query($sql, array());
        if ($rs === false) {
            return false;
        } else {
            return (int) $rs[0]['totalRow'];
        }
    }

}