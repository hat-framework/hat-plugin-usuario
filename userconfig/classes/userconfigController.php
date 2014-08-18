<?php 

class userconfigController extends classes\Controller\Controller{
    
    public function __construct($vars) {
        $this->LoadModel('usuario/userconfig', 'uconf');
        parent::__construct($vars);
    }
    
    public function index(){
        $this->display('usuario/userconfig/index');
    }
    
    public function config(){
        $cod = isset($this->vars[0])?$this->vars[0]:"";
        if($cod === ""){Redirect('usuario/login/logado');}
        $this->registerVar('data', $this->uconf->loadConfig($cod));
        $this->display("");
    }
    
    public function getmenu(){
        die(json_encode($this->uconf->getAllUserGroups()));
    }
    
    public function loadConfig(){
        $cod = isset($this->vars[0])?$this->vars[0]:"";
        if($cod === ""){Redirect('usuario/login/logado');}
        die($this->uconf->getConfig($cod));
    }
    
}