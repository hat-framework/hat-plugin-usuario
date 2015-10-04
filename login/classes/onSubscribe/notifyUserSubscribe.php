<?php

class notifyUserSubscribe extends classes\Classes\Object{
    
    //responsavel pelas mensagens para o usuario
    public function execute($cod_usuario, $array){
        $this->LoadModel('usuario/login/loginDialogs', 'udi');
        $user = $this->LoadModel('usuario/login','uobj')->getItem($cod_usuario);
        $bool = $this->udi->inserir($user);
        $this->setMessages($this->udi->getMessages());
        return $bool;
    }
    
}