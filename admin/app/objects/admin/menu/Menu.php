<?php
class AdminMenu{
    
    private $da;
    private $savant;

    public function __construct($da) {
        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }
    
    public function show($type = 'dashboard'){
        $this->savant->type = $type;
        $dao = new Dao($this->da);
        $this->savant->tables = $dao->listTables();
        
        return $this->savant->getOutput('menu.tpl.php');
    }

}