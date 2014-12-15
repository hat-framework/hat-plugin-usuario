<?php
use classes\Controller\CController;
use classes\Classes\cookie;
class loginController extends CController{

    public $model_name = "usuario/login";
    public function __construct($vars) {
        parent::__construct($vars);
        $this->model->atualizaStatus();
    }
    
    public function AfterLoad() {    
        $this->free_cod = array('index', 'formulario','inserir', 'logout', 'report', 'identidade',
            'reenviar',  'recuperar', 'confirmar', 'confirmrec', 'logado', 'widgets', 'alterar');
        if(CURRENT_ACTION == "index") return;
        if(in_array(CURRENT_ACTION, array('todos'))&& isset($_SESSION[LINK])) unset($_SESSION[LINK]);
        parent::AfterLoad();
    }

    public function index(){
        //se o usuario já está logado
        if($this->model->IsLoged()){
            if(!$this->model->Redirect()){
                Redirect("usuario/login/logado");
            }
        }

        //se usuario enviou algum dado
        if(!empty ($_POST)){
            if($this->model->Login($_POST['email_login'], $_POST['senha_login'])){
                $this->registerVar('status', '1');
                Redirect("usuario/login/logado");
            }
            $this->registerVar('status', '0');
        }

        //se usuario nao logou
        $this->setVars($this->model->getMessages());
        $this->genTags("Faça seu login");
        
        //se usuario ainda nao logou
        $this->display(LINK.'/login');
    }
    
    public function getIndexListType(){
        return "listInTable";
    }
    
    public function todos(){
        $this->genTags('Gerenciar Usuários');
        $this->model->atualizaStatus();
        $this->display('usuario/login/todos');
    }
    
    public function show($display = true, $link = ''){
        $cod = array_shift($this->vars);
        if($cod == "") Redirect ('usuario/login');
        $this->model->atualizaStatus($cod);
        //$this->genTags($this->item['user_name']);
        parent::show($display, $link);
    }
    
    public function inserir(){
        if(!empty ($_POST)){
            $status = $this->model->inserir($_POST);
            $vars   = $this->model->getMessages();
            if($status == true){
                $vars['status']   = '1';
                $vars['redirect'] = 'usuario/login';
            }else $vars['status'] = '0';
            
            $this->setVars($vars);
        }
        $this->genTags("Nova Conta");
        $this->display("usuario/login/inserir");
    }
    
    public function edit($display = true, $link = "") {
        if(!$this->model->UserCanAlter($this->cod)) {
            $this->redirect(LINK . "/show/$this->cod");
        }
        
        if($this->getTag('title') == ""){
            $this->genTags('Alterar '.$this->item['user_name']);
        }
        
        parent::edit($display, $link);
    }
    
    public function apagar() {
        if(!$this->model->UserCanAlter($this->cod)) {
            $this->redirect(LINK . "/show/$this->cod");
        }
        
        if($this->getTag('title') == ""){
            $this->genTags('Apagar '.$this->item['user_name']);
        }
        $this->redirect_droplink = LINK ."/report";
        parent::apagar();
    }
    
    public function alterar(){
        $var = $this->vars[0];
        $this->genTags('Alterar '. ucfirst($var));
        $this->editar("usuario/login/alterar/$var");
    }
    
    public function editar($url = "usuario/login/editar"){
        if(!$this->model->IsLoged()) {$this->model->needLogin();}
        $cod = $this->model->getUserId();
        if($cod == "") {$this->logout();}
        
        if(!empty ($_POST)){
            $status         = $this->model->editar($cod, $_POST);
            $vars           = $this->model->getMessages();
            $vars['status'] = ($status == true)? 1:0;
            $this->setVars($vars);
            if($status == true){
                $this->detectRedirect();
            }
        }
        $this->registerVar('item', $this->model->getItem($cod, "", true));
        $this->genTags("Modificar Dados");
        $this->display($url);
    }
    
    public function logout(){
        if($this->model->Logout()) Redirect("usuario/login/index");
        Redirect('usuario');
    }
    
    public function reenviar(){
        $user = array_shift($this->vars);
        $this->model->resend($user);
        $this->setVars($this->model->getMessages());
        $this->genTags("Reenviar Confirmação");
        $this->display('usuario/login/reenviar');
    }
    
    public function logado(){
        if(!$this->model->IsLoged()) Redirect("usuario/login/");
        $this->registerVar('type', isset($this->vars[0])?explode("|",$this->vars[0]):'');
        $cod = $this->model->getCodUsuario();
        $this->registerVar('item', $this->model->getItem($cod, "", true));
        $this->genTags("Meu Perfil");
        $this->display('usuario/login/logado');
    }

    /*recupera a senha de um usuario*/
    public function recuperar(){

        //verifica se usuário está online
        if($this->model->IsLoged()){
            $item = $this->model->getItem(usuario_loginModel::CodUsuario());
            $this->model->RecoverPassword($item['email']);
            $arr = $this->model->getMessages();
            $link = "";
        }else{
            //solicita a recuperacao da conta
            if(!empty($_POST)) $this->model->RecoverPassword($_POST['email']);
            $arr = $this->model->getMessages();
            $link = 'usuario/login/recuperar';
            
        }

        //exibe o formulário
        $this->genTags("Recuperar Acesso");
        $this->display($link, $arr);
    }

    public function confirmrec(){
        $dados = array_shift($this->vars);
        if(!$this->model->ConfirmRecoverPassword($dados)){
            $this->setVars($this->model->getMessages());
            $this->display('');
        }
        $this->genTags("Confirmar Recuperação");
        $this->setVars($this->model->getMessages());
        $this->index();
    }
    
    public function confirmar(){
        $this->genTags("Confirmar");
        $dados = array_shift($this->vars);
         if(!$this->model->Confirm($dados)){
            $this->setVars($this->model->getMessages());
            $this->display('');
            return;
        }
        cookie::setVar($this->sess_cont_alerts, $this->model->getMessages());
        Redirect(LINK);
    }

    public function tutorial(){
        if($this->model->IsEnabledTutorial()) {
            $this->model->disableTutorial();
        }
        else $this->model->enableTutorial();
        
        $this->genTags("Tutorial");
        $this->setVars($this->model->getMessages());
        $this->display('');
    }
    
    public function why_confirm(){
        $this->genTags("Porque confirmar minha conta");
        $this->display(LINK."/whyconfirm");
    }
    
    public function confirm_resend(){
        $this->genTags("Confirmar Reenvio");
        $this->model->resend_confirmation();
        cookie::setVar($this->sess_cont_alerts, $this->model->getMessages());
        $this->index();
    }
    
    public function block(){
        $this->genTags("Bloquear ".$this->item['user_name']);
        if($this->cod == "") Redirect ('usuario/login/todos');
        $this->model->blockUser($this->cod);
        $this->redirect('usuario/login/todos');
    }
    
    public function unblock(){
        $this->genTags("Desbloquear ".$this->item['user_name']);
        if($this->cod == "") Redirect ('usuario/login/todos');
        $this->model->unblockUser($this->cod);
        $this->redirect('usuario/login/todos');
    }
    
    public function gadget(){
        if(!isset($this->vars[1])) $this->redirect('usuario/login/logado');
        $page = (isset($this->vars[3]))?$this->vars[3]:1;
        $this->LoadModel('usuario/gadget', 'uga');
        $this->registerVar('gadget', $this->uga->getItem($this->vars[1]));
        $this->registerVar('gadgetData', $this->uga->getGadgetData($this->vars[1], $this->cod, $page, 10));
        $this->display('usuario/login/gadget');
    }
    
    public function mktests(){
        $this->LoadClassFromPlugin('usuario/login/loginSTestes', 'model');
        
        $this->model->rmtests();
        $this->model->addtests();
        $this->setVars($this->model->getMessages());
        
        $this->display('');
    }
    
    public function facebook(){
        
        if(isset($_GET['error'])){
            $this->registerVar('erro','Permissão não concedida');
            $this->redirect(LINK);
        }
        
        $var = explode('?code=', $_SERVER[REQUEST_URI]);
        if(count($var) < 2) $this->redirect (LINK);
        $code = end($var);
        $this->LoadClassFromPlugin('usuario/login/loginFacebook', 'lfb');
        $this->lfb->setUserModel($this->model);
        if(!$this->lfb->valida($code)) {
            //$this->registerVar('alert', 'Erro ao cadastrar pelo facebook');
            $this->setVars($this->lfb->getMessages());
            $this->index();
            return;
        }
        $this->display('');
    }
    
    public function report(){
        $this->display(LINK . "/report");
    }
    
    public function otherreport(){
        $this->display(LINK . "/otherReport");
    }
    
     public function personalreport(){
        $this->display(LINK . "/reportPersonal");
    }
    
     public function actionreport(){
        $this->display(LINK . "/actionreport");
    }
    
    public function widgets(){
        $widget = isset($this->vars[0])?$this->vars[0]:"listUser";
        $page   = isset($this->vars[1])?$this->vars[1]:0;
        $this->registerVar('widget', "usuario/login/widgets/{$widget}Widget");
        $this->registerVar('page', $page);
        $this->display(LINK."/widgets");
    }
    
    public function seelog(){
        $this->display(LINK.'/report_user');
    }
    
    public function seedata(){
        Redirect("config&_user=$this->cod&_click=login");
    }
    
    private function detectRedirect(){
        if(isset($_GET['redirect'])){
            Redirect($_GET['redirect']);
        }
    }
    
}