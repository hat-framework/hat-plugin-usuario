<?php
        
class usuarioConfigurations extends \classes\Classes\Options{
          
    protected $menu = array(
        array(
            'menuid' => 'usuarios',
            'menu'   => 'Usuários',
            'url'    => 'usuario/login/todos',
            'icon'   => 'glyphicon glyphicon-user',
            'ordem'  => '10',
        ),
        
        array(
            'menuid' => 'novo_usuario',
            'menu'   => 'Criar Usuário',
            'pai'    => 'usuarios',
            'icon'   => 'glyphicon glyphicon-plus',
            'url'    => 'usuario/login/formulario',
        ),
        
        array(
            'menuid' => 'todos_usuarios',
            'menu'   => 'Listar Usuários',
            'pai'    => 'usuarios',
            'icon'   => 'glyphicon glyphicon-user',
            'url'    => 'usuario/login/todos',
        ),
        
        array(
            'menuid' => 'report',
            'menu'   => 'Relatórios',
            'pai'    => 'usuarios',
            'icon'   => 'glyphicon glyphicon-tasks',
            'url'    => 'usuario/login/report',
        ),
        
        array(
            'menuid' => 'perfil',
            'menu'   => 'Perfil de Usuário',
            'pai'    => 'usuarios',
            'icon'   => '',
            'url'    => 'usuario/perfil/index',
        )
        
    );
    
    protected $files   = array(
        /*'usuario/frases' => array(
            'title'        => 'Mensagens do plugin de Usuários',
            'descricao'    => 'Configurações das mensagens exibidas no plugin de usuários',
            'visibilidade' => 'admin', //'usuario', 'admin', 'webmaster'
            'grupo'        => 'Frases do Sistema',
            'path'         => 'usuario/frases',
            'configs'      => array(
                'AMBIENTE_SEGURO' => array(
                    'name'          => 'AMBIENTE_SEGURO',
                    'label'         => 'Ambiente Seguro',
                    'type'          => 'varchar',//varchar, text, enum
                    'description'   => 'Mensagem exibida na tela de login no momento de digitar o email',
                    'value'         => 'Você está prestes a acessar um ambiente seguro',
                    'value_default' => 'Você está prestes a acessar um ambiente seguro'
                ),
            ),
        ),*/
        
       'usuario/options' => array(
            'title'        => 'Opções de Funcionamento',
            'descricao'    => 'Exibe as opções do plugin de usuários',
            'visibilidade' => 'admin', //'usuario', 'admin', 'webmaster'
            'grupo'        => 'Plugin de Usuários',
            'path'         => 'usuario/options',
            'configs'      => array(
                'USUARIO_CREATE_ACCOUNT' => array(
                    'name'          => 'USUARIO_CREATE_ACCOUNT',
                    'label'         => 'Permitir que Visitantes criem uma conta',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'true',
                    'description'   => 'Se esta opção estiver desmarcada, os usuários do sistema deverão ser incluídos 
                        por quem já estiver participando do sistema.',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
                
                'USUARIO_FB_ACCESS' => array(
                    'name'          => 'USUARIO_FB_ACCESS',
                    'label'         => 'Permitir que Visitantes criem uma conta utilizando o facebook',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'true',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
                
                'USUARIO_LOGIN_AUTOLOGIN_CADASTRO' => array(
                    'name'          => 'USUARIO_LOGIN_AUTOLOGIN_CADASTRO',
                    'label'         => 'Login automático cadastrar no sistema',
                    'type'          => 'enum',//varchar, text, enum
                    'default'       => 'true',
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'description'   => 'Se esta opção estiver marcada, permitirá que o usuário se cadastre no sistema mesmo que 
                                        o usuário não confirme o próprio email',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
                
            ),
        ),
        
        'usuario/facebook' => array(
            'title'        => 'Integração com o facebook',
            'descricao'    => 'Dados da Appkey e appSecret para utilizar o facebook no site',
            'visibilidade' => 'admin', //'usuario', 'admin', 'webmaster'
            'grupo'        => 'Plugin de Usuários',
            'path'         => 'usuario/facebook',
            'configs'      => array(
                'USUARIO_FB_APPID' => array(
                    'name'          => 'USUARIO_FB_APPID',
                    'label'         => 'Id do aplicativo do Facebook',
                    'type'          => 'varchar',//varchar, text, enum
                ),
                
                'USUARIO_FB_APP_SECRET' => array(
                    'name'          => 'USUARIO_FB_APP_SECRET',
                    'label'         => 'Chave secreta do aplicativo do Facebook',
                    'type'          => 'varchar',//varchar, text, enum
                ),
                
            ),
        ),
        
        'usuario/dados' => array(
            'title'        => 'Dados dos usuários',
            'descricao'    => 'Escolha quais dados dos usuários devem ser registrados no sistema',
            'visibilidade' => 'admin', //'usuario', 'admin', 'webmaster'
            'grupo'        => 'Plugin de Usuários',
            'path'         => 'usuario/dados',
            'updateplugins' => 'true',
            'configs'      => array(
                
                'USUARIO_TELEFONE' => array(
                    'name'          => 'USUARIO_TELEFONE',
                    'label'         => 'Registrar telefone fixo e celular dos usuários',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
                'USUARIO_ENDERECO' => array(
                    'name'          => 'USUARIO_ENDERECO',
                    'label'         => 'Registrar endereço do usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
                'USUARIO_MULTI_ADRESS' => array(
                    'name'          => 'USUARIO_MULTI_ADRESS',
                    'label'         => 'Salvar Múltiplos endereços do usuário (Requer a opção <b>Registrar endereço do usuário</b>)',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
                'USUARIO_MULTI_EMAIL' => array(
                    'name'          => 'USUARIO_MULTI_EMAIL',
                    'label'         => 'Salvar Múltiplos emails dos usuários',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
                'USUARIO_CPF' => array(
                    'name'          => 'USUARIO_CPF',
                    'label'         => 'Registrar cpf do usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
                'USUARIO_RG' => array(
                    'name'          => 'USUARIO_RG',
                    'label'         => 'Registrar rg do usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
                'USUARIO_NASCIMENTO' => array(
                    'name'          => 'USUARIO_NASCIMENTO',
                    'label'         => 'Registrar data de nascimento do usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
                'USUARIO_ASSINATURA' => array(
                    'name'          => 'USUARIO_ASSINATURA',
                    'label'         => 'Ativar dados de pagamento para usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
                'USUARIO_CORRETORA' => array(
                    'name'          => 'USUARIO_CORRETORA',
                    'label'         => 'Registrar qual a corretora do usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
            ),
        )
    );
}