<?php
require APP.'/objects/admin/common/Common.php';
require APP.'/objects/admin/menu/Menu.php';

global $layout;
$layout = 'admin/default.php';

$common = new AdminCommon($this->da);
$menu = new AdminMenu($this->da);

$this->com->set('main_menu',$menu->show());
$this->com->set('main_content',$common->dashboard());