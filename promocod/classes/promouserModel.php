<?php 

class usuario_promouserModel extends \classes\Model\Model{
    public  $tabela = "usuario_promouser";
    public  $pkey   = array('cod_usuario', 'promocod');
    public function attachPromocod($promo_cod, $cod_usuario = ''){
        $promocod = (is_array($promo_cod))?$promo_cod['cod']:$promo_cod;
        if(trim($promocod) == ""){return false;}
        if(false === $this->LoadModel('usuario/promocod', 'promo')->avaiblePromo($promocod)){
            return $this->setErrorMessage($this->promo->getErrorMessage());
        }
        
        $coduser = $this->getCodUsuario($cod_usuario);
        if($coduser == 0){return false;}
        
        $data    = $this->selecionar(array('promocod'), "cod_usuario='$coduser' AND promocod='$promocod'",1);
        if(!empty($data)){return true;}
        
        $bool = $this->updateTag($promocod, $coduser, $data);
        $this->LoadModel('usuario/tag/usertag', 'ut')->addTag("promo_$promo_cod", $coduser);
        return $bool;
        
    }
            private function getCodUsuario($cod_usuario){
                if($cod_usuario !== ""){return $cod_usuario;}
                return usuario_loginModel::CodUsuario();
            }
            
            private function updateTag($promocod, $cod_usuario, $data){
                if(!empty($data)){
                    return $this->editar(array($cod_usuario, $promocod), array(
                        'dt_insc' => 'FUNC_NULL'
                    ));
                }
                return $this->inserir(array(
                    'promocod'    => $promocod,
                    'cod_usuario' => $cod_usuario,
                ));
            }
            
    public function getPromoTotal($cod_promo){
        return $this->getCount("promocod='$cod_promo'");
    }
    
    public $dados  = array(
        'promocod' => array(
	    'name'    => 'Tag',
	    'type'    => 'int',
	    'size'    => '11',
	    'pkey'    => true,
	    'grid'    => true,
	    'display' => true,
            'fkey'    => array(
	        'model'         => 'usuario/promocod',
	        'cardinalidade' => '1n',
	        'keys'          => array('cod', 'cod'),
                'onupdate'      => 'CASCADE',
                'ondelete'      => 'CASCADE',
	    ),
        ),
        'cod_usuario' => array(
	    'name'    => 'Usuário',
	    'type'    => 'int',
	    'size'    => '11',
	    'pkey'    => true,
	    'grid'    => true,
	    'display' => true,
            'select_type'   => 'chosen',
            'fkey'    => array(
	        'model'         => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys'          => array('cod_usuario', 'user_name', 'email'),
                'onupdate'      => 'CASCADE',
                'ondelete'      => 'CASCADE',
	    ),
        ),
        'dt_insc' => array(
	    'name'        => 'Data de aderência',
	    'type'        => 'timestamp',
	    'notnull'     => true,
	    'display'     => true,
            'default'     => "CURRENT_TIMESTAMP",
            'especial'    => 'hide',
            'description' => 'Data de inscrição do usuário no código promocional'
        )
    );
}