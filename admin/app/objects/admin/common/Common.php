<?php

class AdminCommon {

    private $da;
    private $savant;

    public function __construct($da) {
        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }

    public function dashboard() {
        global $config_database;
        $this->savant->config_database = $config_database;

        $this->savant->dao = new Dao($this->da);
        $this->savant->tables = $this->savant->dao->listTables();

        return $this->savant->getOutput('dashboard.tpl.php');
    }

    public function login() {
        global $sysUser;
        if (isset($_POST['crudUser']) && isset($_POST['crudUser']['name']) && isset($_POST['crudUser']['password'])) {
            if ($sysUser['enable'] == true) {
                if ($_POST['crudUser']['name'] == $sysUser['name'] &&
                        $_POST['crudUser']['password'] == $sysUser['password']) {
                    $_SESSION['CRUD_AUTH']['user_name'] = $sysUser['name'];
                    $group = array('group_name' => 'SystemAdmin',
                        'group_manage_flag' => 3);
                    $_SESSION['CRUD_AUTH']['group'] = $group;
                    redirect("admin/dashboard");
                    exit;
                }
            }

            $userDao = new GenericDao('crud_users', $this->da);

            $params = array();
            $params['conditions'] = array('user_name = ? and user_password = ?', array($_POST['crudUser']['name'], sha1($_POST['crudUser']['password'])));
            $rs = $userDao->findFirst($params);
            if (!empty($rs)) {
                $groupDao = new GenericDao('crud_groups', $this->da);
                $params = array();
                $params['conditions'] = array('id = ?', array($rs['group_id']));
                $rs1 = $groupDao->findFirst($params);
                if (!empty($rs1)) {
                    $rs['group'] = $rs1;
                } else {
                    $rs['group'] = array('group_name' => 'None',
                        'group_manage_flag' => 0);
                }
                unset($rs['group_id']);
                unset($rs['user_password']);
                unset($rs['user_info']);
                $_SESSION['CRUD_AUTH'] = $rs;
                redirect('admin/dashboard');
            }
        }
        return $this->savant->getOutput('login.tpl.php');
    }

    public function logout() {
        unset($_SESSION['CRUD_AUTH']);
        redirect('admin/login.php');
    }

}