<?php

class loginAdmin extends Admin{
    
    public $model_name = "usuario/login";
    public function __construct($vars) {
        parent::__construct($vars);
    }
    
    public function logout(){
        if($this->model->Logout()) {
            Redirect('usuario/login/');
        }
    }
    
}

?>
