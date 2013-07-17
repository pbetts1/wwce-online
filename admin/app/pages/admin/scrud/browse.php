<?php
require APP.'/objects/admin/scrud/Scrud.php';
require LIBRARY.'/scrud/Scrud.php';
require APP.'/objects/admin/menu/Menu.php';

global $layout;
$layout = 'admin/scrud/browse.php';
$scrud = new AdminScrud($this->da);
$menu = new AdminMenu($this->da);

$this->com->set('main_menu',$menu->show('browse'));
$this->com->set('main_content',$scrud->browse());