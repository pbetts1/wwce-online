<?php

class AdminTable {

    private $da;
    private $savant;

    public function __construct($da) {
        $auth = Auth::singleton();
        $auth->checkDatabaseManagement();

        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }

    public function browse() {
        global $config_database;
        $this->savant->config_database = $config_database;
        $dao = new Dao($this->da);
        $this->savant->tables = $dao->listTables();

        return $this->savant->getOutput('browse.tpl.php');
    }

    public function delete() {
        if (!isset($_POST['table']))
            exit;
        $dao = new Dao($this->da);
        $sql = 'DROP TABLE IF EXISTS `' . $_POST['table'] . '`';
        $dao->execute($sql);
        $_GET['table'] = $_POST['table'];

        global $config_database;

        if (isset($_GET['table']) &&
                trim($_GET['table']) != '') {
            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'])) {
                removeDir(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table']);
            }
            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php')) {
                @unlink(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php');
            }
        }
    }

    public function add() {
        $dao = new Dao($this->da);
        $this->savant->tables = $dao->listTables();

        $cs = array();
        $cs[''] = '';
        $dao = new Dao($this->da);
        $rs = $dao->query('SHOW CHARACTER SET');
        while ($row = $rs->fetchAssoc()) {
            $cs[$row['Charset']] = $row['Charset'];
        }

        asort($cs);

        $en = array();
        $en[''] = '';
        $rs = $dao->query('SHOW ENGINES');
        while ($row = $rs->fetchAssoc()) {
            $en[$row['Engine']] = $row['Engine'];
        }
        $this->savant->engines = $en;

        $collations = array();

        foreach ($cs as $key => $value) {
            if (!empty($value)) {
                $collations[$key] = array();
                $rs = $dao->query("SHOW COLLATION LIKE '" . $value . "%'");
                if (!empty($rs)) {
                    while ($row = $rs->fetchAssoc()) {
                        $collations[$key][$row['Collation']] = $row['Collation'];
                    }
                }
            }
        }
        $this->savant->collations = $collations;

        if (isset($_GET['table']) && in_array($_GET['table'], $this->savant->tables)) {
            global $config_database;
            $tblInfo = array();
            $rs = $dao->query("SHOW TABLE STATUS FROM `" . $config_database['default']['database'] . "` WHERE `name` = '" . $_GET['table'] . "'");
            if (!empty($rs)) {
                while ($row = $rs->fetchAssoc()) {
                    $tblInfo[] = $row;
                }
                $this->savant->table_info = $tblInfo[0];
            }

            $colInfo = array();
            $rs = $dao->query("SHOW FULL COLUMNS FROM `" . $_GET['table'] . "`");
            if (!empty($rs)) {
                while ($row = $rs->fetchAssoc()) {
                    $colInfo[] = $row;
                }
                $this->savant->columns_info = $colInfo;
            }
        }


        return $this->savant->getOutput('add.tpl.php');
    }

    public function insert() {
        $var = array();
        $errors = array();

        $dao = new Dao($this->da);

        if (trim($_POST['table_name']) == '') {
            $errors[] = "Please enter the value for Table Name";
        }




        $comma = ",";
        $sql = "CREATE TABLE `" . $_POST['table_name'] . "` (\n";


        $keys = array();
        foreach ($_POST['fields'] as $i => $v) {
            if (isset($v['key'])) {
                $keys[] = $v['name'];
            }
            if ($i == (count($_POST['fields']) - 1)) {
                if (count($keys) <= 0) {
                    $comma = "";
                } else {
                    $comma = ",";
                }
            }
            if (trim($v['length_value']) == '') {
                switch (strtolower(trim($v['type']))) {
                    case 'bit':
                        $v['length_value'] = '1';
                        break;
                    case 'tinyint':
                        $v['length_value'] = '4';
                        break;
                    case 'smallint':
                        $v['length_value'] = '6';
                        break;
                    case 'mediumint':
                        $v['length_value'] = '9';
                        break;
                    case 'int':
                        $v['length_value'] = '11';
                        break;
                    case 'bigint':
                        $v['length_value'] = '20';
                        break;
                    case 'decimal':
                        $v['length_value'] = '10,0';
                        break;
                    case 'char':
                        $v['length_value'] = '50';
                        break;
                    case 'varchar':
                        $v['length_value'] = '255';
                        break;
                    case 'binary':
                        $v['length_value'] = '50';
                        break;
                    case 'varbinary':
                        $v['length_value'] = '255';
                        break;
                    case 'year':
                        $v['length_value'] = '4';
                        break;
                }
            }
            if (trim($v['length_value']) != '') {
                $v['length_value'] = " (" . $v['length_value'] . ") ";
            }

            $null = '';
            if (!isset($v['is_null'])) {
                $null = " NOT NULL ";
            } else {
                $null = " NULL ";
            }

            $def = "";
            if (trim($v['def']) != "") {
                switch (trim($v['def'])) {
                    case 'NULL':
                        if ($null != " NOT NULL ") {
                            $def = " DEFAULT NULL ";
                        }
                        break;
                    case 'USER_DEFINED':
                        $def = " DEFAULT '" . str_replace("'", "\'", $v['user_def']) . "' ";
                        break;
                    case 'CURRENT_TIMESTAMP':
                        $def = " DEFAULT CURRENT_TIMESTAMP ";
                        break;
                }
            }

            if (in_array($v['name'], $keys)) {
                $null = " NOT NULL ";

                if ($def == " DEFAULT NULL ") {
                    $def = "";
                }
            }

            $ai = '';
            if (isset($v['ai'])) {
                $ai = " AUTO_INCREMENT ";
            }

            $collation = "";
            if (trim($v['collation']) != '') {
                $ary = explode('_', trim($v['collation']));
                $collation = " CHARACTER SET " . $ary[0] . " COLLATE " . trim($v['collation']) . " ";
            }

            $sql .= "`" . $v['name'] . "` " . $v['type'] . $v['length_value'] . $collation . $null . $def . $ai . $comma . " \n";

            if ($i == (count($_POST['fields']) - 1)) {
                if (count($keys) > 0) {
                    $sql .= "PRIMARY KEY (`" . implode('`,`', $keys) . "`) \n";
                }
            }
        }

        if (trim($_POST['storage_engine']) != '') {
            $_POST['storage_engine'] = " ENGINE = " . $_POST['storage_engine'] . " ";
        }
        if (trim($_POST['collation']) != '') {
            $ary = explode('_', trim($_POST['collation']));
            $_POST['collation'] = " DEFAULT CHARACTER SET = " . $ary[0] . " DEFAULT COLLATE = " . $_POST['collation'];
        }
        if (trim($_POST['table_comment']) != '') {
            $_POST['table_comment'] = " COMMENT = '" . str_replace("'", "\'", $_POST['table_comment']) . "' ";
        }

        $sql .= ")" . $_POST['storage_engine'] . $_POST['collation'] . $_POST['table_comment'];

        if (count($errors) <= 0) {
            try {
                $dao->execute($sql);
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            $var['error'] = 1;
            $var['messages'] = $errors;
        } else {
            $var['error'] = 0;
        }

        echo json_encode($var);
    }

    public function update() {
        $var = array();
        $errors = array();
        $dao = new Dao($this->da);
        $tables = $dao->listTables();
        if (in_array($_POST['table_name_id'], $tables)) {
            try {
                global $config_database;
                $tblInfo = array();
                $rs = $dao->query("SHOW TABLE STATUS FROM `" . $config_database['default']['database'] . "` WHERE `name` = '" . $_POST['table_name_id'] . "'");
                if (!empty($rs)) {
                    while ($row = $rs->fetchAssoc()) {
                        $tblInfo = $row;
                    }
                }

                $colInfo = array();
                $fields = array();
                $oldKey = array();
                $rs = $dao->query("SHOW FULL COLUMNS FROM `" . $_POST['table_name_id'] . "`");
                if (!empty($rs)) {
                    while ($row = $rs->fetchAssoc()) {
                        $colInfo[] = $row;
                        $fields[] = $row['Field'];
                        if (!empty($row['Key'])) {
                            $oldKey[] = $row['Field'];
                        }
                    }
                }


                $keys = array();
                $prevColumn = '';
                $newColumns = array();
                $q = array();
                foreach ($_POST['fields'] as $i => $v) {
                    $sql = "";
                    if (isset($v['key'])) {
                        $keys[] = $v['name'];
                    }
                    if (trim($v['length_value']) == '') {
                        switch (strtolower(trim($v['type']))) {
                            case 'bit':
                                $v['length_value'] = '1';
                                break;
                            case 'tinyint':
                                $v['length_value'] = '4';
                                break;
                            case 'smallint':
                                $v['length_value'] = '6';
                                break;
                            case 'mediumint':
                                $v['length_value'] = '9';
                                break;
                            case 'int':
                                $v['length_value'] = '11';
                                break;
                            case 'bigint':
                                $v['length_value'] = '20';
                                break;
                            case 'decimal':
                                $v['length_value'] = '10,0';
                                break;
                            case 'char':
                                $v['length_value'] = '50';
                                break;
                            case 'varchar':
                                $v['length_value'] = '255';
                                break;
                            case 'binary':
                                $v['length_value'] = '50';
                                break;
                            case 'varbinary':
                                $v['length_value'] = '255';
                                break;
                            case 'year':
                                $v['length_value'] = '4';
                                break;
                        }
                    }
                    if (trim($v['length_value']) != '') {
                        $v['length_value'] = " (" . $v['length_value'] . ") ";
                    }

                    $null = '';
                    if (!isset($v['is_null'])) {
                        $null = " NOT NULL ";
                    } else {
                        $null = " NULL ";
                    }

                    $def = "";
                    if (trim($v['def']) != "") {
                        switch (trim($v['def'])) {
                            case 'NULL':
                                if ($null != " NOT NULL ") {
                                    $def = " DEFAULT NULL ";
                                }
                                break;
                            case 'USER_DEFINED':
                                $def = " DEFAULT '" . str_replace("'", "\'", $v['user_def']) . "' ";
                                break;
                            case 'CURRENT_TIMESTAMP':
                                $def = " DEFAULT CURRENT_TIMESTAMP ";
                                break;
                        }
                    }

                    if (in_array($v['name'], $keys)) {
                        $null = " NOT NULL ";

                        if ($def == " DEFAULT NULL ") {
                            $def = "";
                        }
                    }

                    $ai = '';
                    if (isset($v['ai'])) {
                        $ai = " AUTO_INCREMENT ";
                    }

                    $collation = "";
                    if (trim($v['collation']) != '') {
                        $ary = explode('_', trim($v['collation']));
                        $collation = " CHARACTER SET " . $ary[0] . " COLLATE " . trim($v['collation']) . " ";
                    }

                    if (isset($v['key']) && $ai != '') {
                        $q[$v['name']] = $v['type'] . $v['length_value'] . $collation . $null . $def . $ai;
                        $ai = '';
                    }

                    if (!empty($v['id'])) {
                        if ($v['name'] == $v['id']) {
                            $sql .= "ALTER TABLE `" . $_POST['table_name_id'] . "` MODIFY COLUMN `" . $v['name'] . "` " . $v['type'] . $v['length_value'] . $collation . $null . $def . $ai;
                        } else {
                            $sql .= "ALTER TABLE `" . $_POST['table_name_id'] . "` CHANGE COLUMN `" . $v['id'] . "` `" . $v['name'] . "` " . $v['type'] . $v['length_value'] . $collation . $null . $def . $ai . $prevColumn;
                        }
                    } else {
                        $sql .= "ALTER TABLE `" . $_POST['table_name_id'] . "` ADD COLUMN `" . $v['name'] . "` " . $v['type'] . $v['length_value'] . $collation . $null . $def . $ai . $prevColumn;
                    }

                    $dao->execute($sql);
                    $prevColumn = " AFTER `" . $v['name'] . "`";
                    $newColumns[] = $v['name'];
                }

                foreach ($fields as $field) {
                    if (!in_array($field, $newColumns)) {
                        $sql = "ALTER TABLE `" . $_POST['table_name_id'] . "` DROP COLUMN `" . $field . "`";
                        $dao->execute($sql);
                    }
                }

                $crs = array_diff($oldKey, $keys);
                if (!empty($crs) || count($oldKey) != count($keys)) {
                    if (count($keys) > 0) {
                        if (empty($oldKey)) {
                            $dao->execute("ALTER TABLE `" . $_POST['table_name_id'] . "` ADD PRIMARY KEY(`" . implode('`,`', $keys) . "`)");
                        } else {
                            $dao->execute("ALTER TABLE `" . $_POST['table_name_id'] . "` DROP PRIMARY KEY, ADD PRIMARY KEY(`" . implode('`,`', $keys) . "`)");
                        }
                    } else {
                        if (count($oldKey) > 0) {
                            $newKeys = array();
                            $rs = $dao->query("SHOW FULL COLUMNS FROM `" . $_POST['table_name_id'] . "`");
                            if (!empty($rs)) {
                                while ($row = $rs->fetchAssoc()) {
                                    if (!empty($row['Key'])) {
                                        $newKeys[] = $row['Field'];
                                    }
                                }
                            }
                            if (!empty($newKeys)) {
                                $dao->execute("ALTER TABLE `" . $_POST['table_name_id'] . "` DROP PRIMARY KEY");
                            }
                        }
                    }
                }

                if (!empty($q)) {
                    foreach ($q as $name => $v1) {
                        $dao->execute("ALTER TABLE `" . $_POST['table_name_id'] . "` MODIFY COLUMN `" . $name . "` " . $v1);
                    }
                }


                if (strtolower($_POST['storage_engine']) != strtolower($tblInfo['Engine'])) {
                    $sql = "ALTER TABLE `" . $_POST['table_name_id'] . "` ENGINE = " . $_POST['storage_engine'];
                    $dao->execute($sql);
                }

                if (strtolower($_POST['collation']) != strtolower($tblInfo['Collation'])) {
                    $ary = explode('_', trim($_POST['collation']));
                    $sql = "ALTER TABLE `" . $_POST['table_name_id'] . "` CONVERT TO CHARACTER SET " . $ary[0] . " COLLATE " . $_POST['collation'];
                    $dao->execute($sql);
                }

                $sql = "ALTER TABLE `" . $_POST['table_name_id'] . "` COMMENT = '" . $_POST['table_comment'] . "'";
                $dao->execute($sql);

                if (strtolower($_POST['table_name_id']) != strtolower($_POST['table_name'])) {
                    $sql = "RENAME TABLE `" . $_POST['table_name_id'] . "` TO  `" . $_POST['table_name'] . "`";
                    $dao->execute($sql);
                }
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            $var['error'] = 1;
            $var['messages'] = $errors;
        } else {
            $var['error'] = 0;
            $_GET['table'] = $_POST['table_name_id'];
            global $config_database;
            if (isset($_GET['table']) &&
                    trim($_GET['table']) != '') {
                if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'])) {
                    removeDir(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table']);
                }
                if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php')) {
                    @unlink(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php');
                }
            }
        }

        echo json_encode($var);
    }

}