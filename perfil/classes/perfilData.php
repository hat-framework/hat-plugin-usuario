<?php

class usuario_perfilData extends \classes\Model\DataModel{
    
    protected $dados  = array(
        
         'usuario_perfil_cod' => array(
	    'name'     => 'Cod',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'ai'      => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
        
         'usuario_perfil_nome' => array(
	    'name'     => 'Nome',
	    'type'     => 'varchar',
	    'size'     => '32',
            'title'    => true,
	    'notnull' => true,
            'unique'  => array('model' => 'usuario/perfil'),
	    'grid'    => true,
	    'display' => true,
        ),
        
        'usuario_perfil_pai' => array(
	    'name' => 'Perfil Superior',
            'type' => 'int',
	    'fkey' => array(
	        'model' => 'usuario/perfil',
	        'cardinalidade' => '1n',
	        'keys' => array('usuario_perfil_cod', 'usuario_perfil_nome'),
	    ),
            'display' => true,
        ),
        
         'usuario_perfil_descricao' => array(
	    'name'     => 'Detalhes do Perfil',
	    'type'     => 'varchar',
            'desc' => true,
	    'size'     => '256',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        'usuario_perfil_default' => array(
	    'name'     => 'Padrão',
	    'type'     => 'bit',
	    'default'  => '0',
	    'notnull' => true,
	    'grid'    => true,
            'especial'=> 'hide',
	    'display' => true,
        ),
        
        'usuario_perfil_tipo' => array(
	    'name'     => 'Tipo de Perfil',
	    'type'     => 'enum',
	    'default'  => 'usuario',
            'label'    => 's',
            'options' => array(
                'sistema' => 'Perfil do Sistema',
                'usuario' => 'Criado por Usuário'
            ),
	    'notnull' => true,
	    'grid'    => true,
            'especial'=> 'hide',
	    'display' => true,
        ),
        
        'display_list' => array(
	    'name'     => 'Exibir Usuários',
	    'type'     => 'enum',
	    'default'  => 's',
            'label'     => 's',
            'options' => array(
                's' => 'Exibir na lista de Usuários',
                'n' => 'Não'
            ),
            'description' => 'Exibe os usuários deste perfil na tela que lista todos os usuários',
	    'notnull' => true,
	    'grid'    => true,
        ),
        
        'usuario_login' => array(
	    'name'     => 'Usuários com este Perfil',
            'especial' => 'hide',
            'type'     => 'int',
            'display_in' => 'table',
	    'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => 'n1',
	        'keys' => array('cod_usuario', 'user_name', 'user_cargo'),
	    ),
        ),
        
        'path' => array(
	    'name'     => 'Path',
	    'type'     => 'varchar',
	    'size'     => '256',
	    'display'  => true,
            'especial' => 'hide',
            'private'  => true,
        ),

        'button' => array(
            'button' => "Salvar Perfil",
        )
    );
    
}

?>