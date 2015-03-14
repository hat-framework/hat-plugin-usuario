<?php 

class usuario_usertagModel extends \classes\Model\Model{
    public  $tabela = "usuario_usertag";
    public  $pkey   = array('cod_usuario', 'cod_tag');
    
    public function addTag($cod_usuario, $tag){

    }
    
    public $dados  = array(
        'cod_tag' => array(
	    'name'     => 'Código',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'grid'    => true,
	    'display' => true,
            'fkey' => array(
	        'model' => 'usuario/tag',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_tag', 'tag'),
                'onupdate' => 'CASCADE',
                'ondelete' => 'CASCADE',
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
                'ondelete' => 'CASCADE',
	    ),
        ),
        'dt_tag' => array(
	    'name'        => 'Data da Tag',
	    'type'        => 'timestamp',
	    'notnull'     => true,
	    'display'     => true,
            'default'     => "CURRENT_TIMESTAMP",
            'especial'    => 'hide'
        )
    );
}