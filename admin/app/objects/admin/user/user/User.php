<?php

class AdminUser {

    private $da;
    private $savant;

    public function __construct($da) {
        $auth = Auth::singleton();
        $auth->checkUserManagement();
        
        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }

    public function browse() {
        global $config_database;

        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php')) {
            exit;
        }

        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php'));
        $conf = unserialize($content);

        $scurd = new Scurd($_GET['table'], $conf, $this->da);

        $this->savant->content = $scurd->process();

        return $this->savant->getOutput('browse.tpl.php');
    }

}