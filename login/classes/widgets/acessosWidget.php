<?php

class acessosWidget extends \classes\Component\widget{
    protected $pgmethod  = "paginate";
    protected $method    = "listFilthers";
    protected $modelname = "usuario/login";
    protected $arr       = array('cod_usuario', 'user_name', 'email', 'user_uacesso', 'status');
    protected $link      = '';
    protected $where     = "";
    protected $qtd       = "4";
    protected $order     = "user_uacesso DESC";
    protected $title     = "Últimos acessos";
    //protected $actionPaginator = 'widgets/acessos';
    
    public function __construct() {
        parent::__construct();
        $this->where = "cod_perfil NOT IN ('".Webmaster."','". Admin."')";
        $search = filter_input(INPUT_GET, 'user_search');
        if($search !== false && $search != ""){
            $this->where .= " AND (user_name LIKE '%$search%' OR email LIKE '%$search%')";
            $this->title = "$this->title - Filtro: '$search'";
        }
    }
}