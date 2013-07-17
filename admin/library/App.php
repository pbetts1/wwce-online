<?php
global $config_database;

if (session_id() == '') {
    @session_start();
}
require APP . '/configs/common.php';
error_reporting(__ERROR_REPORTING__);

if (MOD_REWRITE == 1){
	$_GET['apache_mod_rewrite'] = 1;
}else{
	$_GET['apache_mod_rewrite'] = 0;
}

//error_reporting(E_ALL);
if (get_magic_quotes_gpc()) {

    function stripslashes_array($array) {
        return is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);
    }

    $_GET = stripslashes_array($_GET);
    $_POST = stripslashes_array($_POST);
    $_COOKIE = stripslashes_array($_COOKIE);
}


require APP . '/configs/database.php';
require APP . '/configs/messages.php';
require LIBRARY . '/Router.php';
require APP . '/configs/router.php';
require LIBRARY . '/functions.php';
require LIBRARY . '/Component.php';
require LIBRARY . '/Validation.php';
if (isset($config_database['default']['type'])){
    require LIBRARY . '/database/'.strtolower($config_database['default']['type']).'/Dao.php';
}else{
    require LIBRARY . '/database/mysql/Dao.php';
}
require LIBRARY . '/database/GenericDao.php';
require LIBRARY . '/Savant3/Savant3.php';
require LIBRARY . '/Hook.php';

// Future-friendly json_encode
if (!function_exists('json_encode')) {
    require_once LIBRARY . '/JSON.php';

    function json_encode($data) {
        $json = new Services_JSON();
        return( $json->encode($data) );
    }

}

// Future-friendly json_decode
if (!function_exists('json_decode')) {

    function json_decode($data) {
        $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        return( $json->decode($data) );
    }

}


require LIBRARY . '/Auth.php';
require APP . '/configs/auth.php';

global $da;


$da = new DataAccess($config_database['default']['server'],
                        $config_database['default']['user'],
                        $config_database['default']['password'],
                        $config_database['default']['database']);

class App {

    private $com;
    private $da;

    public function __construct() {
        global $da;
        
        $this->da = & $da;
        $this->com = new Component();
    }

    public function execute() {
        global $default_page;
        Router::execute();
        if (!isset($_GET['wp']) || $_GET['wp'] == '/') {
            $_GET['wp'] = $default_page;
        }
        if (!is_file(APP . '/pages/' . $_GET['wp']) ||
                !file_exists(APP . '/pages/' . $_GET['wp'])) {
            require APP . '/pages/error/404.php';
            exit();
        }
        $auth = Auth::singleton();
        $auth->execute();
        $this->loadPage();
        $this->loadLayout();
    }

    private function loadLayout() {
        global $layout;
        if ($layout != null && file_exists(APP . '/layouts/' . $layout)) {
            require APP . '/layouts/' . $layout;
        }
    }

    private function loadPage() {
        require APP . '/pages/' . $_GET['wp'];
    }

}