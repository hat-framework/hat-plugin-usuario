<?php 

class usuario_dadosData extends \classes\Model\DataModel{
    
    protected $hasFeatures = true;
    public $dados  = array(
        'cod' => array(
	    'name' => 'Usuário',
            'type' => 'int',
            'notnull' => true,
	    'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'user_name'),
	    ),
            'display' => true,
        ),
        'telefone' => array(
	    'name'     => 'Telefone',
	    'type'     => 'text',
            'display' => true,
        ),
        'email' => array(
	    'name'     => 'email',
	    'type'     => 'text',
            'display' => true,
        ),
        'endereco' => array(
	    'name'     => 'endereco',
	    'type'     => 'text',
            'display' => true,
        ),
	'button'     => array('button' => 'Gravar Dados')
    );
}