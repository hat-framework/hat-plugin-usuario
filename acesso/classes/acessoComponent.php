<?php
class acessoComponent extends classes\Component\Component{
    protected $listActions = array('Detalhes' => "show",'Apagar' => "dropitem", /*'Apagar' => "dropitem"*/);
    public function format_action($action){
        //$action = (in_array($action[0], array('/')))? substr($action, 1, strlen($action)):$action;
        $link = URL."$action";
        $size = 32;
        $name = substr($action, 0, $size);
        if(strlen($action) > $size){$name.="...";}
        return "<a href='$link' target='__BLANK'>$name</a>";
    }
    
    public function format_logname($nome){
        $e = explode('/', $nome);
        return end($e);
    }
    
    public function format_data($data){
        return \classes\Classes\timeResource::Date2StrBr($data);
    }
}
