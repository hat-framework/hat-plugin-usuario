<?php

class listUserWidget extends \classes\Component\widget{
    protected $pgmethod  = "paginate";
    protected $method    = "listFilthers";
    protected $modelname = "usuario/login";
    protected $tb        = "";
    protected $arr       = array(
        'cod_usuario','user_name', 'email', 'cod_perfil', 'tipo_cadastro', 'status','user_criadoem', 'user_uacesso','indicado'
     );
    protected $link      = '';
    protected $where     = "";
    protected $qtd       = "10";
    protected $order     = "";
    protected $title     = "Todos os usuários";


    public function __construct() {
        parent::__construct();
        $this->tb = $this->LoadModel('usuario/login', 'uobj')->getTable();
        $search   = filter_input(INPUT_GET, 'widget');
        if($search !== "listUser"){return;}
        $this->filter_camp("user_string"        , array("cod_usuario","user_name", "user_cargo", "email"), "LIKE");
        $this->filter_camp("cod_perfil"         , array("cod_perfil"));
        $this->filter_camp("confirmed"          , array("confirmed"));
        $this->filter_camp("status"             , array("status"));
        $this->filter_camp("tipo_cadastro"      , array("tipo_cadastro"));
        $this->filter_camp("user_criadoem"      , array("user_criadoem"), " >= ");
        $this->filter_camp("user_criadoem_ate"  , array("user_uacesso"), " <= ");
        $this->filter_camp("user_uacesso"       , array("user_uacesso"), " >= ");
        $this->filter_camp("user_uacesso_ate"   , array("user_uacesso"), " <= ");
        $this->indicado();
        if(empty($this->wh)){return;}
        $this->where = "(".implode(") AND (", $this->wh).")";
        //echoBr($this->where );
        $this->title = "Usuários filtrados";
    }
    
//    public function getItens(){
//        $out = parent::getItens();
//        echo $this->model->db->getSentenca();
//        return $out;
//    }
    
    private $wh = array();
    private function filter_camp($campname, $fields, $comp_type = "=", $join = "OR"){
        if(!isset($_GET[$campname])){return;}
        $data = $_GET[$campname];
        if(!is_array($data) && trim($data) === ""){return;}
        
        if(!is_array($fields)){$fields = array($fields);}
        $wh = array();
        foreach($fields as $field){
            if(!is_array($data)){
                $wh[] = $this->getLineWhere($data, $field, $comp_type);
                continue;
            }
            foreach($data as $d){
                $wh[] = $this->getLineWhere($d, $field, $comp_type);
            }
        }
        $this->wh[] = "(".implode(" $join ", $wh) . " ) ";
    }
    
    private function getLineWhere($value, $camp, $comp_type){
        $value = ($comp_type === "LIKE")?"%$value%":"$value";
        if($value !== 'NULL'){$value = "'$value'";}
        return "$this->tb.$camp $comp_type $value";
    }
    
    private function indicado(){
        if(!isset($_GET['indicado'])){return;}
        $val = $_GET['indicado'];
        $_GET['indicado'] = "NULL";
        $this->filter_camp('indicado'  , array('indicado'), ($val === 'n')?" IS ":" IS NOT ");
    }
}