<?php 

class tagController extends classes\Controller\CController{
    public $model_name = "usuario/tag";
    
    public function __construct($vars) {
        $this->addToFreeCod(array("test"));
        parent::__construct($vars);
    }
    
    
    public function test(){
        $this->model->join('usuario/tag/usertag', array('cod_tag'), array('cod_tag'), "LEFT");
        $var = $this->model->selecionar(array(),"
            tag_expires_time IS NOT NULL AND
            (NOW() - dt_tag <= tag_expires_time * 86400)
        ");
        $this->model->db->printSentenca();
        print_in_table($var);
    }
}