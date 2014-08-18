<?php 
class usuario_userconfigModel extends \classes\Model\Model{
    
    public $tabela      = "usuario_userconfig";
    public $pkey        = 'cod';
    
    public function loadConfig($cod_config){
        $data          = $this->LoadModel('site/confgrupo', 'sconf')->getItem($cod_config);
        //$data['itens'] = $this->LoadModel('site/configuracao', 'sconf')->LoadFileForm($cod_config);
        //print_rd($data);
        return $data;
    }
    
    public function getAllUserGroups(){
        $out['userMenu'] = $this->loadUserMenu();
        $out['options']  = $this->loadUserOptions();
        return $out;
    }
    
    public function getConfig(){
        
    }
    
    private function loadUserMenu(){
        $arr = $this->selecionar(array());
        $out = array();
        foreach($arr as $g){
            $out[] = array('cod' => "{$g['name']}",'title' => $g['title'], 'icon' => $g['icon']);
        }
        return $out;
    }
    
    private function loadUserOptions(){
        $groups = $this->LoadModel('site/confgrupo', 'gr')->getGroupsOfUser("");
        $out = array();
        foreach($groups as $g){
            $out[] = array('cod' => $g['cod_confgrupo'],'title' => $g['name'], 'icon' => 'icon-tag');
        }
        return $out;
    }
    
}