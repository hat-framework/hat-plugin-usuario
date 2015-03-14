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
            'description' => "Digite a Tag a ser aplicada",
         ),
    );
    
}