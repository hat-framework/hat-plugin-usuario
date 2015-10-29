<?php 

class usuario_usertagModel extends \classes\Model\Model{
    public  $tabela = "usuario_usertag";
    public  $pkey   = array('cod_usuario', 'cod_tag');
    
    public function addTag($tag, $cod_usuario = ''){
        $coduser = $this->getCodUsuario($cod_usuario);
        if($coduser == 0){return false;}
        if($this->cookieExistsTest($coduser, $tag)){return true;}
        $tagid = $this->LoadModel('usuario/tag', 'tag')->getTagId($tag);
        if(trim($tagid) === ""){return false;}
        return $this->updateTag($tagid, $coduser);
        
    }
            private function getCodUsuario($cod_usuario){
                if($cod_usuario !== ""){return $cod_usuario;}
                return usuario_loginModel::CodUsuario();
            }
            
            private function cookieExistsTest($cod_usuario, $tag){
                $tagname = (is_array($tag))?$tag['tag']:$tag;
                $cookie  = "$cod_usuario/$tagname";
                if(\classes\Classes\cookie::exists($cookie)){return true;}

                $time = (is_array($tag) && isset($tag['tag_expires_time']))?$tag['tag_expires_time']:1;
                \classes\Classes\cookie::create($cookie, 86400 * $time);
                return false;
            }
            
            private function updateTag($tagid, $cod_usuario){
                $data = $this->selecionar(array('cod_tag'), "cod_tag='$tagid' AND cod_usuario='$cod_usuario'", 1);
                if(!empty($data)){
                    return $this->editar(array($cod_usuario, $tagid), array(
                        'dt_tag' => 'FUNC_NULL'
                    ));
                }
                return $this->inserir(array(
                    'cod_tag'     => $tagid,
                    'cod_usuario' => $cod_usuario,
                ));
            }
    
    public function getUserTags($cod_usuario = ""){
        if($cod_usuario === ""){$cod_usuario = usuario_loginModel::CodUsuario();}
        $this->join('usuario/tag', array('cod_tag'), array('cod_tag'),"LEFT");
        return $this->selecionar(array('tag',"$this->tabela.cod_tag",'dt_tag'), "$this->tabela.cod_usuario='$cod_usuario'");
    }
            
    public function removeTag($tag, $cod_usuario = ''){
        $tagid = $this->LoadModel('usuario/tag', 'tag')->getTagId($tag);
        if(trim($tagid) === ""){return false;}
        return $this->apagar(array($cod_usuario, $tagid));
    }
    
    public function importTagsFromAcesso(){
        $bool = $this->LoadClassFromPlugin('usuario/tag/support/importTags', 'itag')->importTags();
        if($bool === false){
            $this->setMessages($this->itag->getMessages());
            return false;
        }
        return $this->setSuccessMessage("Tags Importadas com sucesso!");
    }
    
    public function hasTag($user, $tagname){
        $tagid = $this->LoadModel('usuario/tag', 'tag')->getTagId($tagname);
        if(trim($tagid) === ""){return false;}
        $res = $this->selecionar(array('cod_tag' => $tagid, 'cod_usuario'=>$user));
        return (!empty($res));
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