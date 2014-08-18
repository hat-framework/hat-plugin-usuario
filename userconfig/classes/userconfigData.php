<?php 

class usuario_userconfigData extends \classes\Model\DataModel{
    
    protected $hasFeatures = true;
    public $dados  = array(
         'cod' => array(
	    'name'     => 'Código',
	    'type'     => 'int',
	    'pkey'    => true,
	    'ai'      => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
        'name' => array(
	    'name'     => 'Name',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'title' => array(
	    'name'     => 'Título',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'icon' => array(
	    'name'     => 'Ícone',
	    'type'     => 'varchar',
	    'size'     => '16',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'view' => array(
	    'name'     => 'View',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
	'button'     => array('button' => 'Gravar Configuração')
    );
}