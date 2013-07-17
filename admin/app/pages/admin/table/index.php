<?php
require APP . '/objects/admin/menu/Menu.php';
require APP . '/objects/admin/table/Table.php';

global $layout;
$layout = 'admin/user/default.php';

$menu = new AdminMenu($this->da);
$table = new AdminTable($this->da);

$this->com->set('main_menu', $menu->show('user'));
$this->com->set('main_content', $table->browse());