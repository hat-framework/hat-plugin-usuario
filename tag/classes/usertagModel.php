<?php 

class usuario_usertagModel extends \classes\Model\Model{
    public  $tabela = "usuario_usertag";
    public  $pkey   = array('cod_usuario', 'cod_tag');
    
    public function addTag($tag, $cod_usuario = ''){
        
        $cookie = "$cod_usuario/$tag";
        if(\classes\Classes\cookie::exists($cookie)){return;}
        
        $tagid = $this->LoadModel('usuario/tag', 'tag')->getTagId($tag);
        if(trim($tagid) === ""){return false;}
        
        if($cod_usuario === ""){
            $cod_usuario = usuario_loginModel::CodUsuario();
            if($cod_usuario == 0){return false;}
        }
        return $this->inserir(array(
            'cod_tag'     => $tagid,
            'cod_usuario' => $cod_usuario,
        ));
    }
    
    public function removeTag($tag, $cod_usuario = ''){
        $tagid = $this->LoadModel('usuario/tag', 'tag')->getTagId($tag);
        if(trim($tagid) === ""){return false;}
        return $this->apagar(array($cod_usuario, $tagid));
    }
    
    public $dados  = array(
        'cod_tag' => array(
	    'name'    => 'Tag',
	    'type'    => 'int',
	    'size'    => '11',
	    'pkey'    => true,
	    'grid'    => true,
	    'display' => true,
            'fkey'    => array(
	        'model'         => 'usuario/tag',
	        'cardinalidade' => '1n',
	        'keys'          => array('cod_tag', 'tag'),
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
            'fkey'    => array(
	        'model'         => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys'          => array('cod_usuario', 'user_name', 'cod_perfil'),
                'onupdate'      => 'CASCADE',
                'ondelete'      => 'CASCADE',
	    ),
        ),
        'dt_tag' => array(
	    'name'     => 'Data da Tag',
	    'type'     => 'timestamp',
	    'notnull'  => true,
	    'display'  => true,
            'default'  => "CURRENT_TIMESTAMP",
            'especial' => 'hide'
        )
    );
}