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
                $url1 = $this->html->getLink("usuario/login/index&refer=$r", true,true);
                $url2 = $this->html->getLink("usuario/login/inserir&refer=$r", true,true);
                return array(
                    "Login" => array(
                        'Login' => $url1,
                        '__id'  => "Minha Conta",
                    ),

                    "Cadastre-se" => array(
                        'Cadastre-se' => $url2,
                        '__id'  => "subscribe",
                    )
                );
            }

            private function LoggedMenu(){
                $temp  = $this->LoadModel('usuario/login', 'uobj')->getUserNick();
                if(strlen($temp) > 10){
                    $e    = explode(" ", $temp);
                    $temp = $e[0];
                }
                return $this->getArray(" Olá, $temp");
            }

                    private function getArray($var){
                        return array(
                            $var => array(
                                '__id'      => "user",
                                '__icon'    => 'glyphicon glyphicon-user icon-user',
                                'Meus dados'=> array(
                                    'Meus dados' => 'config/index/user',
                                    '__icon'     => 'fa fa-user'
                                ),
                                'Configurações'          => array(
                                    'Configurações' => 'site/configuracao/index',
                                    '__icon'        => 'fa fa-cog'
                                ),
                                'Central de Aplicativos' => array(
                                    'Central de Aplicativos' => 'plugins/plug/index',
                                    '__icon'     => 'fa fa-desktop'
                                ), 
                                'Editar de SEO' => array(
                                    'Editar de SEO' => 'plugins/action/find/'.CURRENT_CANONICAL_PAGE,
                                    '__icon'        => 'fa fa-cogs'
                                ),
                                'Sair'                   => array(
                                    'Sair' => 'usuario/login/logout/',
                                    '__icon'     => 'fa fa-sign-out'
                                ), 
                            )
                        );
                    }
    
}