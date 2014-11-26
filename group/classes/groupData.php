<?php 
class usuario_groupData extends \classes\Model\DataModel{
    public $dados  = array(
         'cod' => array(
	    'name'     => 'Código',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'ai'      => true,
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
         'title' => array(
	    'name'     => 'Title',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
         'icon' => array(
	    'name'     => 'Icon',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
         'description' => array(
	    'name'     => 'Description',
	    'type'     => 'varchar',
	    'size'     => '200',
	    'grid'    => true,
	    'display' => true,
        ),
	    'button'     => array('button' => 'Gravar Group'),);
}