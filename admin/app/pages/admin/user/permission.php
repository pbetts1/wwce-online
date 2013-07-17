<?php
require APP.'/objects/admin/user/permission/Permission.php';
require APP.'/objects/admin/menu/Menu.php';

global $layout;
$layout = 'admin/user/default.php';
$permission = new AdminPermission($this->da);
$menu = new AdminMenu($this->da);

$this->com->set('main_menu',$menu->show('user'));
$this->com->set('main_content',$permission->browse());