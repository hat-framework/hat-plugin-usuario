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
                    $cod                     = $this->getCode($arr);
                    $this->item              = $this->model->getItem($this->cod);
                    $this->redirect_link     = array('usertag' => ($cod == "")?"$this->model_name/index":"usuario/tag/show/$cod");
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
            
                    private function getCode($arr){
                        $cod       = "";
                        $this->cod = array_shift($this->vars);
                        if($arr === true){
                            $this->cod = array($this->cod);
                            $cod       = array_shift($this->vars);
                            $this->cod[] = $cod;
                        }
                        return $cod;
                    }
    
    public function importarTags(){
        $this->LoadModel('usuario/tag/usertag', 'utag')->importTagsFromAcesso();
        $this->setVars($this->utag->getMessages());
        $this->display("");
    }
    
    public function exportUserTags(){
        set_time_limit(0);
        
        $logname  = $lastcod = $last = "";
        $interval = $this->getInterval($logname);
        $sync     = $this->getSync();
        $egoi     = $this->LoadResource('api', 'api')->LoadApiClass("emailMarketing/egoiLead");
        $all      = $this->LoadModel('usuario/tag/usertag', 'utag')->getAllTags($sync, $interval);
        $emails   = array();
        
        foreach($all as $a){
            if(trim($a['email']) == "" || trim($a['tag']) == ""){continue;}
            if($last != $a['tag']){
                $this->doSaveTags($logname, $egoi, $lastcod, $last, $emails);
                $emails  = array();
                $last    = $a['tag'];
                $lastcod = $a['cod_tag'];
            }
            $emails[$a['cod_usuario']] = $a['email'];
        }
        $this->display("");
    }
    
            private function getInterval(&$logname){
                $interval = array_shift($this->vars);
                $logname  = "usuario/tag/export_$interval";
                if($interval == ""){
                    $logname  = "usuario/tag/all";
                    classes\Utils\Log::delete($logname);
                }
                return $interval;
            }
            
            private function getSync(){
                $sync = array_shift($this->vars);
                return ($sync == ""|| $sync == 0)?false:true;
            }
    
            private function doSaveTags($logname, $egoi, $lastcod, $last, $emails){
                if($last == ""){return;}
                classes\Utils\Log::save($logname, "<h3>Adicionando a tag {{$last}}</h3>");
                $bool = $egoi->addUserTag($last, $emails);
                if(false === $bool){
                    classes\Utils\Log::save($logname, "Erro ao adicionar a tag {$last} <br/>". $egoi->getErrorMessage());
                    foreach($emails as $cod_usuario => $email){
                        classes\Utils\Log::save($logname, "Não adicionada ao email '{$email}'");
                    }
                    return;
                }
                foreach($emails as $cod_usuario => $email){
                    classes\Utils\Log::save($logname, "Adicionando ao email {$email}");
                    $this->utag->setSync($cod_usuario, $lastcod);
                }
            }
}