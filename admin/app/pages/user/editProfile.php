<?php
require APP.'/objects/admin/menu/Menu.php';
require APP.'/objects/user/profile/Profile.php';
require APP.'/objects/user/menu/Menu.php';
require LIBRARY.'/scrud/Scrud.php';

global $layout;
$layout = 'user/default.php';
$menu = new AdminMenu($this->da);
$profile = new Profile($this->da);

$this->com->set('main_menu',$menu->show('account'));
$this->com->set('main_content',$profile->display());