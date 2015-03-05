<?php 

class usuario_referenciaModel extends \classes\Model\Model{
    public  $tabela = "usuario_referencia";
    public  $pkey   = array('cod_referencia','cod_usuario');
    
    public function associate($cod_referencia, $cod_usuario){
        if($cod_referencia == $cod_usuario){
            throw new \classes\Exceptions\InvalidArgumentException("O usuário não pode referenciar a ele mesmo!");
        }
        $item = $this->selecionar(array(), "cod_usuario='$cod_usuario'");
        if(!empty($item)){
            if($item[0]['cod_referencia'] == $cod_referencia){return true;}
            return $this->setErrorMessage("Este usuário já foi convidado no sistema por outro usuário");
        }
        //$date = \classes\Classes\timeResource::getDbDate();
        return $this->inserir(array(
            'cod_referencia' => $cod_referencia,
            'cod_usuario'    => $cod_usuario,
            //'dtindicacao'    => $date
        ));
    }
    
    public function getReferrers ($cod){
        $this->join('usuario/login', 'cod_referencia','cod_usuario', "LEFT");
        return $this->selecionar(
                array('user_name as cod_referencia',"$this->tabela.cod_usuario as __cod_usuario", 'dtindicacao', "cod_referencia as __cod_referencia"), 
                 "$this->tabela.cod_usuario='$cod'"
        );
    }
    
    public function getMyInvitations($cod){
        $this->join('usuario/login', 'cod_usuario','cod_usuario', "LEFT");
        return $this->selecionar(
                array("user_name as cod_usuario","$this->tabela.cod_usuario as __cod_usuario", 'dtindicacao', "cod_referencia as __cod_referencia"),
                "$this->tabela.cod_referencia='$cod'"
        );
    }
}