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
        $var  = $this->LoadModel('usuario/login', 'uobj')->getUserNick();
        $nick = "<i class='glyphicon glyphicon-user icon-user'></i>$var<span class='caret'></span>";
        $msg  = '<i class="glyphicon glyphicon-envelope icon-envelope"></i>';

        return array(
             $msg => array(
                 $msg => 'mensagem/mensagem/index',
                 '__id' => 'messages'
             ),
             $nick => array(
                //'Área Administrativa'    => "admin/",
                '__id'                   => "user",
                'Meus dados'             => 'usuario/login/',
                //'tutorial'               => 'usuario/login/tutorial',
                'Configurações'          => 'site/configuracao/index',
                'Central de Aplicativos' => 'plugins/plug/index', 
                'Sair'                   => 'usuario/login/logout/',
        ));
    }
    
}