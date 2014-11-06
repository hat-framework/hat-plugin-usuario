<?php

class pluginsReportWidget extends plugins\site\Config\reportWidget{    
     protected $modelname  = "usuario/acesso";
     protected $typegrafic = "LineChart";
     protected $title      = "Estatística de plugins";
     protected $description = "Total de clicks nos plugins:";
     protected $date       = "data"; //coluna que possui as datas do gráfico (geralmente Timestamp)
     protected $titlegrafic   = "Plugins por data (Exceção do Plugin Empresa)";
     
     public function widget(){
        $this->LoadModel($this->modelname, 'model');
        $id = ($this->id == "")?"widget_".  str_replace("/", "_", $this->modelname):$this->id;
        $this->gui->opendiv($id, $this->class);
            $this->gui->setDescription($this->description);
            $this->gui->panelSubtitle($this->title);
            $this->gui->opendiv('', 'panel-body');
            $this->total();
            $this->graf();
            $this->grafSubEmpresa();
            $this->gui->closediv();
        $this->gui->closediv();
    }
     
     
     public function graf(){
        $array = array();
        $array = $this->getItemGrafic();
        if($array == array())return;
        echo $this->LoadResource('charts', 'ch')
                    ->init($this->typegrafic,true)
                    ->transformInChartData($array,'group1',array(),'date','date','count')
                    ->setDivAttributes("style='height:500px'")
                    ->draw('',  array('title' => $this->titlegrafic));
    }
    
     public function getItemGrafic(){
        $where = "group1 IN(SELECT plugnome from plugin_plug) AND group1 != 'empresa' AND cod_perfil !="
                .Webmaster." AND cod_perfil !=".Admin;
        $arr = array(
            "DATE(data) as date", 
            "group1",
            "count(group1) as count"
        );
        $result = $this->model->selecionar($arr,"$where GROUP BY date,group1", "", "","date DESC");
        //echo $this->model->db->getSentenca();
        return $result;
    }
    
    
    public function grafSubEmpresa(){
        $array = array();
        $array = $this->getItemGraficSubEmpresa();
        if($array == array())return;
        echo $this->LoadResource('charts', 'ch')
                    ->init($this->typegrafic,true)
                    ->transformInChartData($array,'group3',array(),'date','date','count')
                    ->setDivAttributes("style='height:500px'")
                    ->draw('',  array('title' => "Sub-Plugins do plugin Empresa por data"));
    }
    
     public function getItemGraficSubEmpresa(){
        $where = "group1 = 'empresa' AND group2 = 'empresa' AND cod_perfil !="
                .Webmaster." AND cod_perfil !=".Admin;
        $arr = array(
            "DATE(data) as date", 
            "group3",
            "count(group3) as count"
        );
        $result = $this->model->selecionar($arr,"$where GROUP BY date,group3", "", "","date DESC");
        //echo $this->model->db->getSentenca();
        return $result;
    }
}