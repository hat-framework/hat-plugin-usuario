<?php 
class perfilController extends classes\Controller\CController{
    public $model_name = "usuario/perfil";
    
    public function __construct($var) {
        $this->addToFreeCod("tests");
        parent::__construct($var);
    }
    
    public function index($display = true, $link = "") {
        $this->genTags("Perfis do Sistema");
        $this->prevent_redirect();
        parent::index($display, $link);
    }
    
    public function permissoes(){
        $this->genTags("Permissões");
        if($this->model->checkPerfilIsOwnUser($this->cod)){
            $this->registerVar('status', '0');
            $this->registerVar('erro', "Você não tem autorização para alterar suas próprias permissões");
            $this->display('');
            return;
        }
        
        if(!$this->model->checkUserCanAlter($this->cod)){
            $this->registerVar('status', '0');
            $this->registerVar('erro', "Você não tem permissão para alterar este perfil");
            $this->display('');
            return;
        }
        
        $this->LoadClassFromPlugin('usuario/perfil/perfilPermissionsForm', 'ppf');
        if(!empty($_POST)){
            $this->registerVar('status', $this->ppf->savePermissions($this->cod, $_POST)?"1":"0");
            $this->setVars($this->ppf->getMessages());
            $this->LoadModel('plugins/plug','plug')->mountPerfilPermissions();
        }
        $this->registerVar('permissoes', $this->ppf->genarateForm($this->cod));
        $this->registerVar('values', $this->model->getPermissoes($this->cod));
        $this->display(LINK."/permissoes");
    }
    
    public function padrao(){
        $this->model->setDefaultPerfil($this->cod);
        $this->setVars ($this->model->getMessages ());
        $this->index();
    }
}