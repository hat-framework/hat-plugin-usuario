<?php

use classes\Classes\Object;
class superiorLoginComponent extends classes\Classes\Object{

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

    public function screen($class = 'pull-right col-md-3'){
        if(usuario_loginModel::isLogged()){return;}
        $this->data = classes\Classes\Template::getClass('superior_login');
        if(isset($this->data['superior_loginClass']))$class = $this->data['superior_loginClass'];
        $this->gui->opendiv('superior_login', $class);
                $this->gui->opendiv('superior_login', 'panel-body');
                    $this->formLogin();
                    $this->bottom();
            $this->gui->widgetClose();
        $this->gui->closediv();
    }
    
    private function formLogin(){
        if(isset($this->data['s_formbutton']))$class = $this->data['s_formbutton'];
        if($class === ""){$class = 'btn btn-xs'; }
        $this->dados['senha_login']['button']['attrs']['class'] = $class;
        
        $this->LoadResource("formulario", "form");
        $ref = (isset($_GET['refer']))?'&refer='.$_GET['refer']:'';
        $link = URL."?url=usuario/login/$ref";
        $this->form->NewForm($this->dados, $_POST, array(), true, $link);
    }
    
    private function bottom(){
        $link1 = $this->Html->getLink("usuario/login/recuperar");
        $link2 = $this->Html->getLink("usuario/login/inserir");
        echo "<div class='col-xs-12'>";
        if(defined('USUARIO_FB_ACCESS') && USUARIO_FB_ACCESS === true){
            if(isset($this->data['s_facebook']))$class = $this->data['s_facebook'];
            $text = (isset($this->data['text_facebook']))?$this->data['text_facebook']:'';
            //echo "<h3>Para Criar uma nova conta <a href='$link2'><b>clique aqui</b></a></h3>";
            $this->LoadClassFromPlugin('usuario/login/loginFacebook', 'fb');
            echo $this->fb->getFBLink($text, "$class btn btn-primary btn-xs");
        }
        if(isset($this->data['s_esqueci_senha']))$classEsqueci = $this->data['s_esqueci_senha'];
        echo "<div class='$classEsqueci esqueci_senha'><a href='$link1' class=''>Esqueci senha</a></div>";
        echo "</div>";
    }
}