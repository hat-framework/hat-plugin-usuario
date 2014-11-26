<?php 
class formController extends \classes\Controller\CController{
    public $model_name = 'usuario/form';
    
    public function index(){
        $this->display(LINK ."/index");
    }
}