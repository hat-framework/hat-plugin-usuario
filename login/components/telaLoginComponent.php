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
        $this->gui   = new \classes\Component\GUI();
        $this->class = classes\Classes\Template::getClass('telalogin');
    }

    public function screen($class = ''){
        $cls   = (isset($this->class['tela_loginClass']))?$this->class['tela_loginClass']:"";
        $title = (isset($this->class['tela_loginTitle']))?$this->class['tela_loginTitle']:"Já Sou Registrado";
        $this->gui->opendiv('tela_login', "$cls $class");
            $this->gui->widgetOpen('', "panel panel-default");
                $this->gui->opendiv('', 'panel-heading');
                    echo "<h3 class='title panel-title'>$title</h3>";
                $this->gui->closediv();

                $this->gui->opendiv('tela_login_container', 'panel-body');
                    $this->formLogin();
                    $this->bottom();
                $this->gui->closediv();
            $this->gui->widgetClose();
        $this->gui->closediv();
    }
    
    public function formLogin(){
        $class = classes\Classes\Template::getClass('formbutton');
        if($class === ""){$class = 'btn btn-lg'; }
        $this->dados['senha_login']['button']['attrs']['class'] = $class;
        
        $this->LoadResource("formulario", "form");
        $ref = (isset($_GET['refer']))?'&refer='.$_GET['refer']:'';
        $link = URL."?url=usuario/login/$ref";
        $this->form->NewForm($this->dados, $_POST, array(), true, $link);
    }
    
    public function bottom(){
        echo "<div id='login_bottom'>";
            $link1 = $this->Html->getLink("usuario/login/recuperar");
            if(defined('USUARIO_FB_ACCESS') && USUARIO_FB_ACCESS === true){
                $fbtext = (isset($this->class['fbtext']))?$this->class['fbtext']:"";
                $clsfb  = (isset($this->class['facebook_login']))?$this->class['facebook_login']:"btn btn-primary";
                $str    = $this->LoadClassFromPlugin('usuario/login/loginFacebook', 'fb')->getFBLink($fbtext, "$clsfb");
                if(trim($str) !== ""){
                    echo "<div class='fb_login'>$str</div>";
                }
            }
            $txt = (isset($this->class['forgetpasswd_txt']))?$this->class['forgetpasswd_txt']:"Esqueci minha senha";
            $cls = (isset($this->class['esqueci_senha']))?$this->class['esqueci_senha']:"";
            echo "<div class='esqueci_senha'><a href='$link1' class='$cls' id='lk_esqueci_senha'>$txt</a></div>";
        echo "</div>";
    }
}