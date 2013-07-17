<?php

class Auth {

    private static $instance;
    public static $public = array();

    public static function singleton() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    public function execute() {
        global $config_database;
        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/v_1.4.txt')) {
            $_GET['wp'] = 'install/index.php';
        } else {
            if (!in_array($_GET['wp'], Auth::$public)) {
                if (!isset($_SESSION['CRUD_AUTH']) &&
                        $_GET['wp'] != 'admin/login.php') {
                    redirect('admin/login.php');
                }
            }
        }
    }

    public function checkGroupManageFlag() {
        if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 0 &&
        (int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 0) {
            redirect('error/no_access.php');
        }
    }
    
    public function checkUserManagement(){
    	if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] != 1 && 
    		(int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] != 3 &&
    		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] != 1 && 
    		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] != 3) {
    		redirect('error/no_access.php');
    	}
    }
    
    public function checkDatabaseManagement(){
    	if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] != 2 &&
    	(int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] != 3 && 
    	(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] != 2 &&
    	(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] != 3) {
    		redirect('error/no_access.php');
    	}
    }

    public function checkConfigPermission() {
    	$permissions = $this->getPermissionType();
        if (!in_array(5, $permissions)) {
            redirect('error/no_access.php');
        }
    }

    public function checkBrowsePermission() {
    	$permissions = $this->getPermissionType();
    	if (!isset($_GET['xtype'])) {
    		if (isset($_SESSION['auth_token_xtable'])) {
    			unset($_SESSION['auth_token_xtable']);
    		}
    		if (isset($_SESSION['xtable_search_conditions'])) {
    			unset($_SESSION['xtable_search_conditions']);
    		}
    		$_GET['xtype'] = 'index';
    	}
    	switch (strtolower($_GET['xtype'])) {
    		case 'index':
    			if (!in_array(4, $permissions)) {
    				redirect('error/no_access.php');
    			}
    			break;
    		case 'form':
    		case 'confirm':
    		case 'update':
    			if (isset($_REQUEST['key'])){
    				if (!in_array(2, $permissions)) {
    					redirect('error/no_access.php');
    				}
    			}else{
    				if (!in_array(1, $permissions)) {
    					redirect('error/no_access.php');
    				}
    			}
    			break;
    		case 'del':
    		case 'delFile':
    		case 'delconfirm':
    			if (!in_array(3, $permissions)) {
    				redirect('error/no_access.php');
    			}
    			break;
    	}
    }

    public function getPermissionType($table = null) {
        global $da;
        if ($table == null) {
            if (isset($_POST['table'])) {
                $table = $_POST['table'];
            } else if (isset($_GET['table'])) {
                $table = $_GET['table'];
            }
        }
        $rs = array();
        if (isset($_SESSION['CRUD_AUTH']['group']['id'])) {
            $pDao = new GenericDao('crud_permissions', $da);
            $params = array();
            $params['conditions'] = array('group_id = ? and table_name = ?', array((int) $_SESSION['CRUD_AUTH']['group']['id'], $table));
            $params['fields'] = array('permission_type');
            $rs = $pDao->find($params);
        }
        $permissions = array();
        if (!empty($rs)){
        	foreach ($rs as $v){
        		$permissions[] = $v['permission_type'];
        	}
        }
        
        if (isset($_SESSION['CRUD_AUTH']['id'])) {
        	$pDao = new GenericDao('crud_user_permissions', $da);
        	$params = array();
        	$params['conditions'] = array('user_id = ? and table_name = ?', array((int) $_SESSION['CRUD_AUTH']['id'], $table));
        	$params['fields'] = array('permission_type');
        	$rs = $pDao->find($params);
        }
        if (!empty($rs)){
        	foreach ($rs as $v){
        		if (!in_array($v['permission_type'], $permissions)){
        			$permissions[] = $v['permission_type'];
        		}
        	}
        }
        
        
        return $permissions;
    }

}