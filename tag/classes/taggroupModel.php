<?php 

class usuario_taggroupModel extends \classes\Model\Model{
    public  $tabela = "usuario_taggroup";
    public  $pkey   = 'cod_taggroup';
        
    public function getGroupId($group){
        $name = $this->antinjection($group);
        if(trim($name) === ""){$name = "Sem Grupo";}
        $value = $this->getField($name, 'cod_taggroup', 'name');
        if(is_numeric($value)){return $value;}
        return (false === $this->inserir(array('name' => $name)))?"":$this->getLastId();
    }
    
    public $dados  = array(
        'cod_taggroup' => array(
            'name'    => "Código",
            'pkey'    => true,
            'ai'      => true,
            'type'    => 'int',
            'display' => true,
            'size'    => '11',
            'grid'    => true,
            'private' => true,
            'notnull' => true
         ),
        'name' => array(
            'name'     => 'Grupo',
            'type'     => 'varchar',
            'display'  => true,
            'title'    => true,
            'size'     => '64',
            'notnull'  => true,
            'unique'   => array('model' => 'usuario/tag/taggroup'),
            'description' => "Grupo da Tag",
        ),
        'grouptags' => array(
	    'name'    => 'Tags do grupo',
	    'display' => true,
            'fkey'    => array(
                'refmodel'      => 'usuario/tag/taggroup',
	        'model'         => 'usuario/tag',
	        'cardinalidade' => 'n1',
	        'keys'          => array('cod_tag', 'tag'),
	    ),
        ),
    );
}