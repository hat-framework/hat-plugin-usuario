<?php

class usuario_tagData extends \classes\Model\DataModel{
    
    public $dados  = array(
        'cod_tag' => array(
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
        
        'tag' => array(
            'name'     => 'Tag',
            'type'     => 'varchar',
            'display'  => true,
            'title'    => true,
            'size'     => '64',
            'notnull'  => true,
            'search'   => true,
            'grid'     => true,
            'unique'   => array('model' => 'usuario/tag'),
            'description' => "Digite a Tag a ser aplicada",
         ),
        
        'tag_expires_time' => array(
	    'name'        => 'Expiração',
	    'type'        => 'int',
            'display'     => true,
            'description' => 'Tempo em dias para remover automaticamente a tag do usuário (útil se você quer saber se o usuário)'
        ),
        
        'users' => array(
	    'name'    => 'Usuários com esta tag',
	    'display' => true,
            'fkey'    => array(
                'refmodel'      => 'usuario/tag',
	        'model'         => 'usuario/tag/usertag',
	        'cardinalidade' => 'n1',
	        'keys'          => array('cod_usuario', 'dt_tag'),
	    ),
        ),
    );
    
}