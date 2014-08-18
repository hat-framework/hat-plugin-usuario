<?php

class usuarioInstall extends classes\Classes\InstallPlugin{
    
    protected $dados = array(
        'pluglabel' => 'Usuários',
        'isdefault' => 'n',
        'system'    => 's',
    );
    
    
    protected $import = array(
        'usuario/userconfig' => array(
            array('cod'=>'1', 'name'=>'pessoal', 'title'=>'Dados Pessoais', 'icon'=>'icon-user', 'view'=>'usuario/login/pessoal')
        )
    );
    public function install(){
        $this->importData();
        return true;
    }
    
    public function unstall(){
        return true;
    }
}