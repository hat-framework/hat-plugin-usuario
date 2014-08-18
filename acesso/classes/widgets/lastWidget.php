<?php

class lastWidget extends \classes\Component\widget{
    protected $modelname  = "usuario/acesso";
    protected $link       = '';
    protected $order      = "";
    protected $title      = "Últimos acessos/cadastros na semana";
    protected $arr        = array("count(*) as qnt");
    
    
    public function getItens(){
        $date = date('Y-m-d');
        $date = \classes\Classes\timeResource::subDateTime($date, 7);
        $arr[0] = $this->model->getLastAccess("data >= '$date' group by cod_perfil");
        $arr[1]   = $this->LoadModel('usuario/login','lg')->getLastAccess("data >= $date");
        return $arr;
    }
    
     public function listMethod($itens) {
        $this->component->removeListAction('Veja mais');
        parent::listMethod($itens);
    }
}