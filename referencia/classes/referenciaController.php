<?php 

class referenciaController extends classes\Controller\TController{
    public $model_name = "usuario/referencia";
    
    public function cadastro(){
        $coduser = usuario_loginModel::CodUsuario();
        $codref  = $this->getVarsParam(0, "Você deve informar o código de referência");
        $this->registerVar('codref', $codref);
        
        if($coduser == 0){return $this->display(LINK ."/cadastro");}
        if($this->model->associate($codref, $coduser)){Redirect("");}
        $this->setVars($this->model->getMessages());
        $this->display("");        
    }
}