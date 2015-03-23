<?php 

class tagController extends classes\Controller\CController{
    public $model_name = "usuario/tag";
    
    public function __construct($vars) {
        $this->addToFreeCod(array("importarTags",'taggroup', 'usertag'));
        parent::__construct($vars);
    }
    
    public function taggroup(){
        $this->exec(__FUNCTION__);
    }
    
    public function usertag(){
        $this->exec(__FUNCTION__, true);
    }
    
            private function exec($function, $arr = false){
                $action           = array_shift($this->vars);
                if(trim($action) === ""){$action = 'index';}
                $this->model_name        = "$this->model_name/$function";
                $this->LoadModel($this->model_name, 'model');
                if(!method_exists($this, $action)){throw new \classes\Exceptions\PageNotFoundException();}
                if(in_array($action, array('show','edit','apagar'))){
                    $this->cod = array_shift($this->vars);
                    if($arr === true){
                        $this->cod = array($this->cod);
                        $this->cod[] = array_shift($this->vars);
                    }
                    $this->item              = $this->model->getItem($this->cod);
                    $this->redirect_link     = array('usertag' => "$this->model_name/index");
                    $this->redirect_droplink = "$this->model_name/index";
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