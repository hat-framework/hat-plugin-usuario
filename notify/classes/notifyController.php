<?php 
 use classes\Controller\Controller;
class notifyController extends Controller{
    public $model_name = "usuario/notify";
    
    public function index(){
        $codUsuario = $this->LoadModel('usuario/login','usu')->getCodUsuario();
        $this->registerVar('codUsuario',$codUsuario);
        $this->display('usuario/notify/notify');
    }
    
    public function insert(){
        if(!empty($_POST)){
            $this->LoadModel('usuario/notify','not');
            $status = $this->not->insert($this->LoadModel('usuario/login','usu')->getCodUsuario(),$_POST);
            $this->registerVar('status', $status);
            $this->setVars($this->not->getMessages());
        }
    }
    
    
}