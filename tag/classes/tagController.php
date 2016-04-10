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
                    $this->redirect_droplink = "usuario/login/show/{$this->cod[0]}";
                    $this->registerVar('cod' , $this->cod);
                    $this->registerVar('item', $this->item);
                }elseif($action == 'formulario'){
                    $link = "usuario/tag/index";
                    if(isset($_POST['cod_usuario'])){$link = "usuario/login/show/{$_POST['cod_usuario']}";}
                    $this->redirect_link = array('usertag' => $link);
                }
                else{
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
        $error    = false;
        foreach($all as $a){
            if(trim($a['email']) == "" || trim($a['tag']) == ""){continue;}
            if($last != $a['tag']){
                if(false === $this->doSaveTags($logname, $egoi, $lastcod, $last, $emails)){$error = true;}
                $emails  = array();
                $last    = $a['tag'];
                $lastcod = $a['cod_tag'];
            }
            $emails[$a['cod_usuario']] = $a['email'];
        }
        
        if($error == true){
            $url = URL ."index.php?url=site/index/log&folder=/usuario/tag&file=/usuario/tag/export_ALL.html";
            sendMailToUser("Falha ao exportar tags", "Verifique os detalhes do erro <a href='$url'>no log</a>", 1);
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
                    $msg = $egoi->getErrorMessage();
                    classes\Utils\Log::save($logname, "<div class='alert alert-danger'>Erro ao adicionar a tag {$last} <br/>$msg</div>");
                    $this->execArray($logname, $lastcod, $emails, false);
                    return false;
                }
                $this->execArray($logname, $lastcod, $emails, true);
                return true;
            }
            
                    private function execArray($logname, $lastcod, $emails, $dosync){
                        $i = 1;
                        foreach($emails as $cod_usuario => $email){
                            $link = URL."/usuario/login/show/$cod_usuario";
                            $msg  = ($dosync)?
                                    "$i - Adicionando ao email <a href='$link'>$email</a>":
                                    "$i - Não adicionada ao email <a href='$link'>$email</a>";
                            
                            classes\Utils\Log::save($logname, $msg);
                            $i++;
                            if($dosync){$this->utag->setSync($cod_usuario, $lastcod);}
                        }
                    }
}