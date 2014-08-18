<?php 

class enderecoController extends \classes\Controller\CController{
    public $model_name = 'usuario/endereco';
    public function __construct($vars) {
        $this->addToFreeCod('getcep');
        parent::__construct($vars);
    }
    
    public function getcep(){
        $cep = isset($this->vars[0])?$this->vars[0]:"";
        if($cep === ""){$this->json_error("O cep deve ser informado na url!");}
        $arr = $this->model->findCep($cep);
        if(empty($arr)){$this->json_error("Nenhum endereço encontrado!");}
        $arr['resultado'] = '1';
        die(json_encode($arr));
    }
    
    private function json_error($msg){
        $vars['erro']      = $msg;
        $vars['status']    = '0';
        $vars['resultado'] = '0';
        die(json_encode($vars));
    }
    
    public function edit($display = true, $link = "") {
        $this->detectRedirect();
        parent::edit($display, $link);
    }
    
    public function formulario($display = true, $link = "") {
        $this->detectRedirect();
        parent::formulario($display, $link);
    }
    
    private function detectRedirect(){
        if(isset($_GET['redirect'])){
            $this->redirect_link  = array(CURRENT_ACTION => $_GET['redirect']);
        }else{$this->prevent_redirect();}
    }
}