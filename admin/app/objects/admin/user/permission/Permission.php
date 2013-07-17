<?php

class AdminPermission {

    private $da;
    private $savant;

    public function __construct($da) {
        $auth = Auth::singleton();
        $auth->checkUserManagement();
        
        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }

    public function browse() {
        global $config_database;
        
        $dao = new Dao($this->da);
        $this->savant->tables = $dao->listTables();
        
        $groupDao = new GenericDao('crud_groups', $this->da);
        $groups = $groupDao->find();
        $this->savant->groups = $groups;
        
        $pDao = new GenericDao('crud_permissions', $this->da);
        
        $rs = $pDao->find();
        $pt = array();
        if (!empty($rs)){
            foreach($rs as $k => $v){
                $pt[$v['group_id'].'_'.$v['table_name'].'_'.$v['permission_type']] = $v['permission_type'];
            }
        }
        
        $this->savant->pt = $pt;

        return $this->savant->getOutput('browse.tpl.php');
    }
    
    public function user_browse(){
    	return $this->savant->getOutput('user_browse.tpl.php');
    }
    
    public function user_json(){
    	$userDao = new GenericDao('crud_users', $this->da);
    	
    	
    	if (!isset($_GET['id'])){
    		$params = array();
    		$params['fields'] = array('id','user_name');
    		$params['conditions'] = array('user_name like ?',array("%".$_GET['q']."%"));
    		$rs = $userDao->find($params);
    		echo $_GET['callback'].'('.json_encode($rs).')';
    	}else{
    		
    		$dao = new Dao($this->da);
    		$rs = $dao->listTables();
    		
    		$tables = array();
    		foreach($rs as $v){
    			if (strpos($v, 'crud_') !== false)	continue;
    			$tables[] = $v;
    		}
    		
    		$this->savant->tables = $tables;
    		
    		$params = array();
    		$params['fields'] = array('id','user_name','user_manage_flag');
    		$params['conditions'] = array('id = ?',array($_GET['id']));
    		$rs = $userDao->findFirst($params);
    		
    		$this->savant->user = $rs;
    		
    		$pDao = new GenericDao('crud_user_permissions', $this->da);
    		$params = array();
    		$params['conditions'] = array('user_id = ?',array($_GET['id']));
    		
    		$rs = $pDao->find($params);
    		$pt = array();
    		if (!empty($rs)){
    			foreach($rs as $k => $v){
    				$pt[$v['user_id'].'_'.$v['table_name'].'_'.$v['permission_type']] = $v['permission_type'];
    			}
    		}
    		
    		$this->savant->pt = $pt;
    		
    		echo  $this->savant->getOutput('user_permission.tpl.php');
    	}
    }
    
    public function save(){
        $groupDao = new GenericDao('crud_groups', $this->da);
        $pDao = new GenericDao('crud_permissions', $this->da);
        $pDao->execute('TRUNCATE TABLE `crud_permissions`',array());
        if (count($_POST['data']) > 0){
            $data = $_POST['data'];
            foreach ($data as $k => $v) {
                $group = array();
                $group['group_manage_flag'] = $v['group_manage_flag'];
                $groupDao->update($group,array('id = ?',array($v['group_id'])));
                if ($v['group_id'] == $_SESSION['CRUD_AUTH']['group']['id']){
                	$_SESSION['CRUD_AUTH']['group']['group_manage_flag'] = $v['group_manage_flag'];
                }
                if (count($v['tables']) > 0){
                    $tables = $v['tables'];
                    foreach ($tables as $k1 => $v1){
                    	if (count($v1['permission_type']) > 0){
                    		foreach ($v1['permission_type'] as $permission){
                    			if ((int)$permission > 0){
			                        $p = array();
			                        $p['group_id'] = $v['group_id'];
			                        $p['table_name'] = $v1['table_name'];
			                        $p['permission_type'] = $permission;
			                        $pDao->save($p);
                    			}
                    		}
                    	}
                    }
                }
            }
        }
        
        $ary = array(1,2,3,4);
        foreach ($data as $k => $v) {
        	foreach ($ary as $v1){
        		if ($v['group_manage_flag'] == 1 || $v['group_manage_flag'] == 3){
	        		$p = array();
	        		$p['group_id'] = $v['group_id'];
	        		$p['table_name'] = 'crud_users';
	        		$p['permission_type'] = $v1;
	        		$pDao->save($p);
        		}
        	}
        }
        foreach ($data as $k => $v) {
        	foreach ($ary as $v1){
        		if ($v['group_manage_flag'] == 1 || $v['group_manage_flag'] == 3){
        			$p = array();
        			$p['group_id'] = $v['group_id'];
        			$p['table_name'] = 'crud_groups';
        			$p['permission_type'] = $v1;
        			$pDao->save($p);
        		}
        	}
        }
    }
    
    public function saveUser(){
    	$userDao = new GenericDao('crud_users', $this->da);
    	$pDao = new GenericDao('crud_user_permissions', $this->da);
    	$pDao->execute('TRUNCATE TABLE `crud_user_permissions`',array());
    	if (count($_POST['data']) > 0){
    		$data = $_POST['data'];
    		foreach ($data as $k => $v) {
    			$user = array();
    			$user['user_manage_flag'] = $v['user_manage_flag'];
    			$userDao->update($user,array('id = ?',array($v['user_id'])));
    			if ($v['user_id'] == $_SESSION['CRUD_AUTH']['id']){
    				$_SESSION['CRUD_AUTH']['user_manage_flag'] = $v['user_manage_flag'];
    			}
    			if (count($v['tables']) > 0){
    				$tables = $v['tables'];
    				foreach ($tables as $k1 => $v1){
    					if (count($v1['permission_type']) > 0){
    						foreach ($v1['permission_type'] as $permission){
    							if ((int)$permission > 0){
    								$p = array();
    								$p['user_id'] = $v['user_id'];
    								$p['table_name'] = $v1['table_name'];
    								$p['permission_type'] = $permission;
    								$pDao->save($p);
    							}
    						}
    					}
    				}
    			}
    		}
    	}
    	
    	$ary = array(1,2,3,4);
    	foreach ($data as $k => $v) {
    		foreach ($ary as $v1){
    			if ($v['user_manage_flag'] == 1 || $v['user_manage_flag'] == 3){
    				$p = array();
    				$p['user_id'] = $v['user_id'];
    				$p['table_name'] = 'crud_users';
    				$p['permission_type'] = $v1;
    				$pDao->save($p);
    			}
    		}
    	}
    	foreach ($data as $k => $v) {
    		foreach ($ary as $v1){
    			if ($v['user_manage_flag'] == 1 || $v['user_manage_flag'] == 3){
    				$p = array();
    				$p['user_id'] = $v['user_id'];
    				$p['table_name'] = 'crud_groups';
    				$p['permission_type'] = $v1;
    				$pDao->save($p);
    			}
    		}
    	}
    }
    

}