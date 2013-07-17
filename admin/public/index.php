<?php 
define('ROOT', dirname(dirname(__FILE__)));
define('APP',ROOT. '/app');

$self = substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/'));

define('__PUBLIC_PATH__', $self.'/');
define('__MEDIA_PATH__', $self.'/media/');
define('LIBRARY', ROOT. '/library');
if (!class_exists('App')){
	require LIBRARY . '/App.php';
}
$app = new App();
$app->execute();