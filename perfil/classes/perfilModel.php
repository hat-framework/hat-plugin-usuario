<?php 

class usuario_perfilModel extends \classes\Model\Model{
    public  $tabela = "usuario_perfil";
    public  $pkey   = 'usuario_perfil_cod';
    
    public function __construct() {
        parent::__construct();
        $this->LoadModel('plugins/permissao', 'perm');
        $this->LoadModel('plugins/plug', 'plug');
        $this->LoadModel('usuario/login', 'uobj');
    }
    
    public function setModelName($model) {
        static $path = "";
        static $cod = "";
        parent::setModelName($model);
        if($path === ""){
            $cod = $this->uobj->getCodPerfil();
            $path = ($cod == "")?"/".Webmaster:$this->getPathPerfil($cod);
        }
        $this->dados['path']['default'] = $path;
        $this->pathWhere = ($cod == Admin || $cod == Webmaster)?"":"usuario_perfil.path LIKE '$path%'";
    }

    /*
     * Retorna o código do perfil de usuário, se o valor enviado é um array, procura pelo índice
     * usuario_perfil_cod, se for um número, retorna o próprio número
     */
    public function getCodPerfil($cod_perfil){
        return (is_array($cod_perfil) && array_key_exists("usuario_perfil_cod", $cod_perfil))?$cod_perfil['usuario_perfil_cod']:$cod_perfil;
    }
    
    /*
     * Retorna o nome do perfil de usuário enviado por parâmetro. 
     */
    public function getNamePerfil($perfil){
        return (!is_array($perfil) || !array_key_exists("usuario_perfil_nome", $perfil))?"":$perfil['usuario_perfil_nome'];
    }
    
    /*
     * Torna um perfil como padrão
     */
    public function setDefaultPerfil($cod_perfil){
        if($cod_perfil == Webmaster || $cod_perfil == Admin) {
            $this->setErrorMessage("Os Perfis de Administrador não podem ser marcados como padrão!");
            return false;
        }
        $query = "UPDATE $this->tabela SET usuario_perfil_default = 0";
        if(!$this->db->ExecuteInsertionQuery($query)){
            //echo $this->db->getSentenca();
            if(DEBUG)$this->setErrorMessage($this->db->getErrorMessage());
            else     $this->setErrorMessage("Não foi possível marcar o perfil como padrão.");
            return false;
        }
        
        $query = "UPDATE $this->tabela SET usuario_perfil_default = 1 WHERE usuario_perfil_cod  = '$cod_perfil'";
        if(!$this->db->ExecuteInsertionQuery($query)){
            //echo $this->db->getSentenca();
            if(DEBUG)$this->setErrorMessage($this->db->getErrorMessage());
            else     $this->setErrorMessage("Não foi possível marcar o perfil como padrão.");
            return false;
        }
        
        $this->setSuccessMessage("Perfil padrão alterado com sucesso!");
        
        return true;
    }
    
    
    /*
     * retorna o código e o nome do perfil padrão do sistema. Se não tiver nenhum o perfil
     * de visitante é retornado
     */
    public function getDefaultPerfil(){
        $var = $this->selecionar(array('usuario_perfil_cod', 'usuario_perfil_nome'), "usuario_perfil_default = '1'", 1);
        if(empty($var)) return array('1' => "Visitante");
        $var = array_shift($var);
        return array($var['usuario_perfil_cod'] => $var["usuario_perfil_nome"]);
    }
    
    private $ignorepath = false;
    public function ignorePath(){
        $this->ignorepath = true;
        return $this;
    }
    
    public function selecionar($campos = array(), $where = "", $limit = "", $offset = "", $orderby = "") {
        if(!$this->ignorepath){
            $where   = ($where == "")?
                $this->pathWhere:
                (($this->pathWhere == "")?$where:"$this->pathWhere AND ($where)");
        }
        $this->ignorepath = false;
        
        $orderby = ($orderby == "")?"usuario_perfil.path ASC, usuario_perfil_nome ASC":$orderby;
        $var = parent::selecionar($campos, $where, $limit, $offset, $orderby);
        //echo $this->db->getSentenca() . "<br/><br/>";
        return $var;
    }
    
    public function paginate($page, $link = "", $cod_item = "", $campo = "", $qtd = 20, $campos = array(), $adwhere = "", $order = "") {
        $order = ($order == "")?"path ASC, usuario_perfil_nome ASC":$order;
        return parent::paginate($page, $link, $cod_item, $campo, $qtd, $campos, $adwhere, $order);
    }
    
    //edita os dados contanto que o perfil não seja de um perfil incial do sistema
    public function editar($id, $post, $camp = "") {
        if(!$this->checkUserCanAlter($id)) {return false;}
        if(!$this->perfil_padrao_erro($id, false)) {return false;}
        if(!parent::editar($id, $post, $camp)) {return false;}
        
        if($camp != "") {return true;}
        $data['path'] = $this->getPath($id);
        if(false === parent::editar($id, $data, $camp)){return false;}
        $this->updatePermissions();
        return true;
    }
    
    public function inserir($dados) {
        if(!parent::inserir($dados)) {return false;}
        $id = (isset($dados['usuario_perfil_cod']) && $dados['usuario_perfil_cod'] != "") ? 
              $dados['usuario_perfil_cod']: $this->getLastId();
        
        $post['path'] = $this->getPath($id);
        if(!parent::editar($id, $post)) {
            $this->setAlertMessage($this->getErrorMessage());
            $this->setErrorMessage("");
        }
        $this->LoadModel('plugins/acesso', 'acc')->setDefaultPermissions($id);
        $this->updatePermissions();
        return $this->setSuccessMessage("Perfil ".$dados['usuario_perfil_nome']." criado corretamente!");
    }
    
    private function updatePermissions(){
        if(in_array(CURRENT_ACTION, array('formulario', 'edit'))){
            $this->LoadModel('plugins/plug','plug')->mountPerfilPermissions();
        }
    }
    
    //apaga os dados contanto que o perfil não seja de um perfil incial do sistema
    public function apagar($valor, $chave = "") {
        if(!$this->checkUserCanAlter($valor)) {return false;}
        if(!$this->perfil_padrao_erro($valor, true)) {return false;}
        return parent::apagar($valor, $chave);
    }
    
    /*
     * checa se o código do perfil está dentro os perfis iniciais do sistema (que não podem ter os dados alterados e não
     * podem ser excluídos)
     */
    private function perfil_padrao_erro($id, $try_drop = true){
        
        //se usuário está tentando apagar um perfil que possui pessoas cadastradas
        if($try_drop && $this->uobj->getTotalUsuariosPorPerfil($id) > 0) {
            $this->setErrorMessage("Não é possível apagar um perfil que possui usuários.");
            return false;
        }
        
        //se o perfil não é criado pelo sistema ou se não está tentando apagá-lo
        $total = $this->getCount("usuario_perfil_tipo = 'sistema' AND usuario_perfil_cod = '$id'");
        if(!$try_drop && $total == 0) return true;
        
        //se usuário é webmaster
        $this->LoadModel('usuario/login', 'uobj');
        if($this->uobj->getCodPerfil() == Webmaster) return true;
        
        $this->setErrorMessage('Não é possível editar ou apagar um perfil padrão do sistema');
        return false;
    }
    
    /*
     * Verifica se o usuário pode alterar o perfil de usuário passado por parâmetro
     */
    public function checkUserCanAlter($id){
        $this->LoadModel('usuario/login', 'uobj');
        $cod_perfil = $this->uobj->getCodPerfil();
        
        $path = $this->getPathPerfil($cod_perfil);
        $total = $this->getCount("path LIKE '$path%' AND usuario_perfil_cod = '$id' AND usuario_perfil_cod != '$cod_perfil'");
        if($total == 0){
            if(!$this->uobj->UserIsWebmaster()){
                $this->setErrorMessage("Você não tem permissão para alterar este perfil de usuário");
                return false;
            }
        }
        return true;
    }
    
    /*
     * Compara o código de um perfil passado por parâmetro com 
     * o código do usuário que está online
     * Retorna true caso sejam iguais, false caso o contrário
     */
    public function checkPerfilIsOwnUser($cod){
        $this->LoadModel('usuario/login', 'uobj');
        return($this->uobj->getCodPerfil() == $cod);
    }
    

    public function hasPermissionByName($permname){
        $this->LoadClassFromPlugin('usuario/perfil/perfilPermissions', 'pp');
        return $this->pp->hasPermissionByName($permname);
    }
    
    /*
     * Recebe uma string contento Plugin/Subplugin/Action
     * Retorna true caso o usuário tenha permissão de acessar o sistema,
     * false caso contrário
     */
    public function hasPermission(&$action_name, $getPermissionString = false, $updatedPermission = false){
        $this->LoadClassFromPlugin('usuario/perfil/perfilPermissions', 'pp');
        return $this->pp->hasPermission($action_name, $getPermissionString, $updatedPermission);
    }
    
    private function getPath($id){
        $temp = parent::selecionar(array('usuario_perfil_pai'), "usuario_perfil_cod = '$id'");
        $pai = array_shift($temp);
        $pai = $pai['usuario_perfil_pai'];
        if($pai == ""){
            $this->LoadModel('usuario/login', 'uobj');
            $pai = $this->uobj->getCodPerfil();
        }
        $temp = parent::selecionar(array('path'), "usuario_perfil_cod = '$pai'");
        $path = array_shift($temp);
        return $path['path']."/$id";
    }
    
    public function getPathPerfil($cod){
        $select = parent::selecionar(array('path'), "usuario_perfil_cod = '$cod'");
        $path = array_shift($select);
        return $path['path'];
    }
    
    public function getAllAssoc(){
        $out = array();
        $arr = $this->selecionar(array('usuario_perfil_cod', 'usuario_perfil_nome'));
        foreach($arr as $a){
            $out[$a['usuario_perfil_cod']] = $a['usuario_perfil_nome'];
        }
        return $out;
    }
    
    public function getPermissoes($cod){
        $arr = $this->LoadModel('plugins/acesso', 'acc')->getPermittedOfPerfil($cod);
        if(empty($arr)){return $arr;}
        $out = array();
        foreach($arr as $a){
            $out[$a] = 1;
        }
        return $out;
    }

}