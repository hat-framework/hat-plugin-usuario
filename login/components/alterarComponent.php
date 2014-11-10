<?php

class alterarComponent extends \classes\Classes\Object{
    
    private $dados = array();
    private $class = "col-xs-4";
    private $id    = "alterar_widget";
    public function __construct() {
        $this->gui   = new \classes\Component\GUI();
        $this->dados = $this->LoadModel('usuario/login', 'uobj')->getDados();
        $this->reset();
    }
    
    public function senha($item){
        $dados['senha_nova'] = array(
            'name'     => 'Senha Nova',
            'especial' => 'senha',
            'notnull'  => true
        );

        $dados['confirmar_senha'] = array(
            'name' => 'Confirmar senha nova',
            'especial' => 'equalto',
            'equalto'  => 'senha_nova',
            'notnull'  => true,
        );
        
        $this->makeWidget("Alterar Senha", $dados, $item, 'usuario/login/alterar/senha', "Alterar Senha");
    }
    
    public function telefone($item){
        if(false === getBoleanConstant('USUARIO_TELEFONE')) {return;}
        $out   = array();
        if(isset($this->dados['fixo'])) {$out['fixo'] = $this->dados['fixo'];}
        if(isset($this->dados['celular'])) {$out['celular'] = $this->dados['celular'];}
        $this->id = 'alterar_telefone';
        $this->makeWidget("Telefone", $out, $item, 'usuario/login/alterar/telefone');
    }
    
    public function email($item){
        $out   = array();
        if(isset($this->dados['user_name']))  {$out['user_name']  = $this->dados['user_name'];}
        if(isset($this->dados['email']))      {$out['email'] = $this->dados['email'];}
        if($this->uobj->UserIsAdmin() && isset($this->dados['user_cargo'])){
            $out['user_cargo'] = $this->dados['user_cargo'];
        }
        if($this->uobj->getCodUsuario() !== $item['cod_usuario'] && isset($this->dados['cod_perfil'])){
            $out['cod_perfil']= $this->dados['cod_perfil'];
        }        
        $this->id = 'alterar_email';
        $this->makeWidget("Dados da conta", $out, $item, 'usuario/login/alterar/email', "Alterar dados da conta");
    }
    
    public function pessoal($item){
        $out = $this->assocDados(array('nascimento', 'cpf', 'rg'));
        if(empty($out)){return;}
        $this->id = 'alterar_pessoal';
        $this->makeWidget("Dados pessoais", $out, $item, 'usuario/login/editar');
    }
    
    public function corretora($item){
        if(false === getBoleanConstant('USUARIO_CORRETORA')) {return;}
        $out   = array();
        if(isset($this->dados['fixo'])) {$out['fixo'] = $this->dados['fixo'];}
        if(isset($this->dados['celular'])) {$out['celular'] = $this->dados['celular'];}
        if(isset($this->dados['codcorretora'])) {$out['codcorretora'] = $this->dados['codcorretora'];}
        $this->id = 'alterar_corretora';
        $this->makeWidget("Telefone & Corretora", $out, $item, 'usuario/login/alterar/telefone');
    }
    
    public function endereco($item){
        if(false === getBoleanConstant('USUARIO_ENDERECO')) {return;}
        $cod_usuario = usuario_loginModel::CodUsuario();
        $dados = $this->LoadModel('usuario/endereco', 'end')->getDados();
        $item  = $this->end->getUserAddress($cod_usuario); 
        $view  = (empty($item)?"usuario/endereco/formulario":"usuario/endereco/edit/$cod_usuario");
        if(isset($dados['button'])){unset($dados['button']);}
        $this->id = 'alterar_endereco';
        $this->makeWidget("Meu endereço", $dados, $item, $view, "Alterar Endereço");
    }
    
    private function makeWidget($title, $dados, $item, $action, $button_name = "Alterar Dados"){
        $dados['senha_confirmacao'] = array(
            'name'     => '<hr/>Senha de Confirmação',
            'especial' => 'senha',
            'senha'    => array('autocomplete' => false),
            'notnull'  => true
        );
        $dados['button'] = array('button' => $button_name);
        
        $link = (isset($_GET['redirect']))?$this->LoadResource('html', 'html')->getLink("$action&redirect=".$_GET['redirect'], true,true):$action;
        $this->gui->opendiv($this->id, $this->class);
            $this->gui->opendiv('change_widget', "panel panel-default");
                $this->gui->opendiv('', 'panel-heading');
                    echo "<h3 class='title panel-title'>$title</h3>";
                $this->gui->closediv();

                $this->gui->opendiv('', 'panel-body');
                    $this->LoadResource("formulario", "form")->NewForm($dados, $item, array(), true, $link);
                $this->gui->closediv();
            $this->gui->widgetClose();
        $this->gui->closediv();
        $this->reset();
    }
    
    public function setClass($class){
        $this->class = $class;
    }
    
    private function reset(){
        $this->class = 'col-xs-12 col-sm-6 col-md-4 col-lg-3';
        $this->id    = 'alterar_widget';
    }
       
    private function assocDados($arr){
        $out = array();
        foreach($arr as $name){
            if(isset($this->dados[$name])){
                $out[$name] = $this->dados[$name];
            }
        }
        return $out;
    }
}
