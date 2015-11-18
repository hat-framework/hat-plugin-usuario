<?php

class usuario_gadgetData extends \classes\Model\DataModel{
    
     public $dados  = array(
         'cod' => array(
	    'name'     => 'Cod',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'pkey'    => true,
	    'grid'    => true,
	    'display' => true
        ),
        
         'titulo' => array(
	    'name'     => 'Título',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'notnull' => true,
            'unique'  => array('model' => 'usuario/perfil'),
	    'grid'    => true,
	    'display' => true,
        ),
        'model' => array(
	    'name'     => 'Model',
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
    
}