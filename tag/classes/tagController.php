<?php 

class tagController extends classes\Controller\CController{
    public $model_name = "usuario/tag";
    
    public function __construct($vars) {
        $this->addToFreeCod(array("importarTags",'taggroup'));
        parent::__construct($vars);
    }
    
    public function taggroup(){
        $action           = array_shift($this->vars);
        if(trim($action) === ""){$action = 'index';}
        $this->model_name = "$this->model_name/".__FUNCTION__;
        $this->LoadModel($this->model_name, 'model');
        if(!method_exists($this, $action)){throw new \classes\Exceptions\PageNotFoundException();}
        if(in_array($action, array('show','edit','apagar'))){
            $this->cod = array_shift($this->vars);
            $this->item = $this->model->getItem($this->cod);
            $this->registerVar('cod' , $this->cod);
            $this->registerVar('item', $this->item);
        }else{
            $page       = array_shift($this->vars);
            $this->item = $this->model->paginate($page);
        }
        $this->registerVar('model_name', $this->model_name);
        $this->registerVar('component' , $this->model_name);
        $this->$action();
    }
    
    public function importarTags(){
        $this->LoadModel('usuario/tag/usertag', 'utag')->importTagsFromAcesso();
        $this->setVars($this->utag->getMessages());
        $this->display("");
    }
}