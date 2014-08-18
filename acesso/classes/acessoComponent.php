<?php
class acessoComponent extends classes\Component\Component{
    public function format_action($action){
        //$action = (in_array($action[0], array('/')))? substr($action, 1, strlen($action)):$action;
        $link = URL."$action";
        return "<a href='$link' target='__BLANK'>$action</a>";
    }
    
    public function format_logname($nome){
        $e = explode('/', $nome);
        return end($e);
    }
    
    public function format_data($data){
        return \classes\Classes\timeResource::Date2StrBr($data);
    }
}
