<?php

class searchUserWidget extends \classes\Component\widget{   
    protected $title = "Pesquisar Usuários";
    public function widget() {
        //print_r($_GET);
        $this->openPanel();
            $this->LoadResource('formulario', 'form')
                    ->setMethod('get')
                    ->newForm($this->dados, $_GET, array(),false, 'usuario/login/todos');
        $this->closeWidget();
    }
    
    protected $dados = array(
        
        'user_string' => array(
            'fieldset'    => "Pesquisa Direta",
            'name'        => "Pesquisar Dados de texto",
            'description' => 'Pesquisar por nome, email, código, cargo',
            'type'        => 'varchar',
            'size'        => '64',
         ),
        
        'cod_perfil' => array(
            'fieldset' => "Agrupamentos de usuário",
	    'name'     => 'Perfil de usuário',
	    'type'     => 'int',
	    'size'     => '11',
	    'fkey' => array(
	        'model' => 'usuario/perfil',
	        'cardinalidade' => '1n',
	        'keys' => array('usuario_perfil_cod', 'usuario_perfil_nome'),
	    ),
        ),
        
        'tags' => array(
	    'name'        => 'Tags de usuário',
	    'type'        => 'int',
	    'size'        => '11',
            'select_type' => 'multiple',
	    'fkey'        => array(
	        'model'         => 'usuario/tag',
	        'cardinalidade' => '1n',
	        'keys'          => array('cod_tag', 'tag'),
	    ),
        ),
        
        'status' => array(
            'name'     => 'Status',
            'type'     => 'enum',
            'options'  => array(
                'online'    => "Online", 
                'inativo'   => "Inativo", 
                'offline'   => "Offline",
                'bloqueado' => "Bloqueado"
            ),
            'especial' => 'multi_enum',
       	 ),
        
        'tipo_cadastro' => array(
            'name'     => 'Cadastro',
            'type'     => 'enum',
            'especial' => 'multi_enum',
            'options'  => array(
                'site'   => "Site",
                'fb'     => "Facebook",
                'google' => "Google",
                'twitter'=> "Twitter",
            )
       	 ),
        
        'confirmed' => array(
            'name'     => 'Confirmado',
            'type'     => 'enum',
            'multi'    => true,
            'options'  => array(
                'n'    => "Não Confirmado", 
                's'    => "Confimado", 
            )
       	 ),
        
        'indicado' => array(
            'name'        => 'Usuário Indicado',
            'description' => 'Busca usuários indicados por outros usuários',
            'type'        => 'enum',
            'multi'       => true,
            'options'     => array(
                's'    => "Indicado"
            )
       	 ),
        
        'user_criadoem' => array(
            'fieldset'    => "Datas",
	    'name'        => 'Criado a partir',
            'description' => 'Pesquisará a partir da data de criação setada',
	    'type'        => 'date',
        ),
        
        'user_criadoem_ate' => array(
	    'name'        => 'Criado até',
            'description' => 'Pesquisará até a data de criação setada',
	    'type'        => 'date',
        ),
        
        'user_uacesso' => array(
	    'name'        => 'Último Acesso a partir',
	    'type'        => 'date',
            'description' => 'Pesquisará a partir da data do último acesso',
            'display'     => true,
        ),
        
        'user_uacesso_ate' => array(
	    'name'        => 'Último Acesso até',
	    'type'        => 'date',
            'description' => 'Pesquisará até a data do último acesso',
            'display'     => true,
        ),
        
        'button' => array(
            'button' => "Pesquisar Usuário"
         ),
        
        'widget' => array(
            'type'        => 'varchar',
            'size'        => '64',
            'default'     => 'listUserWidget',
            'especial'    => 'hidden'
        ),
    );
}