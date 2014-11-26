<?php 
class userConfigFormWidget extends \classes\Component\widget{
    
    protected $pgmethod  = "paginate";
    //protected $method    = "listFilthers";
    protected $modelname = "usuario/form";
    //protected $arr       = array('cod_usuario', 'user_name', 'email', 'user_uacesso', 'status');
    protected $link      = '';
    protected $where     = "";
    protected $qtd       = "0";
    //protected $order     = "user_uacesso DESC";
    //protected $title     = "Últimos acessos";
    
    public function getItens() {
        $out['form'] = $this->LoadModel('usuario/login','uobj')->getDados();
        $out['data'] = array();
        return $out;
    }
    
    public function draw($itens) {
        try{
            $this->LoadResource('formulario', 'form')->NewForm($itens['form'],$itens['data']);
        }  catch (\Exception $e){die("aaaaa");}
    }
    
    private $formId = '';
    public function setFormId($formId){
        $this->formId = $formId;
    }
    
    private $groupId = '';
    public function setGroupId($groupId){
        $this->groupId = $groupId;
    }
    
}
