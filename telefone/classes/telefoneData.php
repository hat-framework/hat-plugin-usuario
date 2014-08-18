<?php 
class usuario_telefoneData extends \classes\Model\DataModel{
    public $dados  = array(
         'cod' => array(
	    'name'     => 'Código',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
         'login' => array(
	    'name'     => 'Usuário',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'user_name'),
	    ),
        ),
         'tipo' => array(
	    'name'     => 'Tipo',
	    'type'     => 'enum',
            'default'  => 'celular',
            'options'  => array(
                'casa'     => 'Residencial',
                'trabalho' => 'Trabalho',
                'celular'  => 'Celular',
            ),
        ),
         'operadora' => array(
	    'name'     => 'Operadora',
	    'type'     => 'varchar',
            'size'     => '64',
        ),
         'numero' => array(
	    'name'     => 'Número',
	    'type'     => 'int',
	    'size'     => '13',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
         'padrao' => array(
	    'name'     => 'Padrao',
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
	    'button'     => array('button' => 'Gravar Telefone'),);
}