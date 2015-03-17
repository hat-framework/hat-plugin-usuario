<?php 

class usuario_tagModel extends \classes\Model\Model{
    public  $tabela = "usuario_tag";
    public  $pkey   = 'cod_tag';
    
    public function getTagId($tagname){
        $tag  = $this->antinjection($tagname);
        if(trim($tag) === ""){return "";}
        $data = $this->getField($tag, 'cod_tag', 'tag');
        if(is_numeric($data)){return $data;}
        return (false === $this->inserir(array('tag' => $tagname)))?"":$this->getLastId();
    }
}