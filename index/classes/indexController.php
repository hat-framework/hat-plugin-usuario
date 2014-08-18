<?php

class indexController extends \classes\Controller\Controller{
    
        public function  __construct($vars) {
            parent::__construct($vars);
        }

        /*Realiza o login do usuario*/
        public function index(){
            Redirect("usuario/login/");
        }
}
?>
