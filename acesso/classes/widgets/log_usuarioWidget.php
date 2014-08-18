<?php

class log_usuarioWidget extends \classes\Component\widget{
    protected $modelname = "usuario/acesso";
    protected $link      = '';
    protected $arr       = array('cod', 'logname', 'data', 'action');
    protected $qtd       = "15";
    protected $order     = "data DESC";
    protected $title     = "Log do usuário";
    protected $codusuario = '';
    
    public function mountWhere() {
        $this->where .= "cod_usuario = '$this->codusuario'";
    }
    
    public function setUser($codusuario){
        $this->codusuario = $codusuario;
    }
}