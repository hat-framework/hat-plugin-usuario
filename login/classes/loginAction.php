<?php

class usuario_loginAction{
    
    private $actions = array(
        'inserir'    => array('padrao'=>'s', 'acesso' => 'publico'  , 'menu' => 'Nova conta'         , 'label' => 'Registrar-se no site'),
        'index'      => array('padrao'=>'s', 'acesso' => 'publico'  , 'menu' => 'Login'              , 'label' => 'Fazer login no sistema'),
        'logout'     => array('padrao'=>'s', 'acesso' => 'publico'  , 'menu' => 'Logout'             , 'label' => 'Fazer logout no sistema'),
        'recuperar'  => array('padrao'=>'s', 'acesso' => 'publico'  , 'menu' => 'Esqueci minha senha', 'label' => 'Recuperar Senha'),
        
        'senha'      => array('padrao'=>'s', 'acesso' => 'protegido', 'menu' => 'Alterar Senha'      , 'label' => 'Alterar a própria Senha'),
        'email'      => array('padrao'=>'s', 'acesso' => 'protegido', 'menu' => 'Alterar Email'      , 'label' => 'Alterar o próprio Email'),
        'telefones'  => array('padrao'=>'s', 'acesso' => 'protegido', 'menu' => 'Meus Telefones'     , 'label' => 'Gerenciar telefones'),
        'enderecos'  => array('padrao'=>'s', 'acesso' => 'protegido', 'menu' => 'Meus Endereços'     , 'label' => 'Gerenciar endereços'),
        'social'     => array('padrao'=>'s', 'acesso' => 'protegido', 'menu' => 'Redes sociais'      , 'label' => 'Gerenciar Redes sociais'),
        'emails'     => array('padrao'=>'s', 'acesso' => 'protegido', 'menu' => 'Emails alternativos', 'label' => 'Gerenciar Emails alternativos'),
        
        'apagar'     => array('padrao'=>'n', 'acesso' => 'privado'  , 'menu' => 'Excluir'            , 'label' => 'Excluir usuário'),
        'formulario' => array('padrao'=>'n', 'acesso' => 'privado'  , 'menu' => 'Novo Usuário'       , 'label' => 'Registrar Usuários'),
        'grid'       => array('padrao'=>'n', 'acesso' => 'privado'  , 'menu' => 'Editar'             , 'label' => 'Alterar os dados de outros usuários'),
    );
    
    public function getActions(){
        return $this->actions;
    }
}

?>