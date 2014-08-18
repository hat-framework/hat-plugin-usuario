<?php

class usuario_notify_tipoData extends \classes\Model\DataModel{
    
    protected $dados  = array(
         'cod' => array(
	    'name'     => 'Código',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'ai'      => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
        
       'name' => array(
	    'name'     => 'Tipo',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'grid'    => true,
	    'display' => true,
        ),
        
        'button' => array(
            'button' => "Salvar Notify_Tipo",
        )
    );
    
}