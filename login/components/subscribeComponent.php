<?php

use classes\Classes\Object;
class subscribeComponent extends classes\Classes\Object{
    
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        $this->LoadResource('html', 'Html');
        $this->Html->LoadCss('tela_login');
        $this->gui = new \classes\Component\GUI();
    }
    
    public function screen($class = 'span12'){
        if(\usuario_loginModel::CodUsuario() !== 0) {return;}
        if(!defined('USUARIO_CREATE_ACCOUNT') || USUARIO_CREATE_ACCOUNT === false) {return;}
        $this->gui->widgetOpen('', $class);
            $this->gui->title('Cadastre-se Gratuitamente');
            $form = $this->getArr();
            $this->LoadResource("formulario", "form");
            $this->form->NewForm($form, array(), array(), false, "usuario/login/inserir");
            $this->facebook();
        $this->gui->widgetClose();
    }
    
    private function getArr(){
        $dados = $this->uobj->getDados();
        unset($dados['permissao']);
        unset($dados['senha']['private']);
        $dados['confirmar_senha'] = array(
            'name' => 'Confirmar senha',
            'especial' => 'equalto',
            'equalto'  => 'senha',
            'notnull'  => true,
        );
        
        $out = array();
        foreach($dados as $name => $arr){
            if(!array_key_exists('tela', $arr)){continue;}
            $arr['placeholder'] = true;
            $out[$name] = $arr;
        }
        $out['button'] = array('button'   => 'Criar Conta');
        
        $arr = array();
        foreach($arr as $name){
            if(!isset($dados[$name])){continue;}
        }
        return $out;
    }
    
    private function facebook(){
        if(!defined('USUARIO_FB_ACCESS')      || USUARIO_FB_ACCESS === false) {return;}
        //echo "<h3>Para Criar uma nova conta <a href='$link2'><b>clique aqui</b></a></h3>";
        $this->LoadClassFromPlugin('usuario/login/loginFacebook', 'fb');
        echo $this->fb->getFBLink('Cadastre-se com Facebook', 'btn btn-primary');
        
    }
}