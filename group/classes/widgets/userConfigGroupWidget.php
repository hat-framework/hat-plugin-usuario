<?php 
class userConfigGroupWidget extends \classes\Component\widget{
    
    protected $pgmethod  = "paginate";
    //protected $method    = "listFilthers";
    protected $modelname = "usuario/group";
    //protected $arr       = array('cod_usuario', 'user_name', 'email', 'user_uacesso', 'status');
    protected $link      = '';
    protected $where     = "";
    protected $qtd       = "0";
    //protected $order     = "user_uacesso DESC";
    //protected $title     = "Últimos acessos";
    
    public function getItens() {
        $temp = array(
            array('cod'=>'pessoal','nome'=>'Dados Pessoais','icon'=>'fa fa-user', 'forms' => array(
                array('cod'=>'email'  ,'nome'=>'Email'    ,'icon'=>'fa fa-envelope'),
                array('cod'=>'senha'  ,'nome'=>'Senha'    ,'icon'=>'fa fa-lock'),
                array('cod'=>'phone'  ,'nome'=>'Telefone' ,'icon'=>'fa fa-phone'),
                array('cod'=>'address','nome'=>'Endereço' ,'icon'=>'fa fa-map-marker'),
            )),
            array('cod'=>'payment','nome'=>'Pagamento' ,'icon'=>'fa fa-money','forms' => array(
                array('cod'=>'paypal'    ,'nome'=>'Paypal'            ,'icon'=>'fa fa-paypal'),
                array('cod'=>'cartao'    ,'nome'=>'Cartão de Crédito' ,'icon'=>'fa fa-credit-card'),
                array('cod'=>'pagseguro' ,'nome'=>'Pagseguro'         ,'icon'=>'fa fa-money'),
            )),
            array('cod'=>'notify' ,'nome'=>'Notificações' ,'icon'=>'fa fa-globe','forms' => array(
                array('cod'=>'conta'    ,'nome'=>'Notificações da Conta'  ,'icon'=>'fa fa-user'),
                array('cod'=>'mercado'  ,'nome'=>'Atualizações do mercado','icon'=>'fa fa-line-chart')
            )),
        );
        $var = parent::getItens();
        return array_merge($temp, $var);
    }
    
    public function draw($itens) {
        $e = explode("/",CURRENT_URL);
        $this->current_group = isset($e[3])?$e[3]:"";
        $this->current_form  = isset($e[4])?$e[4]:"";
        echo '<div id="accordion" class="panel-group">';
            foreach($itens as $item){
                $this->drawItem($item);
            }
        echo '</div>';
    }
    
    private function drawItem($item){
        static $i = 0;
        extract($item);
        if(!isset($forms) || empty($forms)){return;}
        $i++;
        $class    = "collapse$i";
        $collapse = ($this->current_group === $cod)?"collapsed":"collapse";
        echo '<div class="panel panel-default">';
            echo '<div class="panel-heading">';
                echo "<h4 class='panel-title' style='cursor: pointer' data-toggle='collapse' data-parent='#accordion' href='#$class'>";
                    echo "<a><i class='$icon'></i> $nome</a>";
                echo '</h4>';
            echo '</div>';
            echo "<div id='$class' class='panel-collapse $collapse'><div class='panel-body'>";
                
            foreach($forms as $form){
                $this->drawSubItem($cod, $form);
            }
                
            echo '</div></div>';
        echo '</div>';
    }
    
    private function drawSubItem($cod_group, $subitem){
        extract($subitem);
        $active   = ($this->current_form === $cod)?"active":"";
        $url = $this->Html->getLink("usuario/group/form/$cod_group/$cod");
        echo "<a href='$url' class='col-xs-12 btn $active' style=''><h5><i class='$icon'></i> $nome</h5></a>";
    }
    
}
