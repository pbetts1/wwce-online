<?php

class ChangePassword {

    private $da;
    private $savant;

    public function __construct($da) {
        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }

    private function display() {
        $this->savant->errors = array();
        $this->savant->update_flag = 0;

        return $this->savant->getOutput('password.tpl.php');
    }

    private function update() {
        $errors = array();

        $this->savant->update_flag = 1;

        if ($_POST['current_password'] == '') {
            $errors['current_password'] = 'Please enter your current password.';
        }
        if ($_POST['new_password'] == '') {
            $errors['new_password'] = 'Please enter a new password.';
        }
        if ($_POST['confirm_new_password'] == '') {
            $errors['confirm_new_password'] = 'Please confirm your new password.';
        }

        if (count($errors) <= 0) {
            if ($_POST['new_password'] != $_POST['confirm_new_password']) {
                $errors['confirm_new_password'] = 'New password and New confirm password does not miss match.';
            }
        }

        if (count($errors) <= 0) {
            $userDao = new GenericDao('crud_users', $this->da);
            $params = array();
            $params['conditions'] = array('id = ? and user_password = ?', array($_SESSION['CRUD_AUTH']['id'], sha1($_POST['current_password'])));
            $rs = $userDao->findFirst($params);
            if (empty($rs)) {
                $errors['current_password'] = "Current password you entered was incorrect";
            }
        }

        if (count($errors) <= 0) {
            $data['id'] = $_SESSION['CRUD_AUTH']['id'];
            $data['user_password'] = sha1($_POST['new_password']);
            $userDao->save($data);
        }

        $this->savant->errors = $errors;


        return $this->savant->getOutput('password.tpl.php');
    }

    public function execute() {
        $userMenu = new UserMenu($this->da);
        $this->savant->user_menu = $userMenu->display('password');
        if (count($_POST) > 0) {
            return $this->update();
        } else {
            return $this->display();
        }
    }

}
