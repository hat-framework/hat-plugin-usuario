<?php

class pagesWidget extends \classes\Component\widget{
    protected $modelname  = "usuario/acesso";
    protected $link       = '';
    protected $qtd        = "10";
    protected $order      = "";
    protected $title      = "Mais acessadas";
    protected $codusuario = '';
    
    public function getItens() {
        $arr  = $this->model->getChartData($this->qtd, $this->codusuario);
        foreach($arr as &$a){
            $a['tipo'] = 'Repetido';
        }
        $this->togrid = $arr;
        usort($this->togrid , function($a, $b){
            return $a['total']<=$b['total'];
        });
        
        $temp = $this->model->getChartDataUnique($this->qtd, $this->codusuario);
        foreach($temp as &$t){
            $t['tipo'] = 'Único';
            $arr[]     = $t;
        }
        return $arr;
    }
    
    public function listMethod($itens) {
            //print_r($item); echo "<hr/>";
        $title = $this->title;
        $name = GetPlainName($title);
        echo $this->LoadResource('charts', 'ch')
                ->init('ColumnChart', true)
                ->transformInChartData($itens, 'tipo', array(), 'Total', 'action', 'total')
                ->setDivAttributes("style='height:250px'")
                ->draw($name, array('title' => $title));
        return parent::listMethod($this->togrid);
    }
    
    public function setUser($codusuario){
        $this->codusuario = $codusuario;
    }
}