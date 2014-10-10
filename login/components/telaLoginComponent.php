<?php

use classes\Classes\Object;
class telaLoginComponent extends classes\Classes\Object{

    private $dados = array(
        'email_login' => array(
            'name'        => 'Email',
            'notnull'     => 'true',
            'placeholder' => true,
            'especial'    => 'email'
        ),
        'senha_login' => array(
            'name'     => 'Senha',
            'notnull'  => 'true',
            'placeholder' => true,
            'especial' => 'senha',
            'button'   => array(
                'text' => 'Login', 
                'attrs'=>array('class'=>'btn')
             )
        ),
        
    );
    
    public function __construct() {
        $this->LoadResource('html', 'Html');
        $this->gui = new \classes\Component\GUI();
    }

    public function screen($class = 'col-xs-12 col-sm-6 col-md-6 col-lg-4'){
        $this->gui->opendiv('tela_login', $class);
            $this->gui->widgetOpen('', "panel panel-default");
                $this->gui->opendiv('', 'panel-heading');
                    echo "<h3 class='title panel-title'>Já Sou Registrado</h3>";
                $this->gui->closediv();

                $this->gui->opendiv('tela_login', 'panel-body');
                    $this->formLogin();
                    $this->bottom();
                $this->gui->closediv();
            $this->gui->widgetClose();
        $this->gui->closediv();
    }
    
    private function formLogin(){
        
        $class = classes\Classes\Template::getClass('formbutton');
        if($class === ""){$class = 'btn btn-lg'; }
        $this->dados['senha_login']['button']['attrs']['class'] = $class;
        
        $this->LoadResource("formulario", "form");
        $ref = (isset($_GET['refer']))?'&refer='.$_GET['refer']:'';
        $link = URL."?url=usuario/login/$ref";
        $this->form->NewForm($this->dados, $_POST, array(), true, $link);
    }
    
    private function bottom(){
        $link1 = $this->Html->getLink("usuario/login/recuperar");
        $link2 = $this->Html->getLink("usuario/login/inserir");
        if(defined('USUARIO_FB_ACCESS') && USUARIO_FB_ACCESS === true){
            //echo "<h3>Para Criar uma nova conta <a href='$link2'><b>clique aqui</b></a></h3>";
            $this->LoadClassFromPlugin('usuario/login/loginFacebook', 'fb');
            echo $this->fb->getFBLink('', 'btn btn-primary');
        }
        echo "<div class='esqueci_senha'><a href='$link1' class=''>Esqueci minha senha</a></div>";
    }
}