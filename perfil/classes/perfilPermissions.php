<?php

use classes\Classes\Object;
use classes\Classes\cookie;
class perfilPermissions extends classes\Classes\Object{
    
    private   $action_name         = "";
    private   $getPermissionString = "";
    private   $cod_perfil          = "";
    protected $uobj                = null;
    protected $act                 = null;
    protected $acc                 = null;
    protected $perm                = null;
    protected $db                  = null;
    private   $permissions         = array();
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
        $this->cod_perfil      = $this->perfilVisualization();
        $this->permissions     = json_decode(classes\Utils\cache::get("usuario/perfil/p$this->cod_perfil", 'php'));
    }
    
    public function hasPermission(&$action_name, $getPermissionString){
        //corrige o nome da action
        $this->act->prepare_action($action_name);
        
        //se é webmaster
        if($this->cod_perfil == Webmaster){return ($getPermissionString)?'s':true;}
        
        //se não possui a permissão
        if(false === in_array($action_name, $this->permissions)){return ($getPermissionString)?'n':false;}
        
        //se possui a permissão
        return ($getPermissionString)?'s':true;
    }
    
    public function getPerfilPermissions($cod_perfil){
        $permissions = json_decode(classes\Utils\cache::get("usuario/perfil/p$cod_perfil", 'php'));
        if(empty($permissions)){return array();}
        foreach($permissions as $perm){
            $out[$perm] = 's';
        }
        return $out;
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