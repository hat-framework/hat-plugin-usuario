<?php

class usuario_notifyModel extends \classes\Model\Model {

    public $tabela = "usuario_notify";
    public $pkey = 'cod';

    public function insert($codUsuario, $post) {
        foreach ($post as $codtipo => $value) {
            if (is_array($value)) {
                foreach ($value as $val) {
                    $value = $val;
                }
            }
            if (!is_numeric($value))continue;
            if ($value == 1)
                $value = 'n';
            else
                $value = 's';
            $cod = $this->genKey(array($codtipo,$codUsuario),array(0,1));
            $out[] = array('cod' => $cod,'codtipo' => $codtipo, 'codusuario' => $codUsuario, 'permission' => $value);
        }
         if($this->importDataFromArray($out)){
             $this->setSuccessMessage("Notificações salvas com sucesso!"); return true;}
         else { return false;} 
    }
   
}
