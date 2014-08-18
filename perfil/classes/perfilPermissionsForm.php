<?php

use classes\Classes\Object;
class perfilPermissionsForm extends classes\Classes\Object{
    
    public function __construct() {
        $this->LoadModel('plugins/permissao', 'perm');
        $this->LoadModel('plugins/plug'     , 'plug');
        $this->LoadModel('usuario/login'    , 'uobj');
        $this->LoadModel('plugins/acesso'   , 'acc');
        $this->LoadResource('database', 'db');
    }
    
    public function savePermissions($cod_perfil, $post){
        
        $erro = array();
        foreach($post as $permission => $arr){
            
            $item = $this->perm->getSimpleItem($permission, array('plugins_permissao_cod'), 'plugins_permissao_nome');
            if(empty($item)) continue;
            
            $insert = array();
            $select = array($item['plugins_permissao_cod'],$cod_perfil);
            $arr = (is_array($arr))?array_shift($arr):$arr;
            $acc = $this->acc->getItem($select);
            $insert['plugins_acesso_permitir'] = ($arr == "0")?'n':'s';
     
            if(empty($acc)){
                $insert['usuario_perfil_cod']      = $cod_perfil;
                $insert['plugins_permissao_cod']   = $item['plugins_permissao_cod'];
                $insert['plugins_acesso_permitir'] = ($arr == "0")?'n':'s';
                if(!$this->acc->inserir($insert))
                    $erro[] = $this->acc->getErrorMessage();
            }else{
                if(!$this->acc->editar($select, $insert))
                    $erro[] = $this->acc->getErrorMessage();
            }
        }
        if(!$this->uobj->permissoes_alteradas($cod_perfil)){
            $this->setAlertMessage($this->uobj->getMessages());  
        }
        
        if(!empty($erro)){
            $this->setErrorMessage($erro);
            return false;
        }
        
        $this->setSuccessMessage("Perfil salvo com sucesso!");
        return true;
    }
    
    public function genarateForm(){
        
        $data    = array();
        $this->db->Join($this->perm->getTable(), $this->plug->getTable());
        $actions = $this->perm->selecionar(array(
            'plugins_permissao_nome', 'cod_plugin', 
            'pluglabel', 'plugnome',
            'plugins_permissao_label','plugins_permissao_descricao'
        ));
        $plugin  = "";
        $permissions = $this->getPermissionsOfUser();        
        foreach($actions as $act){
            $name       = $act['plugins_permissao_nome'];
            if(!array_key_exists($name, $permissions)) continue;
            if($plugin != $act['cod_plugin']){
                $plugin = $act['cod_plugin'];
                $data[$name]['fieldset'] = ($act['pluglabel'] == "")?ucfirst($act['plugnome']):$act['pluglabel'];
            }
            
            $data[$name]['name']        = $act['plugins_permissao_label'];
            $data[$name]['type']        = 'bit';
            $data[$name]['default']     = 0;
            $data[$name]['description'] = $act['plugins_permissao_descricao'];
        }
        $data['button']['button'] = "Salvar Permissões";
        return $data;
    }
    
    /*
     * Esta função recupera as permissões do usuário que está logado e que está alterando
     * as permissões de algum perfil. O usuário só pode editar as permissões que ele mesmo 
     * possui, não podendo portanto editar as permissões que não possui.
     */
    private function getPermissionsOfUser(){
        //recupera as permissões que este usuário tem acesso
        $cod_perfil = $this->uobj->getCodPerfil();
        $temPermissao = array();
        
        //seleciona as permissões para o formulário de acordo com as permissões do usuário
        //que está editando os dados
        if($cod_perfil != 3){
            $this->db->Join($this->perm->getTable(), $this->acc->getTable());
            $selected = $this->perm->selecionar(array(), 
                    "usuario_perfil_cod = '$cod_perfil' AND plugins_acesso_permitir = 's'"
            );
        }
        else $selected = $this->perm->selecionar();
        foreach($selected as $sel) $temPermissao[$sel['plugins_permissao_nome']] = 's';
        return $temPermissao;
    }
    
}

?>