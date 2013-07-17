<?php
global $config_database;
if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"].'/v_1.4.txt')) {
    redirect('admin/login.php');
}
require APP.'/objects/install/Install.php';

global $layout;
$layout = 'install.php';
$install = new Install($this->da);

$this->com->set('main_content',$install->execute());