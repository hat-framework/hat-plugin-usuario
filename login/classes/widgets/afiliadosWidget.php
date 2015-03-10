<?php

use classes\Classes\Object;
class afiliadosWidget extends \classes\Component\widget{
    protected $pgmethod  = "paginate";
    protected $method    = "listFilthers";
    protected $modelname = "usuario/referencia";
    protected $tb        = "";
    protected $arr       = array('user_name', 'cod_referencia', 'COUNT(usuario_referencia.cod_usuario) as total');
    protected $link      = '';
    protected $where     = "1 GROUP BY cod_referencia";
    protected $qtd       = "10";
    protected $order     = "COUNT(usuario_referencia.cod_usuario) DESC";
    protected $title     = "Top Afiliados";
    
    protected function listMethod($itens){
        if(!is_object($this->component)) {return;}
        $listMethod = $this->method;
        $this->component->removeListAction("Veja Mais");
        $this->component->addListAction("Veja Mais", 'dataList');
        $this->component->$listMethod($this->modelname, $itens);
    }
}