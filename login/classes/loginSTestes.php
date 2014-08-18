<?php

use classes\Classes\Object;
class loginSTestes extends classes\Classes\Object{
    
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        $this->db = $this->uobj->db;
        $this->tabela = $this->uobj->getTable();
    }
    
     public function rmtests(){
        if(!$this->db->Delete($this->tabela, "email LIKE 'thom88_%'")){
            $this->setErrorMessage($this->db->getErrorMessage());
            return false;
        }
        $this->setSuccessMessage('Emails de teste removidos com sucesso!');
        return true;
    }
    
    public function addtests(){
        $erro = array();
        $arr['cod_usuario'] = 80;
        $arr['user_cargo']  = "Teste";
        $arr['senha']       = "12tm3flol";
        $arr['cod_perfil']  = "8";
        $arr['status']      = "offline";
        for($i = 9; $i >= 2; $i--){
             $arr['cod_usuario']++;
             $arr['email']       = "thom88_$i@hotmail.com";
             $arr['user_name']   = "Thom 0$i";
             if(!$this->uobj->inserir($arr)){
                 $erro[] = $this->uobj->getErrorMessage();
             }
             $al = $this->uobj->getAlertMessage();
             if($al != "") $erro[] = $al;
        }
        
        for($i = 9; $i > 0; $i--){
             $arr['cod_usuario']++;
             $arr['email']       = "thom88_origin_$i@origin-webmasters.com.br";
             $arr['user_name']   = "Thom Origin 0$i";
             if(!$this->uobj->inserir($arr)){
                 $erro[] = $this->uobj->getErrorMessage();
             }
             $al = $this->uobj->getAlertMessage();
             if($al != "") $erro[] = $al;
        }
        
        if(!empty($erro)){
            $this->setErrorMessage(implode("<br/>", $erro));
            return false;
        }
        
        $this->setSuccessMessage('Emails de teste adicionados com sucesso!');
        return true;
    }
}

?>