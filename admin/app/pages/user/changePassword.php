<?php
require APP.'/objects/admin/menu/Menu.php';
require APP.'/objects/user/menu/Menu.php';
require APP.'/objects/user/password/ChangePassword.php';

global $layout;
$layout = 'user/default.php';
$menu = new AdminMenu($this->da);
$password = new ChangePassword($this->da);

$this->com->set('main_menu',$menu->show('account'));
$this->com->set('main_content',$password->execute());
