<?php

class usuarioLoader extends classes\Classes\PluginLoader{

    public function setCommonVars(){
        $this->LoadComponent('usuario/login', 'uobj');
        $menu = $this->uobj->menu();
        if(!empty($menu)) $this->setVar("menu", $menu);
    }
    
    public function setAdminVars(){}
    
}

?>
