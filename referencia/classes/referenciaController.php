<?php 

class referenciaController extends classes\Controller\TController{
    public $model_name = "usuario/referencia";
    
    public function __construct($vars) {
        parent::__construct($vars);
        $this->addToFreeCod(array('cadastro'));
    }
    
    public function cadastro(){
        $coduser = usuario_loginModel::CodUsuario();
        $codref  = $this->getVarsParam(0, "Você deve informar o código de referência");
        $this->model->createCookie($codref);
        $this->registerVar('codref', $codref);
        if($coduser == 0){
            $url = $this->getRedirectUrl();
            if($url != ""){Redirect($url);}
            $view = LINK."/cadastro";
            return $this->display($view);
        }
        
        //if($this->model->associate($codref, $coduser)){
            $url = $this->getRedirectUrl(true, true);
            Redirect($url);
        //}
        //$this->setVars($this->model->getMessages());
        //$this->display("");        
    }
    
    private function getRedirectUrl($allow_empty = false, $force_empty = false){
        $get = $_GET;
        if(isset($get['url'])){unset($get['url']);}
        $argss = array();
        foreach($get as $key => $val){
            $argss[] = "$key=$val";
        }
        $args = implode("&",$argss);
        $this->LoadResource('html', 'html');
        if(!$force_empty){
            if(defined('USUARIO_REFERRER_VIEW') && USUARIO_REFERRER_VIEW !== ""){
                return $this->html->getLink(USUARIO_REFERRER_VIEW."&$args", true, true);
            }
        }
        
        return($allow_empty)?$this->html->getLink("", true, true)."?$args":"";
    }
}