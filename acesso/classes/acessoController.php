<?php 
 use classes\Controller\CController;
class acessoController extends CController{
    public $model_name = "usuario/acesso";
    
    public function __construct($vars) {
        $this->addToFreeCod(array('migrate'));
        parent::__construct($vars);
    }
    
    public function migrate(){
        $this->LoadClassFromPlugin('usuario/acesso/acessoMigrate', 'accm');
        //$this->accm->migrateGroups();
        $this->accm->migrateUtm();
        print_r($this->accm->getMessages());
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
    
    public function globaldrop() {
        $this->model->globaldrop($this->item['action']);
        Redirect('usuario/login/seelog/'.$this->item['cod_usuario']);
    }
    
    public function ladetail(){
        print_in_table($this->model->selecionar(array('action', "COUNT(cod_usuario) as total", "cod_usuario"), "action='{$this->item['action']}'"));
    }
    
    public function hasOwn(){
        if(usuario_loginModel::IsWebmaster()){return true;}
        parent::hasOwn();
    }
    
}