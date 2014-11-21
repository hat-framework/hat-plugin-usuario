<?php

class lastWidget extends \classes\Component\widget{
    protected $modelname  = "usuario/acesso";
    protected $link       = '';
    protected $order      = "";
    protected $title      = "Últimos acessos/cadastros na semana";
    protected $description  = 'Número de usuários que acessaram e cadastraram na última semana';
    
    
    public function getItens(){
        $date = date('Y-m-d H:i:s');
        $date = \classes\Classes\timeResource::subDateTime($date, 7);
        $arr[0] = $this->LoadModel('usuario/login','lg')->getLastAccess("user_criadoem >= $date");
        $arr[1] = $this->model->getLastAccess
                ("data >= '$date' AND cod_perfil !='". Webmaster ."' AND cod_perfil !='". Admin ."'"
                . "group by cod_usuario");
        return $arr;
    }
    
     public function listMethod($itens) {
        $this->component->removeListAction('Veja mais');
        parent::listMethod($itens);
    }
}