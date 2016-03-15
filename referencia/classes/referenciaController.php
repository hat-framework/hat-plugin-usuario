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
            $url = $this->getRedirectUrl($codref);
            if($url != ""){Redirect($url);}
            $view = LINK."/cadastro";
            return $this->display($view);
        }
        
        //if($this->model->associate($codref, $coduser)){
            $url = $this->getRedirectUrl($codref,true, false);
            Redirect($url);
        //}
        //$this->setVars($this->model->getMessages());
        //$this->display("");        
    }
    
            protected function getRedirectUrl($codref, $allow_empty = false, $force_empty = false){
                $args   = $this->prepareArgs($codref);
                $this->LoadResource('html', 'html');
                if(!$force_empty){
                    if(defined('USUARIO_REFERRER_VIEW') && USUARIO_REFERRER_VIEW !== "" && USUARIO_REFERRER_VIEW != "usuario/referencia/cadastro"){
                        return $this->html->getLink(USUARIO_REFERRER_VIEW."&$args", true, true);
                    }
                }
                return($allow_empty)?$this->html->getLink("", true, true)."?$args":"";
            }
            
            
                    private function prepareArgs($codref){
                        $get = $_GET;
                        if(isset($get['url'])){unset($get['url']);}
                        if(!isset($get['utm_source'])){
                            $get['utm_source']   = "affiliate";
                            $get['utm_medium']   = "recovery_site";
                            $get['utm_campaign'] = "affiliate_{$codref}";
                        }
                        
                        $args = array();
                        foreach($get as $key => $val){
                            $args[] = "$key=$val";
                        }
                        return implode("&",$args);
                    }
}