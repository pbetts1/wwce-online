<?php
require APP.'/objects/admin/user/permission/Permission.php';

global $layout;
$layout = null;

$permission = new AdminPermission($this->da);
$permission->saveUser();