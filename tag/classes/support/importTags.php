<?php

class importTags extends classes\Classes\Object{
    
    private $tagarray = array();
    private $array    = array();
    private $userarray= array();
    private $bool     = true;
    public function __construct() {
        $this->LoadModel('usuario/tag'        , 'tag');
        $this->LoadModel('usuario/tag/usertag', 'utag');
        $this->LoadModel('usuario/acesso'     , 'ua');
    }
    
    public function importTags(){
        $dados = $this->consultAcesso();
        foreach($dados as $dado){
            $group = $this->filtrarGrupo($dado);
            if($group === false){continue;}

            $tagid = $this->prepareTagId($group);
            if($tagid === false){continue;}
            
            $this->addToArray($tagid, $dado['cod_usuario'], $dado['data']);
            $this->addUserAtivo($dado);
        }
        return $this->importarTags();
    }
            private function addUserAtivo($dado){
                if(isset($this->userarray[$dado['cod_usuario']])){return;}
                $this->userarray[$dado['cod_usuario']] = true;
                $codtag = $this->prepareTagId("");
                if($codtag === false){return;}
                $this->addToArray($codtag, $dado['cod_usuario'], $dado['data']);
            }
    
            private function consultAcesso(){
                return $this->ua->selecionar(
                    array("group1", "cod_usuario",'data'), 
                    "NOW() - 30*86400 < data GROUP BY cod_usuario,group1"
                );
            }

            private function filtrarGrupo($dado){
                $temp           = urldecode($dado['group1']);
                $e              = explode("/", $temp);
                $group          = array_shift($e);
                return (trim($group) === "")?false:$group;
            }

            private function prepareTagId($group){
                if(isset($this->tagarray[$group])){return $this->tagarray[$group];}
                if(trim($group) !== ""){$group = " ".  ucfirst($group);}
                $tag   = "Usuário Ativo".$group; 
                $tagid = $this->tag->getTagId(array(
                    'taggroup' =>'Usuário Ativo','tag_expires_time' => '30','tag'=>$tag
                ));
                if(trim($tagid) === ""){return false;}
                $this->tagarray[$group] = $tagid;
                return $tagid;
            }

            private function addToArray($cod_tag, $cod_usuario, $dt_tag = ""){
                if(trim($dt_tag) === ""){$dt_tag = "FUNC_NULL";}
                $this->array[]                 = array('cod_tag' => $cod_tag, 'cod_usuario' => $cod_usuario,'dt_tag' => $dt_tag);
                if(count($this->array) < 1000){return;}
                $this->importarTags();
            }
            
            private function importarTags(){
                if(false === $this->utag->importDataFromArray($this->array)){
                    $this->appendErrorMessage($this->utag->getErrorMessage());
                    $this->bool = false;
                }
                unset($this->array);
                $this->array = array();
                return $this->bool;
            }
}
