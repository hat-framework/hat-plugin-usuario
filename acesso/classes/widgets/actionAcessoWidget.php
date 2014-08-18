<?php

class actionAcessoWidget extends \classes\Component\widget{
    protected $modelname  = "usuario/acesso";
    protected $link       = '';
    protected $order      = "";
    protected $title      = "Quantidade de visualizações por dia";
    
    public function getItens() {
        $result = $this->model->selecionar(array("DATE(data) as data","COUNT(action) AS total"),
            "data !=0 AND cod_usuario !=0 AND cod_perfil !=".Webmaster." GROUP BY DATE(data)",
            "","","data ASC");
        return $result;
        
    }

    public function listMethod($itens) {
        $this->chart("", $itens);
    }
    
    private function chart($title, $itens){
        if(empty($itens)) return;
        $this->gui->openDiv("", "span12");
        $name = GetPlainName($title);
        echo $this->LoadResource('charts', 'ch')
                ->init('AreaChart')
                ->load($itens)
                ->setDivAttributes("style='height:250px'")
                ->draw($name, array('title' => $title));
        
        $this->gui->closeDiv();
    }
    
   
}