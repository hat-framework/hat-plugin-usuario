<?php 
 use classes\Controller\CController;
class acessoController extends CController{
    public $model_name = "usuario/acesso";
    
    public function __construct($vars) {
        $this->addToFreeCod('migrate');
        parent::__construct($vars);
    }
    
    public function migrate(){
        $this->model->migrateGroups();
        print_r($this->model->getMessages());
    }
    
    public function index(){
        $this->display(LINK."/report");
    }
    
    public function report(){
        $this->display(LINK."/report");
    }
    
}