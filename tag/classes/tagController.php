<?php 

class tagController extends classes\Controller\CController{
    public $model_name = "usuario/tag";
    
    public function __construct($vars) {
        $this->addToFreeCod(array("importarTags",'taggroup', 'usertag','exportUserTags'));
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
    
    public function exportUserTags(){
        set_time_limit(0);
        $interval = array_shift($this->vars);
        $egoi     = $this->LoadResource('api', 'api')->LoadApiClass("emailMarketing/egoiLead");
        $all      = $this->LoadModel('usuario/tag/usertag', 'utag')->getAllTags(false, $interval);
        $logname  = "usuario/tag";
        $last     = "";
        $lastcod  = "";
        $emails   = array();
        classes\Utils\Log::delete($logname);
        foreach($all as $a){
            if(trim($a['email']) == "" || trim($a['tag']) == ""){continue;}
            if($last != $a['tag']){
                $this->doSaveTags($logname, $egoi, $a, $lastcod, $last, $emails);
                $emails  = array();
                $last    = $a['tag'];
                $lastcod = $a['cod_tag'];
            }
            $emails[$a['cod_usuario']] = $a['email'];
        }
        $this->display("");
    }
    
            private function doSaveTags($logname, $egoi, $a, $lastcod, $last, $emails){
                if($last == ""){return;}
                classes\Utils\Log::save($logname, "<h3>Adicionando a tag {{$last}}</h3>");
                $bool = $egoi->addUserTag($a['tag'], $emails);
                if(false === $bool){
                    classes\Utils\Log::save($logname, "Erro ao adicionar a tag {$a['tag']}");
                }
                foreach($emails as $cod_usuario => $email){
                    classes\Utils\Log::save($logname, "Adicionando ao email {$email}");
                    $this->utag->setSync($cod_usuario, $lastcod);
                }
            }
}