<?php

class notifyWidget extends \classes\Component\widget{
    
    protected $modelname = "usuario/notify_tipo";
    protected $class    = "";
    protected $title    = "Preferência de Notificações";
   // protected $cachename = "carteira/principais";
    
    protected function draw($itens){
        $this->verify();
        $out = array();
        if(empty($itens) && !$this->drawEmpty) return '';
        $this->openWidget();
        foreach($itens as $item){
            $name = 'Não quero receber notificação de: '.$item['name'];
            $out[$item['cod']] = array('name'=>$name,'type'=>'bit','default'=>0);
            $values[$item['cod']] = 0;
            foreach($this->notify as $not){
                if($item['cod'] == $not['codtipo'] && $not['permission'] == 'n')$values[$item['cod']] = 1;
            }
        }
        $out['button'] = array('button' => 'Salvar Preferências');
        $this->LoadResource("formulario", "form");
        $link = $this->Html->getLink('usuario/notify/insert');
        $this->form->NewForm($out, $values,array(),true,$link);
        $this->closeWidget();
    }
    
    private function verify(){
        $this->LoadModel('usuario/notify', 'not');
        $this->notify = $this->not->selecionar(array('codtipo','permission'),"codusuario = '$this->codUsuario'");
    }
    
    public function setcodUsuario($codUsuario){
        $this->codUsuario = $codUsuario;
    }
}