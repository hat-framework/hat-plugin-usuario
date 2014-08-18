<?php

class usuario_notifyData extends \classes\Model\DataModel{
    
    protected $dados  = array(
        
         'cod' => array(
	    'name'     => 'Código',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'pkey'    => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
        
        'codusuario' => array(
	    'name'     => 'Codigo de usuário',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'especial' => 'session',
	    'session'  => 'usuario/login',
	    'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'user_name'),
	    ),
        ),
        'codtipo' => array(
	    'name'     => 'Tipo',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'especial' => 'session',
	    'session'  => 'usuario/notify_tipo',
	    'fkey' => array(
	        'model' => 'usuario/notify_tipo',
	        'cardinalidade' => '1n',
	        'keys' => array('cod', 'name'),
	    ),
        ),
        'permission' => array(
	    'name'     => 'Permissão',
	    'type'     => 'enum',
	    'default' => 's',
	    'options' => array(
	    	's' => 's',
	    	'n' => 'n',
	    ),
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'button' => array(
            'button' => "Salvar Notify",
        )
    );
    
}