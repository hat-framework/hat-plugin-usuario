<?php

class lastActionWidget extends \classes\Component\widget{
    protected $modelname  = "usuario/acesso";
    protected $link       = '';
    protected $order      = "";
    protected $title      = "Acessos na semana por action";
    protected $arr        = array("count(*) as qnt");
    
    
    public function getItens(){
        $result = $this->LoadModel('plugins/plug', 'plugnome')->selecionar(array('plugnome'));
        $in = "group1 IN(";
        $v = '';
        foreach($result as $row){
            $in.= $v."'".$row['plugnome']."'";
            $v = ',';
        }
        $in.= ")";
        $date = date('Y-m-d H:i:s');
        $date = \classes\Classes\timeResource::subDateTime($date, 7);
        $arr = $this->model->getLastActionAccess("$in and data >= '$date' group by group1,group2,group3 ORDER BY count DESC");
        return $arr;
    }
    
    public function listMethod($itens) {
        $this->component->removeListAction('Veja mais');
        parent::listMethod($itens);
    }
}