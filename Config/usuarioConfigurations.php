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
                'menu'   => 'Novo Usuário',
                'pai'    => 'usuarios',
                'icon'   => 'fa fa-user-plus',
                'url'    => 'usuario/login/formulario',
            ),

            array(
                'menuid' => 'todos_usuarios',
                'menu'   => 'Listar Usuários',
                'pai'    => 'usuarios',
                'icon'   => 'fa fa-user',
                'url'    => 'usuario/login/todos',
            ),

            array(
                'menuid' => 'perfil',
                'menu'   => 'Perfils de Usuário',
                'pai'    => 'usuarios',
                'icon'   => 'fa fa-user',
                'url'    => 'usuario/perfil/index',
            ),

            array(
                'menuid' => 'tags',
                'menu'   => 'Todas as Tags',
                'pai'    => 'usuarios',
                'icon'   => 'fa fa-tags',
                'url'    => 'usuario/tag/index',
            ),
        
            array(
                'menuid' => 'promo',
                'menu'   => 'Todas as Promoções',
                'pai'    => 'usuarios',
                'icon'   => 'fa fa-bookmark',
                'url'    => 'usuario/promocod/index',
            ),
        
            array(
                'menuid' => 'report',
                'menu'   => 'Relatórios',
                'pai'    => 'usuarios',
                'icon'   => 'glyphicon glyphicon-tasks',
                'url'    => 'usuario/login/report',
            ),
        
    );
    
    protected $files   = array(

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
                
                'USUARIO_ENABLE_MSG' => array(
                    'name'          => 'USUARIO_ENABLE_MSG',
                    'label'         => 'Ícone de mensagem',
                    'type'          => 'enum',//varchar, text, enum
                    'default'       => 'false',
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'description'   => 'Se esta opção estiver marcada, mostrará um ícone de mensagens no menu superior',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                
                'USUARIO_REFERRER_VIEW' => array(
                    'name'          => 'USUARIO_REFERRER_VIEW',
                    'label'         => 'View padrão para o cadastro via referrer',
                    'type'          => 'varchar',//varchar, text, enum
                    'default'       => 'usuario/referencia/cadastro',
                    'description'   => 'mude esta opção se você criou uma página de captura para o seu site',
                    'value'         => 'usuario/referencia/cadastro',
                    'value_default' => 'usuario/referencia/cadastro'
                ),
                
                'USUARIO_FIRST_LOGIN_VIEW' => array(
                    'name'          => 'USUARIO_FIRST_LOGIN_VIEW',
                    'label'         => 'Primeira página após login',
                    'type'          => 'varchar',//varchar, text, enum
                    'default'       => '',
                    'description'   => 'Mude esta opção se após o cadastro você deseja redirecionar o usuário para uma página customizada',
                    'value'         => '',
                    'value_default' => ''
                ),
                
                'USUARIO_FRIENDLY_DATE' => array(
                    'name'          => 'USUARIO_FRIENDLY_DATE',
                    'label'         => 'Exibir datas amigáveis',
                    'type'          => 'enum',//varchar, text, enum
                    'default'       => 'false',
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'description'   => 'Se esta opção estiver marcada, as datas listadas aparecerão no formato de redes sociáis: há tantos minutos,por exemplo em vez de aparecer a data.',
                    'value'         => 'false',
                    'value_default' => 'false'
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
                
            ),
        )
    );
}