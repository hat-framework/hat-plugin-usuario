<?php

class conversaoAcessoWidget extends \classes\Component\widget{
    protected $modelname  = "usuario/acesso";
    protected $link       = '';
    protected $order      = "";
    protected $title      = "Eficiência em conversão novos visitantes em logs/cadastros";
    
    public function getItens() {
        $arr['acesso'] = $this->model->getUserAccess();
        $arr['login'] = $this->model->getLoginAccess();
        foreach($arr['acesso'] as $acesso){
            $conversao = 0;
            foreach($arr['login'] as $login){
                if($acesso['data'] == $login['data']){
                    $conversao = $login['ip']/$acesso['ip'] * 100;
                }
            }
            $out[] = array('data' => $acesso['data'], 'logou(%)' => $conversao);
        }
        return $out;
    }

    public function listMethod($itens) {
        $this->chart("", $itens);
    }
    
    private function chart($title, $itens){
        if(empty($itens)) return;
        $this->gui->openDiv("", "col-xs-12");
        $name = GetPlainName($title);
        echo $this->LoadResource('charts', 'ch')
                ->init('AreaChart')
                ->load($itens)
                ->setDivAttributes("style='height:250px'")
                ->draw($name, array('title' => $title));
        
        $this->gui->closeDiv();
    }
    
   
}