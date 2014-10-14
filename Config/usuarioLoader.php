<?php

class usuarioLoader extends classes\Classes\PluginLoader{

    public function setCommonVars(){
        $this->LoadComponent('usuario/login', 'uobj');
        $menu = $this->uobj->menu();
        if(!empty($menu)) $this->setVar("menu", $menu);
        if (isset($_REQUEST['ajax']))return;
        $this->LoadResource('html/html', 'html');
        $this->html->LoadCss('modulos/usuario');
    }
    
    public function setAdminVars(){}
    
}

?>
