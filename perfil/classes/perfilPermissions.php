<?php

use classes\Classes\Object;
use classes\Classes\session;
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
        $this->user_cod_perfil = (isset($_REQUEST['userID']) && $_REQUEST['userID'] == '1')?usuario_loginModel::CodPerfil():$this->uobj->getCodPerfil();
        $this->cod_perfil      = $this->perfilVisualization();
        if(is_array($this->cod_perfil)){
            $temp = array_keys($this->cod_perfil);
            $this->cod_perfil = array_shift($temp);
        }
        $this->cod_usuario = usuario_loginModel::CodUsuario();
        $this->LoadPermissionFile();
    }
    
            /**
             * Load file with all user permissions
             * @throws classes\Exceptions\AcessBloquedException (if permission's file doesn't exists and system cannot create)
             * @author Thom <thom@hat-framework.com>
             */
            private function LoadPermissionFile($forceRemount = false){
                //Load permissions from file
                if(!$forceRemount){
                    $this->permissions = json_decode(classes\Utils\cache::get("usuario/perfil/p$this->cod_perfil", 'php'));
                    if(!empty($this->permissions)){return;}
                }
                //if file doesn't exists or if permissions not setted, create permissions file
                $this->LoadModel('plugins/plug', 'plug')->mountPerfilPermissions();
                $this->permissions = json_decode(classes\Utils\cache::get("usuario/perfil/p$this->cod_perfil", 'php'));

                //if permission file doesn't exists, throw exception
                if(empty($this->permissions)){
                    $url = $_SERVER['HTTP_HOST'];
                    sendEmailToWebmasters("Permissão $this->cod_perfil", "Perfil de usuário '$this->cod_perfil' sem permissão. "
                            . "Usuário que tentou acessar: <a href='$url/usuario/login/show/$this->cod_usuario'>$this->cod_usuario</a>");
                    if($_GET['url'] == 'usuario/login/logout'){
                        $this->uobj->Logout();
                        throw new classes\Exceptions\AcessBloquedException(
                            "Você não pode acessar o sistema. "
                            . "Pode ser algum problema temporário com as permissões"
                            . " ou algum administrador bloqueou o seu acesso. "
                            . "Tente novamente mais tarde. <hr> Recarregue a página para deslogar.");
                    }else{
                        Redirect('usuario/login/logout');
                    }
                }
            }
    
    /**
     * Verify if user has permission to access some page
     * @param string $action_name name of haturl
     * @param boolean $getPermissionString
     * @return mixed string if getPermissionString === true, boolena otherwise
     * @author Thom <thom@hat-framework.com>
     */
    public function hasPermission(&$action_name, $getPermissionString, $changedPermission = false){
        //atualiza as permissoes
        if($changedPermission){
            $this->uobj->makeLogin($this->cod_usuario);
            $this->LoadPermissionFile(true);
//            $this->uobj->permissoes_alteradas_done();
        }
        
        //corrige o nome da action
        $this->act->prepare_action($action_name);
        
        //se é webmaster
        if($this->cod_perfil == Webmaster){return ($getPermissionString)?'s':true;}
        
        //se não possui a permissão
        if(false === in_array($action_name, $this->permissions)){
            $i = strlen($action_name)-1;
            if($i < 0){$i = 0;}
            if(strlen($action_name) == 0 || !isset($action_name[$i])){return false;}
            $last = $action_name[$i];
            if(!is_numeric($last)){return ($getPermissionString)?'n':false;}
            $trueaction = substr($action_name, 0, $i);
            return $this->hasPermission($trueaction, $getPermissionString);
        }
        
        //se possui a permissão
        return ($getPermissionString)?'s':true;
    }
    
    /**
     * Load all urls with perfil permissions.
     * @param int $cod_perfil cod of user perfil
     * @return array array with all $cod_perfil's permissions
     * @author Thom <thom@hat-framework.com>
     */
    public function getPerfilPermissions($cod_perfil){
        $permissions = json_decode(classes\Utils\cache::get("usuario/perfil/p$cod_perfil", 'php'));
        if(empty($permissions)){return array();}
        foreach($permissions as $perm){
            $out[$perm] = 's';
        }
        return $out;
    }
    
    /**
     * Load all perfil permissions name.
     * @param int $cod_perfil cod of user perfil
     * @return array array with all $cod_perfil's permissions
     * @author Thom <thom@hat-framework.com>
     */
    public function getPerfilPermissionsName($cod_perfil){
        return json_decode(classes\Utils\cache::get("plugins/permissions/p$cod_perfil", 'php'));
    }
    
    /**
     * Verify if an action need code
     * @param string $action_name name of haturl
     * @return boolean true if action need code, false otherwise
     * @author Thom <thom@hat-framework.com>
     */
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
    
    /**
     * Redirect user to $action_name page, if he has $action_name's permission
     * @param string $action_name name of haturl
     * @author Thom <thom@hat-framework.com>
     */
    public function RedirectIfHasPermission($action_name){
        if($this->hasPermission($action_name, false, false)){
            Redirect($action_name);
        }
    }
    
    public function hasPermissionByName($permname){
        $iswebmaster = usuario_loginModel::IsWebmaster();
        if($iswebmaster && !array_key_exists('_perfil', $_GET)){return true;}
        $cod_perfil = usuario_loginModel::CodPerfil();
        $key        = ($iswebmaster)?"$this->cookie/$cod_perfil":"$this->cookie";
        $perm       = session::getVar($key);
        if(empty($perm) && $cod_perfil != ""){
            $perm = $this->getPerfilPermissionsName($cod_perfil);
            session::setVar($key, $perm);
        }
        if(!is_array($perm)){$perm = array();}
        //print_rh($perm); echoBr($permname);
        return(in_array($permname, $perm));
    }
    
    public function isPublic($action_name){
        $this->act->prepare_action($action_name);
        $var = session::getVar($this->cookie_public);
        if(!is_array($var)) {return true;}
        //print_r($var); echo "$action_name";
        return(array_key_exists($action_name, $var));
    }
    
    public function perfilVisualization(){
        $cod_perfil = usuario_loginModel::CodPerfil();
        $perfil = filter_input(INPUT_GET, '_perfil');
        if(trim($perfil) === "" || $perfil === Webmaster){return $cod_perfil;}
        $this->LoadModel('usuario/perfil', 'perf');
        return(false === $this->perf->checkUserCanAlter(usuario_loginModel::CodUsuario()))?$cod_perfil:$perfil;
    }
}