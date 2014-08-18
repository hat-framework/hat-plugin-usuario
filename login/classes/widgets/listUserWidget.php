<?php

class listUserWidget extends \classes\Component\widget{
    protected $pgmethod  = "paginate";
    protected $method    = "listFilthers";
    protected $modelname = "usuario/login";
    protected $arr       = array('cod_usuario','user_name', 'email', 'cod_perfil', 'tipo_cadastro', 'status');
    protected $link      = '';
    protected $where     = "";
    protected $qtd       = "10";
    protected $order     = "";
    protected $title     = "Todos os usuários";


    public function __construct() {
        parent::__construct();
        $search = filter_input(INPUT_GET, 'user_search');
        if($search !== false && $search != ""){
            $this->where .= "(user_name LIKE '%$search%' OR email LIKE '%$search%')";
            $this->title = "Com nome ou email contendo '$search'";
        }
    }
}