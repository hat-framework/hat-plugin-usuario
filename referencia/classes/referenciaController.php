<?php 

class referenciaController extends classes\Controller\TController{
    public $model_name = "usuario/referencia";
    
    public function cadastro(){
        $coduser = usuario_loginModel::CodUsuario();
        $codref  = $this->getVarsParam(0, "Você deve informar o código de referência");
        $this->model->createCookie($codref);
        $this->registerVar('codref', $codref);
        
        if($coduser == 0){
            $view = (defined('USUARIO_REFERRER_VIEW') && USUARIO_REFERRER_VIEW !== "")?USUARIO_REFERRER_VIEW:LINK."/cadastro";
            return $this->display($view);
        }
        
        if($this->model->associate($codref, $coduser)){Redirect("");}
        $this->setVars($this->model->getMessages());
        $this->display("");        
    }
}