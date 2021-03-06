<?php

use classes\Classes\Object;
class subscribeComponent extends classes\Classes\Object{
    
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        $this->LoadResource('html', 'Html');
        $this->Html->LoadCss('tela_login');
        $this->gui = new \classes\Component\GUI();
        $this->class = classes\Classes\Template::getClass('subscribe');
    }
    
    private $referrer = "";
    public function setReferrer($ref){
        $this->referrer = $ref;
        return $this;
    }
    
    public function screen($class = ''){
        if(\usuario_loginModel::CodUsuario() !== 0) {return;}
        if(!defined('USUARIO_CREATE_ACCOUNT') || USUARIO_CREATE_ACCOUNT === false) {return;}
        $cls   = (isset($this->class['subscribeClass']))?$this->class['subscribeClass']:"";
        $title = (isset($this->class['subscribeTitle']))?$this->class['subscribeTitle']:"Cadastre-se Gratuitamente";
        $this->gui->opendiv('tela_cadastro', "$class $cls");
            $this->gui->widgetOpen('', "panel panel-default");
                $this->gui->opendiv('', 'panel-heading');
                    echo "<h3 class='title panel-title'>$title</h3>";
                $this->gui->closediv();

                $this->gui->opendiv('', 'panel-body');
                    $form = $this->getArr();
                    $this->LoadResource("formulario", "form");
                    $this->form->NewForm($form, array(), array(), true, "usuario/login/inserir");
                    $this->facebook();
                $this->gui->closediv();
            $this->gui->widgetClose();
        $this->gui->closediv();
    }
    
    public function getArr(){
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
        if($this->referrer !== ""){
            $dados['referrer'] = array(
                'name'     => 'Referência',
                'especial' => 'hidden',
                'default'  => "$this->referrer",
                'tela'     => true
            );
        }
        
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
        $this->LoadClassFromPlugin('usuario/login/loginFacebook', 'fb');
        if(!$this->fb->enabledApi()){return;}
        echo "<center>ou</center>";
        $txt   = (isset($this->class['fbtext'])) ?$this->class['fbtext'] :'Cadastre-se com Facebook';
        $cls   = (isset($this->class['fbclass']))?$this->class['fbclass']:'btn btn-primary';
        $class = classes\Classes\Template::getClass('facebook');
        echo "<div class='$class'>";
        //echo "<h3>Para Criar uma nova conta <a href='$link2'><b>clique aqui</b></a></h3>";
        echo $this->fb->getFBLink($txt, $cls);
        echo "</div>";
        
    }
}