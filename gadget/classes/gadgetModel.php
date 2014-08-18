<?php 

class usuario_gadgetModel extends \classes\Model\Model{
    public  $tabela = "usuario_gadget";
    public  $pkey   = 'cod';
    public $dados  = array(
         'cod' => array(
	    'name'     => 'Cod',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'ai'      => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
        
         'titulo' => array(
	    'name'     => 'Título',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'model' => array(
	    'name'     => 'Model',
	    'type'     => 'varchar',
	    'size'     => '64',
	    'display'  => true,
        ),
        
        'model_method' => array(
	    'name'     => 'Método do Modelo',
	    'type'     => 'varchar',
	    'size'     => '64',
	    'display'  => true,
        ),
        
        'comp_method' => array(
	    'name'     => 'Método do Componente',
	    'type'     => 'varchar',
	    'size'     => '64',
	    'display'  => true,
        ),

        'button' => array(
            'button' => "Salvar Gadget",
        )
    );
    
    public function getGadgetData($cod_gadget, $cod_usuario, $page = 1, $limit = 3){
        if($cod_usuario == "") modelException(__CLASS__, "O código do usuário não foi informado para carregar as informações deste gadget");
        $gadget = $this->getItem($cod_gadget);
        if(empty($gadget)) throw new classes\Exceptions\modelException(__CLASS__, "O gadget que você está tentando acessar não foi encontrado ou não existe");

        $this->LoadModel($gadget['model'], 'tmp');
        $method = $gadget['model_method'];
        if(!method_exists($this->tmp, $method))throw new classes\Exceptions\modelException(__CLASS__, "O método $method não existe na classe do modelo");
        $this->LoadResource('html', 'html');
        $link = $this->html->getLink("usuario/login/gadget/$cod_usuario/$cod_gadget/".GetPlainName($gadget['titulo'])."/");
        
        $var = $this->tmp->$method($cod_usuario, $link, $limit, $page);
        //print_r($var); echo "<hr/> $method - "; echo $this->db->getSentenca(); die("<br/><br/>".__CLASS__);
        return $var;
    }
    
    public function unstall($module){
        return $this->db->ExecuteQuery("DELETE FROM $this->tabela WHERE model LIKE '$module/%'");
    }
}