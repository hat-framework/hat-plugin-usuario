<?php

class codigoComponent extends \classes\Classes\Object{
    
    private $dados = array();
    private $class = "col-xs-12 col-sm-6 col-lg-4";
    private $id    = "referrer_code";
    private $modelname = 'usuario/referencia';
    public function __construct() {
        $this->gui = new \classes\Component\GUI();
        $this->cod = usuario_loginModel::CodUsuario();
        $this->LoadResource('html', 'html');
        $this->LoadModel($this->modelname, 'ref');
        $this->LoadComponent($this->modelname, 'comp');
    }
       
    public function show(){
        $this->gui->openDiv($this->id);
            $this->gui->openDiv("{$this->id}_general", 'col-xs-12 col-lg-5');
                $this->referrerCode();
                $this->myReferrers();
            $this->gui->closeDiv();
            $this->gui->openDiv("{$this->id}_general", 'col-xs-12 col-lg-7');
                $this->myInvitations();
            $this->gui->closeDiv();
        $this->gui->closeDiv();
    }
    
    private function referrerCode(){
        $url = $this->html->getLink("usuario/referencia/cadastro/$this->cod");
        $title = "<b>Compartilhe a url abaixo</b> com os usuários que você deseja convidar para o sistema";
        $this->gui->openPanel('', "{$this->id}_code")
                  ->panelHeader("Url de Compartilhamento", 'fa fa-exchange')
                  ->panelBody("$title<input type='text' class='form-control' readonly value='$url'/>")
                  ->closePanel();
    }
    
    private function myReferrers(){
        $data = $this->ref->getReferrers($this->cod);
        if(empty($data)){return;}
        $this->gui->openPanel('', "{$this->id}_code")
                  ->panelHeader("Convidado Por", 'fa fa-users')
                  ->panelBody($this->comp->listInTable($this->modelname,$data))
                  ->closePanel();
    }
    
    private function myInvitations(){
        $data = $this->ref->getMyInvitations($this->cod);
        $this->gui->openPanel('', "{$this->id}_code")
                  ->panelHeader("Meus Convites", 'fa fa-envelope')
                  ->panelBody(!empty($data)?
                        $this->comp->listInTable($this->modelname,$data):
                        "Nenhum usuário se cadastrou ainda no sistema com um convite seu! "
                   )
                  ->closePanel();
        
    }
}