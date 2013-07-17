<?php
class Router{
	public static $route = array();
	public static function execute(){
		global $default_page;
		if (!isset($_GET['wp']) || empty($_GET['wp'])){
			$_GET['wp'] = $default_page;
		}
		if (isset(Router::$route[$_GET['wp']])){
			$_GET['wp'] = Router::$route[$_GET['wp']];
		}else{
			foreach (Router::$route as $k => $v){
				if (preg_match("|^".$k."$|", $_GET['wp'], $matches)){
					foreach ($matches as $k1 => $v1){
						$_GET['wp'] = str_replace('$'.$k1, $v1, $v);
					}
					$_GET['params'] = $matches;
					break;
				}
			}
		}
	}
}