<?php 

class usuario_tagModel extends \classes\Model\Model{
    public  $tabela = "usuario_tag";
    public  $pkey   = 'cod_tag';
    
    public function getTagId($tagname){
        $data = array('tag' => $tagname);
        if(is_array($tagname)){
            $data    = $tagname;
            $tagname = $tagname['tag'];
            $this->groupid($data);
        }
        $tag  = $this->antinjection($tagname);
        if(trim($tag) === ""){return "";}
        $value = $this->getField($tag, 'cod_tag', 'tag');
        if(is_numeric($value)){return $value;}
        return (false === $this->inserir($data))?"":$this->getLastId();
    }
    
            private function groupid(&$data){
                $group   = isset($data['taggroup'])?$data['taggroup']:"";
                $groupid = $this->LoadModel('usuario/tag/taggroup', 'tg')->getGroupId($group);
                if(trim($groupid) === "" || $groupid === false){return;}
                $data['taggroup'] = $groupid;
            }
}