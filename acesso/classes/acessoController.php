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
    
    public function dropitem() {
        $this->model->dropItem($this->item['action']);
        Redirect('usuario/login/seelog/'.$this->item['cod_usuario']);
    }
    
    public function ladetail(){
        print_in_table($this->model->selecionar(array('action', "COUNT(cod_usuario) as total", "cod_usuario"), "action='{$this->item['action']}'"));
    }
    
}