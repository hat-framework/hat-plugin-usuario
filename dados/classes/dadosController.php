<?php 

class dadosController extends classes\Controller\CController{
    
    public function __construct($vars) {
        $this->addToFreeCod("setData");
        parent::__construct($vars);
    }
    
    public function setData(){
        print_rd($_POST);
    }
    
}