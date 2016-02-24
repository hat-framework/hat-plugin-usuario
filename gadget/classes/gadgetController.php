<?php 

use classes\Controller\CController;
class gadgetController extends CController{
    public $model_name = "usuario/gadget";
    
    public function exec(){
        $cod_usuario = usuario_loginModel::CodUsuario();
        if($this->LoadModel('usuario/perfil', 'perf')->hasPermissionByName('usuario_analisar')){
            $cod_usuario = isset($this->vars[1])?$this->vars[1]:$cod_usuario;
        }
        $this->registerVar('gadget', $this->item);
        $this->registerVar('cod_usuario', $cod_usuario);
        $this->display(LINK.'/exec');
    }
}