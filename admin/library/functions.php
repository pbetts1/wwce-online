<?php

function isPost() {
	return (count($_POST) > 0) ? true : false;
}

function isGet() {
	return (count($_GET) > 0) ? true : false;
}

function isAjax() {
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false;
}

function redirect($url, $file = 'index.php') {
	if (isset($_GET['apache_mod_rewrite']) && (int)$_GET['apache_mod_rewrite'] == 1){
		header("Location: " . __SELF__ . '/' . $url);
	}else{
		header("Location: " . __SELF__ . '/'.$file.'?wp=' . str_replace('?', '&', $url));
	}
	exit();
}

function removeDir($dirPath) {
	if (!is_dir($dirPath)) {
		throw new Exception("$dirPath must be a directory");
	}
	if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
		$dirPath .= '/';
	}
	$files = glob($dirPath . '*', GLOB_MARK);
	foreach ($files as $file) {
		if (is_dir($file)) {
			removeDir($file);
		} else {
			unlink($file);
		}
	}
	rmdir($dirPath);
}

function strUrl($str, $file = 'index.php'){
	$url = '';

	if (isset($_GET['apache_mod_rewrite']) && (int)$_GET['apache_mod_rewrite'] == 1){
		$url = __SELF__.'/'.$str;
	}else{
		$str = str_replace('?', '&', $str);
		$url = __SELF__.'/'.$file.'?wp='.$str;
	}

	return $url;
}

function recurse_copy($src, $dst) {
	$dir = opendir($src);
	@mkdir($dst);
	while (false !== ( $file = readdir($dir))) {
		if (( $file != '.svn' ) && ( $file != '.' ) && ( $file != '..' )) {
			if (is_dir($src . '/' . $file)) {
				recurse_copy($src . '/' . $file, $dst . '/' . $file);
			} else {
				copy($src . '/' . $file, $dst . '/' . $file);
			}
		}
	}
	closedir($dir);
}

