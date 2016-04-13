<?php

use classes\Classes\Object;
class recuperarComponent extends classes\Classes\Object{
    
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        $this->LoadResource('html', 'Html');
        $this->Html->LoadCss('tela_login');
        $this->gui = new \classes\Component\GUI();
    }
    
    public function screen($class = 'col-xs-12 col-sm-6 col-md-6 col-lg-8'){
        $data = classes\Classes\Template::getClass('recuperar');
        if(isset($data['recuperarClass'])){$class = $data['recuperarClass'];}
        $this->gui->opendiv('recuperar', $class);
            $form = $this->getArr();
            $this->LoadResource("formulario", "form")->NewForm($form, $_POST, array(), false);
        $this->gui->closediv();
    }
    
    private function getArr(){
        return array(
            'email'  => array(
                'name'     => 'Email',
                'especial' => 'email',
                'description' => 'Digite o email cadastrado no sistema para que a nova senha seja enviada para este mesmo email',
                'button'   => 'Recuperar'
            )
        );
    }
}