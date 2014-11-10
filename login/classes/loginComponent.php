<?php

use classes\Classes\EventTube;
class loginComponent extends classes\Component\Component{
    
    public function Subscribe($options=''){
        $this->callUserComponent("subscribe", $options);
    }
    
    public function tela_login($options=''){
        $this->callUserComponent("telaLogin", $options);
    }
    
     public function superior_login($options=''){
        $this->callUserComponent("superiorLogin", $options);
    }
    
    public function recuperar($options=''){
        $this->callUserComponent("recuperar", $options);
    }
    
    public function LoggedMenu($options=''){
        return $this->callUserComponent("usermenu", $options,'getLoggedMenu');
    }
    
    public function menu(){
        $this->LoadModel('usuario/login', 'uobj');
        $arr = array(
            'Página Inicial' => array('Página Inicial' => MODULE_DEFAULT),
            /*'Administrar Usuários' => array(
                //'Administrar Usuários'  => 'usuario/login/todos',
                'Usuários'              => array('Usuários'          => 'usuario/login/todos'),
                'Perfis de Usuário'     => array('Perfis de Usuário' => 'usuario/perfil/index'),
            ),*/
            'Minha Conta' => array(
                'Minha Conta'           => 'usuario/login',
                'Criar nova conta'      => array('Criar nova conta'     => 'usuario/login/inserir'),
                'Esqueci Minha senha'   => array('Esqueci Minha senha'  => 'usuario/login/recuperar'),
                'Alterar Dados'         => array('Alterar Dados'        => 'usuario/login/email'),
                'Alterar Senha'         => array('Alterar Senha'        => 'usuario/login/senha'),
                //'Alterar Dados'         => array('Alterar Dados'        => 'usuario/login/dados'),
                'Sair'                  => array('Sair'                 => 'usuario/login/logout')
            )
        );
        if($this->uobj->IsLoged()){
            unset($arr['Minha Conta']['Criar nova conta']);
            unset($arr['Minha Conta']['Esqueci Minha senha']);
        }else{
            unset($arr['Minha Conta']['Email e Senha']);
            unset($arr['Minha Conta']['Sair']);
        }
        if(MODULE_DEFAULT == "usuario") unset($arr['Página Inicial']);
 
        $this->LoadJsPlugin('menu/treeview', 'mt');
        //$this->LoadJsPlugin('menu/menu', 'mt');
        $this->mt->imprime();
        $var = $this->mt->draw($arr);
        $var = "<h3>Minha Conta</h3>$var";
        EventTube::addEvent('menu-lateral', $var);
    }
    
    public function setLoadMenu($region = "menu-superior"){
        
        $menu_array = $this->LoggedMenu();
        if(!\usuario_loginModel::IsWebmaster()){
            unset($menu_array['Área Administrativa']);
        }
        //gera o menu superior
        $this->LoadJsPlugin('menu/dropdown', 'mn');
        $this->mn->imprime();
        $var = $this->mn->draw($menu_array, "navbar-right", 'user-menu');
        
        EventTube::addEvent($region, $var);
        
    }
    
    protected $listActions = array('Veja Mais' => "show", 'Bloquear' => "block", 'Desbloquear' => 'unblock');
    public function drawTitle(&$item) {
        $scomp = new \classes\Component\showComponent($this->dados, $this->gui);
        $this->gui->opendiv('item_header', 'widget col-xs-12');
            $scomp->printHeader($item, $this);
            $this->gadgets($item);
        $this->gui->closediv();
    }
    
    private function gadgets($item){
        if(!isset($item['cod_usuario'])) return;
        $this->LoadModel('usuario/gadget', 'uga');
        $gadgets = $this->uga->selecionar();
        $cod_usuario = $item['cod_usuario'];
        echo "<div id='usertabs'><ul>";
        if($cod_usuario == \usuario_loginModel::CodUsuario()){
            $this->makeGadgetLink("usuario/login/logado", 'Alterar Dados');
        }
        $this->makeGadgetLink("usuario/login/show/$cod_usuario", 'Dados Pessoais');
        foreach($gadgets as $ga){
            $link = "usuario/login/gadget/$cod_usuario/".$ga['cod']."/". GetPlainName($ga['titulo']);
            $this->makeGadgetLink($link, $ga['titulo']);
        }
        echo "<ul></div>";
    }
    
    private function makeGadgetLink($link, $title){
        $url = $this->Html->getLink($link);
        $class = (strstr(CURRENT_URL, $link))?" active":'';
        echo "<li><a class='usertabs$class' href='$url'$class>$title</a></li>";
    }
    
    public function getActionLinks($model, $pkey, $item){
        
        if(!isset($item['status'])||!isset($this->listActions["Desbloquear"])){
            return parent::getActionLinks($model, $pkey, $item);
        }
        $laction = $this->listActions;
        if($item['status'] == 'bloqueado'){
            $this->listActions['Desbloquear'] = "unblock";
            unset($this->listActions['Bloquear']);
        }else{
            $this->listActions['Bloquear'] = "block";
            unset($this->listActions['Desbloquear']);
        }
        $var = parent::getActionLinks($model, $pkey, $item);
        $this->listActions = $laction;
        return $var;
    }
    
    private function callUserComponent($component, $options = "", $method = 'screen'){
        $this->LoadComponent("usuario/login/$component", 'comp');
        return $this->comp->$method($options);
    }
    
    public function format_user_criadoem($data){
        return \classes\Classes\timeResource::Date2StrBr($data);
    }
    public function format_user_uacesso($data){
        return \classes\Classes\timeResource::Date2StrBr($data);
    }

}