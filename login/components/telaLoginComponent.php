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
            'button'   => 'Login'
        ),
    );
    
    public function __construct() {
        $this->LoadResource('html', 'Html');
        $this->gui = new \classes\Component\GUI();
    }

    public function screen($class = 'span12'){
        $this->gui->widgetOpen('', $class);
            $this->gui->opendiv('tela_login');
                $this->gui->title("Já Sou Registrado");
                $this->formLogin();
                $this->bottom();
            $this->gui->closediv();
        $this->gui->widgetClose();
    }
    
    private function formLogin(){
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