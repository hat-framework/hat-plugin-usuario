<?php

use classes\Classes\Object;
use classes\Classes\cookie;
class perfilPermissions extends classes\Classes\Object{
    
    private   $action_name         = "";
    private   $getPermissionString = "";
    private   $update_permissions  = "";
    private   $cod_perfil          = "";
    protected $uobj                = null;
    protected $act                 = null;
    protected $acc                 = null;
    protected $perm                = null;
    protected $db                  = null;
    private   $cookie_perm         = "usuario_perm";
    private   $cookie              = "usuario_permissoes";
    private   $cookie_public       = 'usuario_public';
    
    public function __construct() {
        $this->LoadModel('usuario/login'    , 'uobj');
        $this->LoadModel('plugins/action'   , 'act');
        $this->LoadModel('plugins/acesso'   , 'acc');
        $this->LoadModel('plugins/permissao', 'perm');
        $this->LoadResource('database', 'db');
        $this->user_cod_perfil = $this->uobj->getCodPerfil();
    }
    
    public function getPerfilPermissions($cod_perfil){
        $this->LoadModel('plugins/acesso', 'acesso');
        $this->db->Join($this->acesso->getTable(), $this->perm->getTable());
        $permissoes = $this->acesso->selecionar(array('plugins_permissao_nome', 'plugins_acesso_permitir'), "usuario_perfil_cod = '$cod_perfil'");
        $out = array();
        foreach($permissoes as $perm)
            $out[$perm['plugins_permissao_nome']] = ($perm['plugins_acesso_permitir'] == "s")?"1":"0";
        return $out;
    }
    
    public function hasPermission(&$action_name, $getPermissionString, $update_permissions){

        $this->init($action_name, $getPermissionString, $update_permissions);
        
        //carrega a pemissão
        $permission = $this->loadPermissions();
        
        //se permissão é negativa, verifica se existe alguma pemissão explícita para o usuário
        $bool = true;
        if($permission == "n"){
            $bool = ($this->user_cod_perfil == Webmaster);
            $new_perf = $this->perfilVisualization();
            if($bool === true && $new_perf !== $this->user_cod_perfil){$bool = false;}
            if($bool || $action_name === "usuario/login/index") {$permission = 's';}
        }
        $action_name = $this->changeActionName($action_name);
        
        return ($this->getPermissionString == false)?$bool:$permission;
    }
    
    private function changeActionName($action_name){
        static $cache_actname = array();
        if(!array_key_exists($action_name, $cache_actname)){
            $act = $action_name;
            if($this->NeedCod($action_name)) {
                $exp  = explode("/", $this->action_name);
                $model = $exp[0]."/".$exp[1];
                if(isset($_SESSION[$model])){
                    $act  = $action_name . "/";
                    $act .= is_array($_SESSION[$model])?  implode("/", $_SESSION[$model]):$_SESSION[$model];
                }else $act = $action_name;
            }
            $cache_actname[$action_name] = $act;
        }
        return $cache_actname[$action_name];
    }
    
    private function NeedCod($action_name){
        static $obj_arr = array();
        
        $action_name = str_replace("//", '/', $action_name);
        $exp  = explode("/", $action_name);
        if(count($exp) > 3){
            foreach($exp as $i => $e){
                if($e == "") unset($exp[$i]);
            }
            if(count($exp) > 3) return false;
        }
        $plugin = $exp[0];
        if(!array_key_exists($plugin, $obj_arr)){
            $file = classes\Classes\Registered::getPluginLocation($plugin, true) ."/Config/{$plugin}Actions.php";
            if(!file_exists($file)) return false;
            require_once $file;

            $class = $exp[0]."Actions";
            if(!class_exists($class)){ return false; }
            $obj_arr[$plugin] = new $class();
        }
        $obj = $obj_arr[$plugin];
        $act = $obj->getAction($this->action_name);
        return(array_key_exists("needcod", $act) && $act['needcod'] == true);
        //return(array_key_exists($action_name, $cookie));
    }
    
    public function RedirectIfHasPermission($action_name){
        if($this->hasPermission($action_name, false, false)){
            Redirect($action_name);
        }
    }
    
    public function hasPermissionByName($permname){
        if(usuario_loginModel::IsWebmaster() && !array_key_exists('_perfil', $_GET)){return true;}
        $cod_perfil = $this->perfilVisualization();
        $true_perf  = usuario_loginModel::CodPerfil();
        $perm       = array();
        if($cod_perfil === $true_perf){$perm = cookie::getVar($this->cookie_perm);}
        
        if(empty($perm)){
            $cod_perfil = $this->perfilVisualization();
            if($cod_perfil != ""){
                $perm = $this->getPerfilPermissions($cod_perfil);
                if($cod_perfil === $true_perf){cookie::setVar($this->cookie_perm, $perm);}
            }
        }
        
        if(!is_array($perm))$perm = array();
        
        return(array_key_exists($permname, $perm) && $perm[$permname] == 1);
    }
    
    private function init($action_name, $getPermissionString, $update_permissions){
        $this->action_name         = $action_name;
        $this->getPermissionString = $getPermissionString;
        $this->update_permissions  = $update_permissions;
        $this->cod_perfil          = $this->perfilVisualization();
    }
    
    /*
     * Recebe uma string contento Plugin/Subplugin/Action
     * Carrega todas as permissões do usuário em um cookie.
     * Retorna s => caso o usuário possa ver todos os dados de uma action
     *         n => caso o usuário não possa ver nada naquela action
     *         p => caso o usuário possa ver apenas os próprios dados
     */
    private function loadPermissions(){
        
        //verifica se existe alguma atualização nas permissões do usuário
        if($this->update_permissions){
            cookie::destroy($this->cookie_perm);
            $this->uobj->isUpdatedPermissions();
        }
        
        //verifica se o usuário foi bloquado no sistema
        if($this->uobj->isBloqued()){
            throw new AcessDeniedException("O seu acesso ao sistema foi bloqueado por um administrador");
        }
        
        //cria os cookies
        $this->genCookies();
        
        //corrige o nome da ação caso tenha algum caractere inválido
        $this->act->prepare_action($this->action_name);
        
        //recupera o cookie gravado
        $perm = cookie::getVar($this->cookie);
        
        //se ação não existe, então ela é proibida
        if(!array_key_exists($this->action_name, $perm)) return "n";
        return $perm[$this->action_name];
    }
    
    //usado em loadPermissions para gerar os cookies
    private function genCookies(){
        
        //cria os cookies caso nao exista
        if(!cookie::cookieExists($this->cookie_perm) || cookie::getVar($this->cookie_perm) == ""){
            if($this->cod_perfil != ""){
                $perm = $this->getPerfilPermissions($this->cod_perfil);
                cookie::setVar($this->cookie_perm, $perm);
            }
        }
        //if(!cookie::cookieExists($this->cookie) || cookie::getVar($this->cookie) == ""){
            if($this->cod_perfil != "") $this->LoadLoggedPermissions();
            else                  $this->LoadUnloggedPermissions();
        //}
    }
    
    /*
     * Carrega as permissões para usuários que autenticados no sistema
     */
    private function LoadLoggedPermissions(){
        
        $this->db->Join($this->act->getTable(), $this->perm->getTable());
        $this->db->Join($this->perm->getTable(), $this->acc->getTable());
        $permissions = $this->act->selecionar(array(
            'plugins_permissao_label', 'plugins_action_nome', 
            'plugins_acesso_permitir', 'plugins_action_groupyes',
            'plugins_action_groupno' , 'plugins_action_privacidade',
        ), "usuario_perfil_cod = '$this->cod_perfil'");
        
        $arr = $this->LoadUnloggedPermissions();
        if(empty($permissions)) {
            cookie::setVar($this->cookie, $arr);
            return;
        }
        
        $varr = array();
        foreach($permissions as $perm){
            
            //permissão inicial é vazia
            $permissao = "";

            //se ação é privada checa as permissões
            if($perm['plugins_action_privacidade'] == 'privado'){

                //se ação é permitida, verifica a permissão no grupo de permitidos
                if    ($perm['plugins_acesso_permitir'] == 's')  $permissao = $perm['plugins_action_groupyes'];

                //se ação não é permitida, verifica a permissão no grupo de não permitidas
                elseif($perm['plugins_acesso_permitir'] == 'n')  $permissao = $perm['plugins_action_groupno'];

                //se não é nem permitida nem bloqueada há algum erro e lança execeção
                else  throw new classes\Exceptions\modelException("A permissão ".$perm['plugins_permissao_label'] . " não foi definida
                    nem como permitida nem como bloqueada! Provável erro na instalação do script");
            }

            //se ação é pública, então é permitido
            else {
                $varr[$perm['plugins_action_nome']] = '';
                $permissao = "s";
            }
            $arr[$perm['plugins_action_nome']] = $permissao;
        }
        //print_r($arr); die("aa");
        //salva a permissão em um cookie
        cookie::setVar($this->cookie, $arr);
        cookie::setVar($this->cookie_public, $varr);
    }
    
    
    /*
     * Carrega as permissões para usuários que não estão logados
     */
    private function LoadUnloggedPermissions(){
        $arr = $varr = array();
        
        $permissions = $this->act->selecionar(array('plugins_action_nome', 'plugins_action_privacidade'));
        foreach($permissions as $perm){    
            if($perm['plugins_action_privacidade'] != 'privado'){
                $varr[$perm['plugins_action_nome']] = '';
            }
            $permissao = ($perm['plugins_action_privacidade'] == 'privado')?"n":"s";
            $arr[$perm['plugins_action_nome']] = $permissao;
        }
        cookie::setVar($this->cookie, $arr);
        cookie::setVar($this->cookie_public, $varr);
        return $arr;
    }
    
    public function isPublic($action_name){
        $this->act->prepare_action($action_name);
        $var = cookie::getVar($this->cookie_public);
        if(!is_array($var)) {return true;}
        //print_r($var); echo "$action_name";
        return(array_key_exists($action_name, $var));
    }
    
    private function perfilVisualization(){
        $cod_perfil = usuario_loginModel::CodPerfil();
        $perfil = filter_input(INPUT_GET, '_perfil');
        if(trim($perfil) === "" || $perfil === Webmaster){return $cod_perfil;}
        $this->LoadModel('usuario/perfil', 'perf');
        return(false === $this->perf->checkUserCanAlter(usuario_loginModel::CodUsuario()))?$cod_perfil:$perfil;
    }
}

