<?php 
class usuario_formData extends \classes\Model\DataModel{
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
         'group' => array(
	    'name'     => 'Grupo',
	    'type'     => 'int',
	    'size'     => '11',
	    'grid'    => true,
	    'display' => true,
	    'fkey' => array(
	        'model' => 'usuario/group',
	        'cardinalidade' => '1n',
	        'keys' => array('cod', 'cod'),
	    ),
        ),
         'title' => array(
	    'name'     => 'Title',
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
         'icon' => array(
	    'name'     => 'Icon',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'grid'    => true,
	    'display' => true,
        ),
         'form_data' => array(
	    'name'     => 'Data',
	    'type'     => 'text',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
         'nresponses' => array(
	    'name'     => 'Número de Respostas',
	    'type'     => 'int',
	    'size'     => '2',
            'default'  => '1',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
	    'button'     => array('button' => 'Gravar Form'),);
}