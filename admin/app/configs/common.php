<?php

define('__IMAGE_UPLOAD_REAL_PATH__', ROOT.'/public/media/images/');
define('__IMAGE_PATH__', __PUBLIC_PATH__.'/media/images/');

define('__FILE_UPLOAD_REAL_PATH__', ROOT.'/public/media/files/');
define ( 'PLUGINS_FOLDER',APP.'/plugins' );
define ('__DATABASE_CONFIG_PATH__',APP.'/configs/database');

//define('__ERROR_REPORTING__',E_ALL);
define('__ERROR_REPORTING__',0);

define('__CHARSET__','utf-8');

define('MOD_REWRITE', 0);

define('__SELF__',substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));

global $layout;
$layout = 'default';

global $default_page;
$default_page = 'admin/login.php';

global $imageExtensions;
$imageExtensions = array(".png", ".jpg", ".gif");

global $fileExtensions;
$fileExtensions = array(".png", ".jpg", ".gif",".doc",".docx",".xls",".xlsx",".zip",".rar",".7z");


global $sysUser;
$sysUser['name'] = "systemAdmin";
$sysUser['password'] = "123456";
$sysUser['enable'] = false;


global $tableAlias;
$tableAlias = array();
$tableAlias['articles'] = 'Articles';
$tableAlias['categories'] = 'Categories';

define('__LOGIN_HTTPS__',false); // make the login page https, instead of http