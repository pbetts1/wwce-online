<?php

class UserMenu {

    private $da;
    private $savant;

    public function __construct($da) {
        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }
    
    public function display($type='profile'){
        $this->savant->type = $type;
        
        return $this->savant->getOutput('menu.tpl.php');
    }
}
