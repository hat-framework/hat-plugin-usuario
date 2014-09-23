<?php

class usuarioInstall extends classes\Classes\InstallPlugin{
    
    protected $dados = array(
        'pluglabel' => 'Usuários',
        'isdefault' => 'n',
        'system'    => 's',
    );
    
    
    protected $import = array(
        /*'usuario/userconfig' => array(
            array('cod'=>'1', 'name'=>'pessoal', 'title'=>'Dados Pessoais', 'icon'=>'icon-user', 'view'=>'usuario/login/pessoal')
        ),*/
        'usuario/notify_tipo' => array(
            array('cod'=>'1', 'name'=>'pessoal'),
            array('cod'=>'2', 'name'=>'pessoal'),
            array('cod'=>'3', 'name'=>'pessoal'),
            array('cod'=>'4', 'name'=>'pessoal'),
        ),
        'usuario/perfil' => array(
            array('usuario_perfil_cod'=>'3', 'usuario_perfil_nome'=>'Webmaster'                 , 'usuario_perfil_pai'=>'' ,'usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'sistema', 'path'=>'/3'    , 'usuario_perfil_descricao'=>'Perfil destinado aos Webmasters. Eles terão acesso à todos os dados do site'),
            array('usuario_perfil_cod'=>'2', 'usuario_perfil_nome'=>'Administrador'             , 'usuario_perfil_pai'=>'3','usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'sistema', 'path'=>'/3/2'  , 'usuario_perfil_descricao'=>'Usuários com previlégios administrativos, podem alterar configurações do site'),
            array('usuario_perfil_cod'=>'1', 'usuario_perfil_nome'=>'Visitante'                 , 'usuario_perfil_pai'=>'2','usuario_perfil_default'=>'1', 'usuario_perfil_tipo'=>'sistema', 'path'=>'/3/2/1', 'usuario_perfil_descricao'=>'Perfil destinado aos visitantes do site, qualquer usuário que fizer o próprio cadastro automaticamente'),
            
            array('usuario_perfil_cod'=>'4', 'usuario_perfil_nome'=>'Assinante Temporário'      , 'usuario_perfil_pai'=>'2','usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'usuario', 'path'=>'/3/2/4', 'usuario_perfil_descricao'=>'Durante um período pré determinado este usuário terá acesso ao sistema de análise e gestão'),
            array('usuario_perfil_cod'=>'5', 'usuario_perfil_nome'=>'Analista de Informação'    , 'usuario_perfil_pai'=>'2','usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'usuario', 'path'=>'/3/2/5', 'usuario_perfil_descricao'=>'Usuário para ter Permissão para ver informações do site e todos os recursos de assinantes'),
            array('usuario_perfil_cod'=>'6', 'usuario_perfil_nome'=>'Assinante Analise'         , 'usuario_perfil_pai'=>'2','usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'usuario', 'path'=>'/3/2/6', 'usuario_perfil_descricao'=>'Usuário com acesso exclusivo para assinantes de analise'),
            array('usuario_perfil_cod'=>'7', 'usuario_perfil_nome'=>'Assinante Gestão'          , 'usuario_perfil_pai'=>'2','usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'usuario', 'path'=>'/3/2/7', 'usuario_perfil_descricao'=>'Usuário com acesso exclusivo para assinantes de gestão'),
            array('usuario_perfil_cod'=>'8', 'usuario_perfil_nome'=>'Assinante Analise e Gestão', 'usuario_perfil_pai'=>'2','usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'usuario', 'path'=>'/3/2/8', 'usuario_perfil_descricao'=>'Usuário com acesso exclusivo para assinantes de analise e gestão')
        )
    );

    public function install(){
        $this->createRoutine();
        return $this->importData();
    }
    
    public function unstall(){
        return true;
    }
    
    private function createRoutine(){
        $this->LoadResource("database", 'db')->executeInsertionQuery("
            delimiter |
                CREATE EVENT IF NOT EXISTS usuario_login_upstatus
                ON SCHEDULE EVERY 15 MINUTE
                COMMENT 'Atualiza o status dos usuarios do sistema a cada X minutos'
                DO
                   BEGIN
                        update usuario set status = 'offline' WHERE status != 'online' AND (NOW() - user_uacesso) > 3600 OR isnull(user_uacesso) ;
                        update usuario set status = 'inativo' WHERE status = 'online'  AND (NOW() - user_uacesso) > 900 AND (NOW() - user_uacesso) <= 3600;
                   END |
            delimiter ;
        ");
    }
}