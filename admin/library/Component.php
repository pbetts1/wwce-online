<?php
class Component{
	public static $componets = array();

	public function load($positionName){
		$components = array();
		if (isset(Component::$componets[$positionName])){
			$components = Component::$componets[$positionName];
		}
		if (!empty($components)){
			foreach ($components as $component){
				if (is_array($component)){
					echo call_user_func($component);
				}else if(is_string($component)){
					echo $component;
				}
			}
		}
	}
        
        public function toString($positionName){
		$components = array();
		if (isset(Component::$componets[$positionName])){
			$components = Component::$componets[$positionName];
		}
		if (!empty($components)){
			foreach ($components as $component){
				if (is_array($component)){
					return call_user_func($component);
				}else if(is_string($component)){
					return $component;
				}
			}
		}
	}
        
	public function set($positionName,$component){
		Component::$componets[$positionName][] = $component;
	}
}