<?php

class dailyWidget extends \classes\Component\widget{
    protected $modelname  = "usuario/acesso";
    protected $link       = '';
    protected $order      = "";
    protected $title      = "Acessos diários";
    
    public function getItens() {
        $arr['user']   = $this->model->getUserAccess();
        $arr['perfil'] = $this->model->getPerfilAccess();
        return $arr;
    }
    
    public function listMethod($itens) {
        $this->userChart("Acessos por Usuário", $itens['user']);
        $this->perfilChart("Acessos por Perfil", $itens['perfil']);
    }
    
    private function userChart($title, $itens){
        
        if(empty($itens)) return;
        $this->gui->openDiv("", "span6");
        $name = GetPlainName($title);
        echo $this->LoadResource('charts', 'ch')
                ->init('LineChart')
                ->load($itens)
                ->setDivAttributes("style='height:250px'")
                ->draw($name, array('title' => $title));
        
        $this->gui->closeDiv();
    }
    
    private function perfilChart($title, $itens){
        if(empty($itens)) return;
        $this->gui->openDiv("", "span6");
        $out = array();
        $perfis = $this->LoadModel('usuario/perfil', 'perf')->getAllAssoc();
        foreach($itens as $it){
            $perf = (isset($perfis[$it['cod_perfil']]))?$perfis[$it['cod_perfil']]:$it['cod_perfil'];
            if(isset($it['cod_usuario'])) {$out[$it['data']."cod_usuario".$it['cod_usuario']]
                    = array('data' => $it['data'], 'valor' => $it['cod_usuario'], 'tipo' => $perf );}
        }
        $itens = array_values($out);
        $name = GetPlainName($title);
        echo $this->LoadResource('charts', 'ch')
                ->init('LineChart', true)
                ->transformInChartData($itens, 'tipo', array(), 'Date', 'data', 'valor')
                ->setDivAttributes("style='height:250px'")
                ->draw($name, array('title' => $title));
        $this->gui->closeDiv();
    }
}