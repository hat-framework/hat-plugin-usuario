<?php

class errosWidget extends \classes\Component\widget{
    protected $modelname  = "usuario/acesso";
    protected $link       = '';
    protected $qtd        = "15";
    protected $order      = "";
    protected $title      = "Páginas com mais erros";
    protected $codusuario = '';
    
    public function getItens() {
        return $this->model->getChartGroupData($this->groups, $this->qtd, $this->codusuario);
    }
    
    public function listMethod($itens) {
            //print_r($item); echo "<hr/>";
            $title = $this->title;
            $name = GetPlainName($title);
            echo $this->LoadResource('charts', 'ch')
                    ->init('ColumnChart', true)
                    ->transformInChartData($itens, 'action', array(), 'Total', 'action', 'total')
                    ->setDivAttributes("style='height:250px'")
                    ->draw($name, array('title' => $title));
    }
    
    public function setUser($codusuario){
        $this->codusuario = $codusuario;
    }
    
    public function setGroups($codusuario){
        $this->codusuario = $codusuario;
    }
}