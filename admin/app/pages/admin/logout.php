<?php
require APP.'/objects/admin/common/Common.php';

global $layout;
$layout = null;

$common = new AdminCommon($this->da);
$common->logout();