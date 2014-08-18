<?php

use classes\Classes\Object;
class usermenuComponent extends classes\Classes\Object{
       
    public function __construct() {
        $this->gui = new \classes\Component\GUI();
        $this->LoadModel('usuario/login', 'lobj');
        $this->LoadResource('html', 'html')->LoadCss("profile");
    }
    
    public function getLoggedMenu(){
        return($this->lobj->IsLoged())?$this->LoggedMenu():$this->getUnloggedMenu();
    }
    
    private function getUnloggedMenu(){
        $r   = base64_encode(URL.CURRENT_URL);
        $url = $this->html->getLink("usuario/login/index&refer=$r", true,true);
        return array(
            "Login" => array(
                'Login' => $url,
                '__id'  => "Minha Conta",
            )
        );
    }
    
    private function LoggedMenu(){
        //$tutorial = ($this->lobj->IsEnabledTutorial())? "Desativar Tutorial":"Ativar Tutorial";
        $nick = MOBILE?"": $this->lobj->getUserNick();
        if($nick == "") $nick = "Conta";
        return array(
             $nick => array(
                'Área Administrativa' => "admin/",
                '__id'             => "Minha Conta",
                'Meus dados'       => 'usuario/login/',
                //$tutorial          => 'usuario/login/tutorial',
                'Configurações'         => 'site/configuracao/index',
                'Central de Aplicativos' => 'plugins/plug/index', 
                'Sair'             => 'usuario/login/logout/',
        ));
    }
    
}