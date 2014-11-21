<?php

class usuario_loginData extends \classes\Model\DataModel{
    
    protected $hasFeatures = true;
    protected $dados = array(
        'cod_usuario' => array(
            'name'    => "Código do usuário",
            'pkey'    => true,
            'ai'      => true,
            'type'    => 'int',
            'display' => true,
            'size'    => '11',
            'grid'    => true,
            'private' => true,
            'notnull' => true
         ),
        
        'user_name' => array(
            'name'     => 'Nome',
            'type'     => 'varchar',
            'display'  => true,
            'title'    => true,
            'size'     => '64',
            'notnull'  => true,
            'search'   => true,
            'grid'     => true,
            'tela'     => array('subscribe'),
            'description' => "Digite o seu nome completo",
         ),
        
        'user_cargo' => array(
            'name'        => 'Cargo',
            'type'        => 'varchar',
            //'display'     => true,
            'subtitle'    => true,
            'search'      => true,
            'size'        => '64',
            'grid'        => true,
            'description' => "Digite o papel exercido na empresa por você",
         ),
 
        'email' => array(
            'name'     => 'Email',
            'type'     => 'varchar',
            'display'  => true,
            'mobile_hide' => true,
            'unique'   => array('model' => 'usuario/login'),
            'size'     => '64',
            'search'   => true,
            'notnull'  => true,
            'grid'     => true,
            'tela'     => array('subscribe'),
            'especial' => 'email',
            'description' => "O email será utilizado para fazer login e se comunicar com o site.",
         ),
        
        'fixo' => array(
	    'name'     => 'Telefone Fixo',
	    'type'     => 'varchar',
            'feature'  => 'USUARIO_TELEFONE',
	    'size'     => '11',
            'especial' => 'telefone',
	    'grid'    => true,
	    'display' => true,
        ),
        
        'celular' => array(
	    'name'     => 'Celular',
	    'type'     => 'varchar',
            'feature'  => 'USUARIO_TELEFONE',
	    'size'     => '11',
            'especial' => 'telefone',
	    'grid'    => true,
	    'display' => true,
        ),
        
        'senha' => array(
            'name'      => 'Senha',
            'type'      => 'varchar',
            'especial'  => 'senha',
            'size'      => '64',
            'notnull'   => true,
            'confirm'   => true,
            'private'   => true,
            'tela'     => array('subscribe'),
            'description' => "Digite uma senha contendo pelo menos 6 caracteres",
         ),
        
        'permissao' => array(
            'name'     => 'Tipo de Usuário',
            'type'     => 'enum',
            'default'  => 'Visitante',
            'options'  => array(
                'Webmaster' => 'Webmaster',
                'Admin'     => 'Admin',
                'Visitante' => 'Visitante'
            ),
            'permission' => 'usuario_GU',
            //'grid'     => false,
            'notnull'  => true,
            'private' => true,
            'description' => "O tipo de usuário define o acesso do usuário no sistema. <br/>
                <b>Usuários do tipo visitante</b> podem acessar as áreas em que o login é necessário. <br/>
                <b>Usuários do tipo Admin</b> podem modificar dados de configuração do site e podem acessar
                qualquer área e visualizar todos os dados, portanto cuidado ao atribuir alguém como admin",
       	 ),
        
        'cod_perfil' => array(
	    'name'     => 'Perfil de usuário',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
            'search'   => true,
            //'private' => true,
	    'fkey' => array(
	        'model' => 'usuario/perfil',
	        'cardinalidade' => '1n',
	        'keys' => array('usuario_perfil_cod', 'usuario_perfil_nome'),
                'onupdate' => 'CASCADE',
                'ondelete' => 'RESTRICT',
	    ),
        ),
        
        'user_criadoem' => array(
	    'name'     => 'Data de Criação',
	    'type'     => 'timestamp',
	    'notnull' => true,
            'mobile_hide' => true,
            'default' => "CURRENT_TIMESTAMP",
            'especial' => 'hide'
        ),
        
        'user_uacesso' => array(
	    'name'        => 'Último Acesso',
	    'type'        => 'datetime',
            'especial'    => 'hide',
            'display'     => true,
        ),
        'status' => array(
            'name'     => 'Status',
            'type'     => 'enum',
            'especial' => 'hidden',
            'display' => true,
            'label'   => array(
                'exceptions' => array(),
                'drop'       => array()
            ),
            'default'  => 'online',
            'options'  => array(
                'online'    => "Online", 
                'inativo'   => "Inativo", 
                'offline'   => "Offline",
                'bloqueado' => "Bloqueado"
            ),
            'notnull'  => true
       	 ),
        
        'confirmed' => array(
            'name'     => 'Confirmado',
            'type'     => 'enum',
            'especial' => 'hidden',
            //'private'  => true,
            'display'  => true,
            'default'  => 'n',
            'options'  => array(
                'n'    => "Não Confirmado", 
                's'    => "Confimado", 
            ),
            'label'   => array(
                'exceptions' => array(),
            ),
            'notnull'  => true
       	 ),
        
        'usuario_login_tutorial' => array(
	    'name'     => 'Tutorial',
	    'type'     => 'enum',
	    'default' => 'ativo',
            'notnull' => true,
	    'options' => array(
	    	'ativo' => 'ativo',
	    	'inativo' => 'inativo',
	    ),
            'private' => true
        ),
        
/*
        'foto' => array(
            'especial' => 'foto',
            'fkey' => array(
	        'model' => 'galeria/foto',
	        'cardinalidade' => '11',
	        'keys' => array('cod_foto', 'cod_foto'),
                'onupdate' => 'CASCADE',
                'ondelete' => 'RESTRICT',
	    ),
       	 ),*/
        
        'confirmkey' => array(
            'type'     => 'varchar',
            'especial' => 'hide',
            'size'     => '256',
            'private'  => true
       	 ),
        
        'update_permission' => array(
            'name'     => 'Permissão Atualizada',
            'especial' => 'hide',
            'type'     => 'enum',
            'private'  => true,
            'notnull'  => true,
            'default'  => 'n',
            'options'  => array(
                's' => "Sim",
                'n' => "Não"
            )
       	 ),
        
        'tipo_cadastro' => array(
            'name'     => 'Cadastro',
            'especial' => 'hide',
            'type'     => 'enum',
            //'private'  => true,
            'notnull'  => true,
            'display'  => true,
            'default'  => 'site',
            'options'  => array(
                'site'   => "Site",
                'fb'     => "Facebook",
                'google' => "Google",
                'twitter'=> "Twitter",
            )
       	 ),
        
         'codcorretora' => array(
	    'name'     => 'Sua corretora',
	    'type'     => 'int',
	    'size'     => '11',
	    'grid'    => true,
	    'display' => true,
            'feature'  => 'USUARIO_CORRETORA',
	    /*'fkey' => array(
	        'model' => 'carteira/corretora',
	        'cardinalidade' => '1n',
	        'keys' => array('cod', 'dsnomecomercial'),
	    ),*/
        ),
        
        'cpf' => array(
	    'name'     => 'Cpf',
	    'type'     => 'varchar',
            'feature'  => 'USUARIO_CPF',
	    'size'     => '16',
            'especial' => 'cpf',
	    'grid'    => true,
	    'display' => true,
        ),
         'rg' => array(
	    'name'     => 'Rg',
	    'type'     => 'varchar',
	    'size'     => '32',
            'feature'  => 'USUARIO_RG',
	    'grid'    => true,
	    'display' => true,
        ),
         'nascimento' => array(
	    'name'     => 'Nascimento',
	    'type'     => 'date',
            'feature'  => 'USUARIO_NASCIMENTO',
	    'grid'    => true,
	    'display' => true,
        ),
 
        'button' => array(
            'button' => "Salvar Usuário"
         )
    );
    
}