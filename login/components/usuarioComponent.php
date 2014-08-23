<?php

class usuarioComponent extends classes\Component\Component{
    private $menu = array(
        'Página Inicial' => array('Página Inicial' => MODULE_DEFAULT),
        'Página do usuário' => array(
            'Página do usuário'     => 'usuario/login',
            'Alterar Email'         => array('Alterar Email'        => 'usuario/login/email'),
            'Trocar Senha'          => array('Trocar Senha'         => 'usuario/login/senha'),
            'Sair'                  => array('Sair'                 => 'usuario/login/logout')
        )
    );
    
    public function menu(){
       $this->LoadModel('usuario/login', 'uobj');
        if(!$this->uobj->IsLoged())return array();
        else return $this->menu; 
    }
}
