<?php

class conversaoLoginWidget extends \classes\Component\widget{
    protected $modelname  = "usuario/login";
    protected $link       = '';
    protected $order      = "";
    protected $title      = "Eficiência em conversão de cadastros em retorno e assinatura";
    
    public function getItens() {
        $arr['cadastro'] = $this->model->getDailyCadastro();
        $arr['retorno'] = $this->model->getDailyReturn();
        $arr['pago'] = $this->model->getDailyPay();
        foreach ($arr['cadastro'] as $cadastro) {
            $return = 0;
            $pay = 0;
            foreach ($arr['retorno'] as $retorno) {
                if ($cadastro['data'] == $retorno['data'])
                    $return = ($retorno['cod_usuario'] / $cadastro['cod_usuario']) * 100;
            }
            foreach ($arr['pago'] as $pago) {
                if ($pago['data'] == $cadastro['data'])
                    $pay = ($pago['cod_usuario'] / $cadastro['cod_usuario']) * 100;
            }
            $out[] = array('data' => $cadastro['data'], 'cadastro(%)' => '100', 'retorno(%)' => $return, 'pago(%)' => $pay);
        }
        return $out;
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