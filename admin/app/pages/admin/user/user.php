<?php

require APP . '/objects/admin/user/user/User.php';
require LIBRARY . '/scrud/Scrud.php';
require APP . '/objects/admin/menu/Menu.php';

global $layout;
$layout = 'admin/user/default.php';

$hook = Hook::singleton();

$hook->addFunction('SCRUD_ADD_FORM', 'addPasswordConfirmElement');
$hook->addFunction('SCRUD_EDIT_FORM', 'addPasswordConfirmElement');

$hook->addFunction('SCRUD_BEFORE_VALIDATE', 'passwordConfirmValidate');
$hook->addFunction('SCRUD_VALIDATE', 'comparePassAndConfirmPass');
$hook->addFunction('SCRUD_VALIDATE', 'checkUser');

$hook->addFunction('SCRUD_BEFORE_SAVE', 'encryptPassword');


$_GET['table'] = 'crud_users';
$scrud = new AdminUser($this->da);
$menu = new AdminMenu($this->da);

$this->com->set('main_menu', $menu->show('user'));
$this->com->set('main_content', $scrud->browse());

function addPasswordConfirmElement($element) {
    $tmp = array();
    foreach ($element as $k => $v) {
        if (isset($_REQUEST['key']) && $k == 'crud_users.user_name'){
            $v['element'][1]['readonly'] = "readonly";
        }
        
        $tmp[$k] = $v;
        if ($k == 'crud_users.user_password') {
            $tmp['crud_users.user_password_confirm'] = Array(
                'alias' => 'User confirm password ',
                'element' => Array(
                    0 => 'password',
                    1 => Array(
                        'style' => 'width:210px;'
                    )
                )
            );
        }
    }
    $element = $tmp;

    return $element;
}

function passwordConfirmValidate($validate) {
    if (isset($_GET['xtype']) && $_GET['xtype'] != 'update') {
        $validate['crud_users.user_password_confirm'] = array('rule' => 'notEmpty',
            'message' => 'Please enter the value for User confirm password .');
    }
    return $validate;
}

function comparePassAndConfirmPass($error) {
    if (!empty($_POST['data']['crud_users']['user_password']) &&
            !empty($_POST['data']['crud_users']['user_password_confirm'])) {
        if ($_POST['data']['crud_users']['user_password'] != $_POST['data']['crud_users']['user_password_confirm']) {
            $error['crud_users.user_password'][] = 'User password doesn\'t match User confirm password ';
            $error['crud_users.user_password_confirm'] = array();
        }
    }

    return $error;
}

function encryptPassword($data) {
    $data['crud_users']['user_password'] = sha1($data['crud_users']['user_password']);

    return $data;
}

function checkUser($error) {
    global $da;

    if (empty($_POST['key'])) {
        $userDao = new GenericDao('crud_users', $da);
        $params = array();
        $params['conditions'] = array('user_name = ?', array($_POST['data']['crud_users']['user_name']));
        $rs = $userDao->findFirst($params);

        if (!empty($rs)) {
            $error['crud_users.user_name'][] = 'Someone already has that username. Try another? ';
        }
    }

    return $error;
}