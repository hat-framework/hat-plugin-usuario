<?php

class usuario_acessoData extends \classes\Model\DataModel{
    
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
        
        'logname' => array(
	    'name'     => 'Nome',
	    'type'     => 'varchar',
            'title'    => true,
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group0' => array(
	    'name'     => 'Grupo',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group1' => array(
	    'name'     => 'Grupo 1',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group2' => array(
	    'name'     => 'Grupo 2',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group3' => array(
	    'name'     => 'Grupo 3',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group4' => array(
	    'name'     => 'Grupo 4',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group5' => array(
	    'name'     => 'Grupo 5',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group6' => array(
	    'name'     => 'Grupo 6',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group7' => array(
	    'name'     => 'Grupo 7',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group8' => array(
	    'name'     => 'Grupo 8',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group9' => array(
	    'name'     => 'Grupo 9',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group10' => array(
	    'name'     => 'Grupo 10',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group11' => array(
	    'name'     => 'Grupo 5',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group12' => array(
	    'name'     => 'Grupo 6',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group13' => array(
	    'name'     => 'Grupo 7',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group14' => array(
	    'name'     => 'Grupo 8',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'group15' => array(
	    'name'     => 'Grupo 9',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'key' => array(
	    'name'     => 'Track Key',
	    'type'     => 'varchar',
	    'size'     => '24',
        ),
        
        'data' => array(
	    'name'     => 'Data e hora',
	    'type'     => 'timestamp',
	    'notnull'  => true,
            'default'  => "CURRENT_TIMESTAMP",
            'especial' => 'hide',
	    'display'  => true,
        ),
        
        'cod_usuario' => array(
	    'name'     => 'Usuario',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        'cod_perfil' => array(
	    'name'     => 'Perfil de usuário',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
            'search'   => true,
        ),
        
        'action' => array(
	    'name'     => 'Action',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'refer' => array(
	    'name'     => 'Veio de',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'display'  => true,
        ),
        
        'ip' => array(
	    'name'     => 'IP',
	    'type'     => 'varchar',
	    'size'     => '24',
	    'display'  => true,
        ),
        
        'msg' => array(
	    'name'     => 'Mensagem',
	    'type'     => 'text',
        ),

        'button' => array(
            'button' => "Salvar Perfil",
        )
    );
    
}