<?php

class cadastrosWidget extends \classes\Component\widget{
    protected $pgmethod  = "paginate";
    protected $method    = "listFilthers";
    protected $modelname = "usuario/login";
    protected $arr       = array('cod_usuario', 'user_name', 'email', 'user_criadoem', 'status');
    protected $link      = '';
    protected $where     = "";
    protected $qtd       = "4";
    protected $order     = "user_criadoem DESC";
    protected $title     = "Na última semana";
    //protected $actionPaginator = 'usuario/login/widgets/cadastros';
    
    public function __construct() {
        parent::__construct();
        $data        = \classes\Classes\timeResource::subDateTime(\classes\Classes\timeResource::getDbDate(), '7');
        $search = filter_input(INPUT_GET, 'user_search');
        $this->where = "user_criadoem >= '$data' AND cod_perfil NOT IN ('".Webmaster."','". Admin."')";
        if($search !== false && $search != ""){
            $this->where .= " AND (user_name LIKE '%$search%' OR email LIKE '%$search%')";
            $this->title = "$this->title - Filtro: '$search'";
        }
    }
}