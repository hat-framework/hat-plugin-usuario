<?php

class usuarioInstall extends classes\Classes\InstallPlugin{
    
    protected $dados = array(
        'pluglabel' => 'Usuários',
        'isdefault' => 'n',
        'system'    => 's',
    );
    
    
    protected $import = array(
        'usuario/perfil' => array(
            array('usuario_perfil_cod'=>'3', 'usuario_perfil_nome'=>'Webmaster'                 , 'usuario_perfil_pai'=>'' ,'usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'sistema', 'path'=>'/3'    , 'usuario_perfil_descricao'=>'Perfil destinado aos Webmasters. Eles terão acesso à todos os dados do site'),
            array('usuario_perfil_cod'=>'2', 'usuario_perfil_nome'=>'Administrador'             , 'usuario_perfil_pai'=>'3','usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'sistema', 'path'=>'/3/2'  , 'usuario_perfil_descricao'=>'Usuários com previlégios administrativos, podem alterar configurações do site'),
            array('usuario_perfil_cod'=>'1', 'usuario_perfil_nome'=>'Visitante'                 , 'usuario_perfil_pai'=>'2','usuario_perfil_default'=>'1', 'usuario_perfil_tipo'=>'sistema', 'path'=>'/3/2/1', 'usuario_perfil_descricao'=>'Perfil destinado aos visitantes do site, qualquer usuário que fizer o próprio cadastro automaticamente'),
            array('usuario_perfil_cod'=>'4', 'usuario_perfil_nome'=>'Assinante Temporário'      , 'usuario_perfil_pai'=>'2','usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'usuario', 'path'=>'/3/2/4', 'usuario_perfil_descricao'=>'Durante um período pré determinado este usuário terá acesso as funcionalidades do sistema'),
            array('usuario_perfil_cod'=>'5', 'usuario_perfil_nome'=>'Analista de Informação'    , 'usuario_perfil_pai'=>'2','usuario_perfil_default'=>'0', 'usuario_perfil_tipo'=>'usuario', 'path'=>'/3/2/5', 'usuario_perfil_descricao'=>'Usuário para ter Permissão para ver informações do site e todos os recursos de assinantes'),
        )
    );

    public function install(){
        $this->createRoutine();
        return $this->importData();
    }
    
    public function unstall(){
        return true;
    }
    
    public function createRoutine(){
        $this->LoadResource('files/file', 'fobj');
        $this->LoadResource('database'  , 'db');
        $this->LoadResource('files/dir', 'dobj');
        $files = $this->dobj->getArquivos(dirname(__FILE__).DS."sql");
        
        foreach($files as $file){
            $str = $this->fobj->GetFileContent(dirname(__FILE__).DS."sql".DS."$file");
            if(false === $this->db->executeInsertionQuery($str)){
                $this->appendErrorMessage($this->db->getMessages());
            }
        }
    }
}