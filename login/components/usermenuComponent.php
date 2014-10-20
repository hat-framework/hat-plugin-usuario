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
        $msg  = '';
        return array(
             $msg => array(
                 $msg      => 'mensagem/mensagem/index',
                 '__id'    => 'messages',
                 '__icon'  => 'glyphicon glyphicon-envelope icon-envelope'
             ),
             $var => array(
                //'Área Administrativa'    => "admin/",
                '__id'                   => "user",
                '__icon'                 => 'glyphicon glyphicon-user icon-user',
                'Meus dados'             => array(
                    'Meus dados' => 'usuario/login/',
                    '__icon'     => 'fa fa-user'
                ),
                /*'Tutorial'             => array(
                    'Tutorial' => 'usuario/login/tutorial',
                    '__icon'     => ''
                ),*/
                //'tutorial'               => 'usuario/login/tutorial',
                'Configurações'          => array(
                    'Configurações' => 'site/configuracao/index',
                    '__icon'     => 'fa fa-cog'
                ),
                'Central de Aplicativos' => array(
                    'Central de Aplicativos' => 'plugins/plug/index',
                    '__icon'     => 'fa fa-desktop'
                ), 
                'Sair'                   => array(
                    'Sair' => 'usuario/login/logout/',
                    '__icon'     => 'fa fa-sign-out'
                ), 
        ));
    }
    
}