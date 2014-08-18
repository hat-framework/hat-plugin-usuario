<?php 
class usuario_mensagemModel extends \classes\Model\Model{
    public $tabela      = "usuario_mensagem";
    public $pkey        = 'cod';
    protected $feature  = "USUARIO_MENSAGEM";
    
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        parent::__construct();
    }
    
    public function getGroups($cod_usuario){
        $this->LoadModel('usuario/perfil', 'perf');
        $perfil = $this->uobj->getCodPerfil($cod_usuario);
        $where  = "";
        if(!in_array($perfil, array(Webmaster, Admin))){
            //if(false === getBoleanConstant('USUARIO_MENSAGEM_ANY_USER')){return array();}
            $where  = "usuario_perfil_cod='$perfil'";
        }
        $all = array('usuario_perfil_cod' => 'todos', 'usuario_perfil_nome' => 'Todos Usuários');
        $out = $this->perf->selecionar(array('usuario_perfil_cod', 'usuario_perfil_nome'), $where);
        array_unshift($out, $all);
        return $out;
    }
    
    public function getFriendList($cod_usuario, $page = 0){
        $perfil = $this->uobj->getCodPerfil($cod_usuario);
        if(in_array($perfil, array(Webmaster, Admin))){
            return $this->getList($cod_usuario, $page);
        }
        $arr = $this->getLastInteractions($cod_usuario, $page);
        if(!empty($arr)){
            $where = "cod_usuario IN('".implode("','",$arr)."') OR cod_perfil IN('".Webmaster."','".Admin."')";
            return $this->getList($cod_usuario, $page, $where);
        }
        return $this->getList($cod_usuario, $page, "cod_perfil IN('".Webmaster."','".Admin."')");
    }
    
    private function getList($cod_usuario, $page, $where = ""){
        $limit   = 25;
        $offsset = $limit * $page;
        $wh      = "cod_usuario != '$cod_usuario'";
        $where   = ($where === "")?$wh:"$wh AND ($where)";
        $arr     = $this->getLastInteractions($cod_usuario, $page);
        $list    = $this->uobj->selecionar(array('cod_usuario', 'user_name', 'cod_perfil'), $where, $limit, $offsset);
        $out     = array();
        foreach($arr as $cod){
            foreach($list as $cod_list => $user){
                if($cod !== $user['cod_usuario']){continue;}
                $out[] = $user;
                unset($list[$cod_list]);
                break;
            }
        }
        if(empty($list)){return $out;}
        return array_merge($out, $list);
    }
    
    private function getLastInteractions($cod_usuario, $page = 0){
        $users = array();
        $where = "`from`='$cod_usuario' OR `to`='$cod_usuario'";
        $this->prepareUserList('to'  , $where, $users, $page);
        $this->prepareUserList('from', $where, $users, $page);
        return $users;
    }
    
    private function prepareUserList($col, $where, &$users, $page){
        $limit  = 10;
        $offset = ($page * $limit);
        $results = $this->selecionar(array("DISTINCT `$col` as cod_usuario"), $where, $limit, $offset, "data DESC");
        if(empty($results)){return array();}
        foreach($results as $res){
            $users[$res['cod_usuario']] = $res['cod_usuario'];
        }
    }
    
    public function LoadUserTalk($from, $to, $page = 0){
        $limit  = 10;
        $offset = $limit * $page;
        $from   = $this->antinjection($from);
        $to     = $this->antinjection($to);
        $type   = substr($to, 0, 5);
        $where  = "(`from`='$from' AND `to`='$to') OR (`from`='$to' AND `to`='$from')";
        if(in_array($type, array('todos', 'group'))){
            $data = "";
            //limita a visualização dos grupos apenas para a data após o ingresso do usuário no sistema
            if(true === getBoleanConstant("USUARIO_MENSAGEM_LIMIT_DATA")){
                $user   = $this->uobj->getItem($from, "", false, array('user_criadoem'));
                $data   = ($user['user_criadoem'] === "")?"":" AND data >= '{$user['user_criadoem']}'";
            }
            $where  = "(`to`='$to') $data";
        }
        $var    = $this->selecionar(array('mensagem', '`from`', '`to`', 'data', 'visualizada'), $where, $limit, $offset, "data DESC");
        return $var;
    }
    
    public function setRead($from, $to){
        $post  = array('visualizada' => 's');
        $where = "`from`='$from' AND `to`='$to'";
        if(!$this->db->Update($this->tabela,$post, $where)){
            $this->setErrorMessage($this->db->getErrorMessage());
            return false;
        }
        return true;
    }
    
    public function getFeatures($cod_usuario){
        $consts = returnConstants('USUARIO_MENSAGEM');
        $perfil = $this->uobj->getCodPerfil($cod_usuario);
        if(in_array($perfil, array(Webmaster, Admin))){
            foreach($consts as $name => $val){
                $consts[$name] = true;
            }
        }
        return $consts;
    }
    
}