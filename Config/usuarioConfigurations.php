<?php
        
class usuarioConfigurations extends \classes\Classes\Options{
          
    protected $menu = array(
        array(
            'menuid' => 'usuario_mensagem',
            'menu'   => 'Minhas Mensagems',
            'url'    => 'usuario/mensagem/index',
            'ordem'  => '9',
        ),
        array(
            'menuid' => 'usuarios',
            'menu'   => 'Usuários',
            'url'    => 'usuario/login/todos',
            'ordem'  => '10',
        ),
        
        array(
            'menuid' => 'novo_usuario',
            'menu'   => 'Criar Usuário',
            'pai'    => 'usuarios',
            'url'    => 'usuario/login/formulario',
        ),
        
        array(
            'menuid' => 'pessoas',
            'menu'   => 'Relatórios',
            'pai'    => 'usuarios',
            'url'    => 'usuario/login/report',
        ),
        
        array(
            'menuid' => 'perfil',
            'menu'   => 'Perfil de Usuário',
            'pai'    => 'usuarios',
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
                
                'USUARIO_MENSAGEM' => array(
                    'name'          => 'USUARIO_MENSAGEM',
                    'label'         => 'Habilitar envio de mensagens para os usuários',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
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
        ),
        'usuario/messages' => array(
            'title'        => 'Opções de envio de mensagens',
            'descricao'    => 'Exibe as opções do plugin de mensagens',
            'visibilidade' => 'admin', //'usuario', 'admin', 'webmaster'
            'grupo'        => 'Plugin de Usuários',
            'path'         => 'usuario/options',
            'updateplugins' => 'true',
            'configs'      => array(
                'USUARIO_MENSAGEM' => array(
                    'name'          => 'USUARIO_MENSAGEM',
                    'label'         => 'Habilitar envio de mensagens para os usuários',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'USUARIO_MENSAGEM_LIMIT_DATA' => array(
                    'name'          => 'USUARIO_MENSAGEM_LIMIT_DATA',
                    'label'         => 'Não permitir que usuários vejam mensagens enviadas para grupos anteriores a data do cadastro?',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'true',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
                'USUARIO_MENSAGEM_EMAIL' => array(
                    'name'          => 'USUARIO_MENSAGEM_EMAIL',
                    'label'         => 'Habilitar notificação por email quando novas mensagens forem enviadas',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'true',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
                
                'USUARIO_MENSAGEM_EMAIL_BODY' => array(
                    'name'          => 'USUARIO_MENSAGEM_EMAIL_BODY',
                    'label'         => 'Permitir que a notificação por email contenha todo o corpo da mensagem? (do contrário haverá apenas um link para o sistema)',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'USUARIO_MENSAGEM_ANY_USER' => array(
                    'name'          => 'USUARIO_MENSAGEM_ANY_USER',
                    'label'         => 'Permitir que qualquer usuário envie mensagens para os administradores',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'USUARIO_MENSAGEM_FULL_CHAT' => array(
                    'name'          => 'USUARIO_MENSAGEM_FULL_CHAT',
                    'label'         => 'Permitir que qualquer usuário envie mensagens para qualquer outro usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'USUARIO_MENSAGEM_GROUP_CHAT' => array(
                    'name'          => 'USUARIO_MENSAGEM_GROUP_CHAT',
                    'label'         => 'Permitir que qualquer usuário envie mensagens para seu grupo de usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'USUARIO_MENSAGEM_ALL_CHAT' => array(
                    'name'          => 'USUARIO_MENSAGEM_ALL_CHAT',
                    'label'         => 'Permitir que qualquer usuário responda as mensagens enviadas para todos usuários (todos usuários visualizarão a resposta)',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
            ),
        ),
    );
    
    public function getMenu(){
        if(false === getBoleanConstant("USUARIO_MENSAGEM")){
            unset($this->menu['0']);
        }
        return $this->menu;
    }
}