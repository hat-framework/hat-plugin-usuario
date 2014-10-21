<?php

use classes\Classes\Object;
class subscribeComponent extends classes\Classes\Object{
    
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        $this->LoadResource('html', 'Html');
        $this->Html->LoadCss('tela_login');
        $this->gui = new \classes\Component\GUI();
    }
    
    public function screen($class = ''){
        if(\usuario_loginModel::CodUsuario() !== 0) {return;}
        if(!defined('USUARIO_CREATE_ACCOUNT') || USUARIO_CREATE_ACCOUNT === false) {return;}
        $data = classes\Classes\Template::getClass('subscribe');
        if(isset($data['subscribeClass']))$class = $data['subscribeClass'];
        $this->gui->opendiv('tela_cadastro', $class);
            $this->gui->widgetOpen('', "panel panel-default");
                $this->gui->opendiv('', 'panel-heading');
                    echo "<h3 class='title panel-title'>Cadastre-se Gratuitamente</h3>";
                $this->gui->closediv();

                $this->gui->opendiv('', 'panel-body pull-left');
                    $form = $this->getArr();
                    $this->LoadResource("formulario", "form");
                    $this->form->NewForm($form, array(), array(), false, "usuario/login/inserir");
                    $this->facebook();
                $this->gui->closediv();
            $this->gui->widgetClose();
        $this->gui->closediv();
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
            'tela'     => true,
        );
        
        $out = array();
        foreach($dados as $name => $arr){
            if(!array_key_exists('tela', $arr)){continue;}
            //$arr['placeholder'] = true;
            $out[$name] = $arr;
        }
        
        $class = classes\Classes\Template::getClass('formbutton');
        if($class === ""){$class = 'btn btn-lg'; }
        $out['button'] = array('button'   => array('text' => 'Criar Conta', 'attrs'=>array('class'=>$class)));
        
        return $out;
    }
    
    private function facebook(){
        if(!defined('USUARIO_FB_ACCESS')      || USUARIO_FB_ACCESS === false) {return;}
        $class = classes\Classes\Template::getClass('facebook');
        echo "<div class='$class'>";
        //echo "<h3>Para Criar uma nova conta <a href='$link2'><b>clique aqui</b></a></h3>";
        $this->LoadClassFromPlugin('usuario/login/loginFacebook', 'fb');
        echo $this->fb->getFBLink('Cadastre-se com Facebook', 'btn btn-primary');
        echo "</div>";
        
    }
}