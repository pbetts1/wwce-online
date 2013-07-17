<?php
if (__LOGIN_HTTPS__ === true){
	$secure_connection = false;
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
			|| $_SERVER['SERVER_PORT'] == 443) {

		$secure_connection = true;
	}

	if ($secure_connection == false){
		header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		exit;
	}
}

require APP.'/objects/admin/common/Common.php';

global $layout;
$layout = 'admin/login.php';

$common = new AdminCommon($this->da);

$this->com->set('main_content',$common->login());