<?php

class Install {

    private $da;
    private $savant;

    public function __construct($da) {
        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }

    private function dispay() {
        $this->savant->errors = array();

        return $this->savant->getOutput('install.tpl.php');
    }

    private function update() {

        global $config_database;
        $errors = array();
        if (!is_writable(__DATABASE_CONFIG_PATH__)) {
            $errors[] = 'Sorry, <b>' . __DATABASE_CONFIG_PATH__ . '</b> directory is not allowed to write.';
        }
        $da = new DataAccess($config_database['default']['server'],
                        $config_database['default']['user'],
                        $config_database['default']['password'],
                        $config_database['default']['database']);
        $error = $da->isError();
        if (!empty($error)) {
            $errors[] = $da->isError();
        }

        $this->savant->errors = $errors;
        if (count($errors) > 0) {
            return $this->savant->getOutput('install.tpl.php');
        } else {
            require dirname(__FILE__) . '/phpMyImporter.php';

            if (!is_dir(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"])) {
                $oldumask = umask(0);
                mkdir(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"], 0777);
                umask($oldumask);
            }

            // install user data
            $dao = new Dao($this->da);
            $tables = $dao->listTables();

            $connection = @mysql_connect($config_database['default']['server'], $config_database['default']['user'], $config_database['default']['password']);


            // install new
            $oldumask = umask(0);
            recurse_copy(dirname(__FILE__) . '/data/user/config', __DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"]);
            umask($oldumask);

            ob_start();
            $filename = dirname(__FILE__) . '/data/user/sql/crud_permissions.sql';
            $compress = false;
            $dump = new phpMyImporter($config_database['default']["database"], $connection, $filename, $compress);
            $dump->utf8 = true;
            $dump->doImport();
            ob_get_clean();

            ob_start();
            $filename = dirname(__FILE__) . '/data/user/sql/crud_histories.sql';
            $compress = false;
            $dump = new phpMyImporter($config_database['default']["database"], $connection, $filename, $compress);
            $dump->utf8 = true;
            $dump->doImport();
            ob_get_clean();

            ob_start();
            $filename = dirname(__FILE__) . '/data/user/sql/crud_user_permissions.sql';
            $compress = false;
            $dump = new phpMyImporter($config_database['default']["database"], $connection, $filename, $compress);
            $dump->utf8 = true;
            $dump->doImport();
            ob_get_clean();
            
            ob_start();
            $filename = dirname(__FILE__) . '/data/user/sql/crud_users.sql';
            $compress = false;
            $dump = new phpMyImporter($config_database['default']["database"], $connection, $filename, $compress);
            $dump->utf8 = true;
            $dump->doImport();
            ob_get_clean();

            // install group
            ob_start();
            $filename = dirname(__FILE__) . '/data/user/sql/crud_groups.sql';
            $compress = false;
            $dump = new phpMyImporter($config_database['default']["database"], $connection, $filename, $compress);
            $dump->utf8 = true;
            $dump->doImport();
            ob_get_clean();

            // install sample data
            if ((int) $_POST['sample_data'] == 1) {
            	// install group
            	ob_start();
            	$filename = dirname(__FILE__) . '/data/sampledata/sql/articles.sql';
            	$compress = false;
            	$dump = new phpMyImporter($config_database['default']["database"], $connection, $filename, $compress);
            	$dump->utf8 = true;
            	$dump->doImport();
            	ob_get_clean();

            	// install group
            	 
            	ob_start();
            	$filename = dirname(__FILE__) . '/data/sampledata/sql/categories.sql';
            	$compress = false;
            	$dump = new phpMyImporter($config_database['default']["database"], $connection, $filename, $compress);
            	$dump->utf8 = true;
            	$dump->doImport();
            	ob_get_clean();

            	$oldumask = umask(0);
            	recurse_copy(dirname(__FILE__) . '/data/sampledata/config', __DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"]);
            	umask($oldumask);
            }

            $oldumask = umask(0);
            file_put_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/v_1.4.txt', "");
            umask($oldumask);

            return $this->savant->getOutput('complete.tpl.php');
        }
    }

    public function execute() {
        if (count($_POST) > 0) {
            return $this->update();
        } else {
            return $this->dispay();
        }
    }

}