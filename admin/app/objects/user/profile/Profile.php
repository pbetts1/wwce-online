<?php

class Profile {

    private $da;
    private $savant;

    public function __construct($da) {
        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }

    public function display() {
        global $config_database;
        
        $userMenu = new UserMenu($this->da);
        $hook = Hook::singleton();

        $hook->addFunction('SCRUD_EDIT_FORM', 'removeElement');
        $hook->addFunction('SCRUD_EDIT_CONFIRM', 'removeElement');
        $hook->addFunction('SCRUD_BEFORE_VALIDATE', 'removeValidate');
        $hook->addFunction('SCRUD_COMPLETE_UPDATE', 'completeUpdate');
        $hook->addFunction('SCRUD_BEFORE_SAVE', 'removeElementData');
        
        
        if (!isset($_GET['xtype'])){
            $_GET['xtype'] = 'form';
        }
        $_GET['table'] = 'crud_users';
        $_GET['key']['crud_users.id'] = $_SESSION['CRUD_AUTH']['id'];
        
        $_SERVER['QUERY_STRING'] = $_SERVER['QUERY_STRING'].'&key[crud_users.id]='.$_SESSION['CRUD_AUTH']['id'];

        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php')) {
            exit;
        }

        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php'));
        $conf = unserialize($content);
        $conf['theme_path'] = dirname(__FILE__).'/crud';
        $scurd = new Scurd('crud_users', $conf, $this->da);
        
        $this->savant->user_menu = $userMenu->display('profile');
        
        $this->savant->content = $scurd->process();

        return $this->savant->getOutput('profile.tpl.php');
    }

}

function removeElement($element){
    unset($element['crud_users.user_name']);
    unset($element['crud_users.user_password']);
    unset($element['crud_users.group_id']);
    
    return $element;
}

function removeElementData($data){
    
    if (isset($data['crud_users']['user_name'])){
        unset($data['crud_users']['user_name']);
    }
    if (isset($data['crud_users']['user_password'])){
        unset($data['crud_users']['user_password']);
    }
    if (isset($data['crud_users']['group_id'])){
        unset($data['crud_users']['group_id']);
    }
    
    return $data;
}

function removeValidate($validate){
    unset($validate['crud_users.user_name']);
    unset($validate['crud_users.user_password']);
    unset($validate['crud_users.group_id']);
    
    return $validate;
}

function completeUpdate($data){
    redirect('user/editProfile.php');
}