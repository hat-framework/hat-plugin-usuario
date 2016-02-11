<?php

class usuario_gadgetModel extends \classes\Model\Model {

    public $tabela = "usuario_gadget";
    public $pkey   = 'cod';

     public function getGadgetData($cod_gadget, $cod_usuario){
        if($cod_usuario == "") {modelException(__CLASS__, "O código do usuário não foi informado para carregar as informações deste gadget");}
        $gadget = $this->getItem($cod_gadget);
        if(empty($gadget)) {throw new modelException(__CLASS__, "O gadget que você está tentando acessar não foi encontrado ou não existe");}

        $this->LoadModel($gadget['model'], 'tmp');
        $method = $gadget['model_method'];
        if(!method_exists($this->tmp, $method)){throw new modelException(__CLASS__, "O método $method não existe na classe do modelo");}
        $this->LoadResource('html', 'html');
        $link = $this->html->getLink("usuario/login/gadget/$cod_usuario/$cod_gadget/".GetPlainName($gadget['titulo'])."/");
        
        $limit = 0;
        $page  = 0;
        $var   = $this->tmp->$method($cod_usuario, $link, $limit, $page);
        //print_r($var); echo "<hr/> $method - "; echo $this->db->getSentenca(); die("<br/><br/>".__CLASS__);
        return $var;
    }
    
    public function unstall($module){
        if(false == $this->db->ExecuteInsertionQuery("DELETE FROM $this->tabela WHERE model LIKE '$module/%'")){
            $this->setMessages($this->db->getMessages());
            return false;
        }
        return true;
    }
   
}
