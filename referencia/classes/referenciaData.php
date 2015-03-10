<?php

class usuario_referenciaData extends \classes\Model\DataModel{
    
    public $dados  = array(
        
        'cod_referencia' => array(
	    'name'     => 'Referência',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'grid'    => true,
	    'display' => true,
            'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'user_name'),
                'onupdate' => 'CASCADE',
                'ondelete' => 'RESTRICT',
	    ),
        ),
        'cod_usuario' => array(
	    'name'     => 'Usuário',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'grid'    => true,
	    'display' => true,
            'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'user_name', 'cod_perfil'),
                'onupdate' => 'CASCADE',
                'ondelete' => 'RESTRICT',
	    ),
        ),
        'dtindicacao' => array(
	    'name'        => 'Data de Indicação',
	    'type'        => 'timestamp',
	    'notnull'     => true,
	    'display'     => true,
            'default'     => "CURRENT_TIMESTAMP",
            'especial'    => 'hide'
        )
    );
    
}