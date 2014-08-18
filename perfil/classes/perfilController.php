<?php 
 use classes\Controller\CController;
class perfilController extends CController{
    public $model_name = "usuario/perfil";
    
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
        $this->LoadClassFromPlugin('usuario/perfil/perfilPermissions'    , 'pp');
        if(!empty($_POST)){
            $this->registerVar('status', $this->ppf->savePermissions($this->cod, $_POST)?"1":"0");
            $this->setVars($this->ppf->getMessages());
        }
        $item   = $this->ppf->genarateForm($this->cod);
        $values = $this->pp->getPerfilPermissions($this->cod);
        $this->registerVar('permissoes', $item);
        $this->registerVar('values', $values);
        $this->display(LINK."/permissoes");
    }
    
    public function padrao(){
        $this->model->setDefaultPerfil($this->cod);
        $this->setVars ($this->model->getMessages ());
        $this->index();
    }
}
?>