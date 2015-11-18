<?php
//print_r($_SERVER['HTTP_REFERER']);
use classes\Classes\session;
use classes\Classes\EventTube;
class usuario_loginModel extends \classes\Model\Model{
    protected $tabela = "usuario";
    protected $pkey   = "cod_usuario";
    private   $cookie = "usuario";
    private static $__cookie = "usuario";
    private $cookieuid = "user";
    
     public function setModelName($model){
        parent::setModelName($model);
        $this->restoreCookieUid();
    }
    
    public function atualizaStatus($cod = ""){
        $wh = ($cod != "")? "AND cod_usuario = '$cod'":"";
        $this->db->ExecuteQuery("
        update usuario set status = 'online'  WHERE status != 'bloqueado' AND ((NOW() - user_uacesso) <= 900 $wh);
        update usuario set status = 'inativo' WHERE status != 'bloqueado' AND ((NOW() - user_uacesso) > 900 AND (NOW() - user_uacesso) <= 3600 $wh);
        update usuario set status = 'offline' WHERE status != 'bloqueado' AND ((NOW() - user_uacesso) > 3600 OR isnull(user_uacesso) $wh);
        ");
    }

    public function setLastAccessOfUser(){
        if(!$this->IsLoged()) {return;}
        parent::editar($this->getCodUsuario(), array('user_uacesso' => "FUNC_NOW()"));
    }

    public function getDados() {
        $var = parent::getDados();
        if($this->IsLoged()){
            $this->LoadModel('usuario/perfil', 'perfil');
            $var['cod_perfil']['default'] = $this->perfil->getDefaultPerfil();
        }
        if(!$this->UserIsAdmin()){
            $count = $this->getCount("permissao = 'Webmaster'");
            if($count != 0) {
                $var['permissao']['value']    = "Visitante";
                //$var['permissao']['value']    = "Admin";
                $var['permissao']['especial'] = 'hidden';
                $var['permissao']['type']     = 'varchar';
            }
            //else  $var['permissao']['default']  = "Webmaster";

        }elseif(!$this->UserIsWebmaster()){
            unset($var['permissao']['options']['Webmaster']);
        }

        if($this->UserIsWebmaster()){
            unset($var['permissao']['private']);
        }      
        return $var;
    }

    //verifica se o usuario est� logado
    public function IsLoged(){
        return (session::exists($this->cookie));
    }//c
    
    
    public function getWebmastersMail(){
        $arr = $this->selecionar(array('email'), "cod_perfil='".Webmaster."'");
        $out = array();
        foreach($arr as $a){
            $out[] = $a['email'];
        }
        return $out;
    }
    
     //desloga o usuario
    public function Logout(){
        
        $cod = $this->getCodUsuario();
        $total = $this->getCount("status = 'bloqueado' AND cod_usuario = '$cod'");
        $v['status'] = ($total != 0)?'bloqueado':'offline';
        
        //ao deslogar apaga todos os cookies
        session::destroyAll();
        \classes\Classes\cookie::destroy($this->cookieuid);
                
        return parent::editar($cod, $v);
    }//c
    
    public function IsEnabledTutorial(){
        $var = session::getVar($this->cookie);
        //print_r($var);//die();
        if($var != "") return($var['usuario_login_tutorial'] == 'ativo');
        else           return false;
    }
    
    public function disableTutorial(){
        $var = session::getVar($this->cookie);
        if($var == "") {
            $this->setErrorMessage('Usu�rio n�o est� logado');
            return false;
        }
        $var['usuario_login_tutorial'] = "inativo";
        session::setVar($this->cookie, $var);
        
        $cod = $this->getCodUsuario();
        if($cod == 0) return;
        if(!parent::editar($cod, array('usuario_login_tutorial' => "inativo"))) {
            die($this->getErrorMessage());
            return false;
         }
        
        $this->setSuccessMessage('Tutorial Desativado com sucesso');
        return true;
    }
    
    public function enableTutorial(){
        
        $var = session::getVar($this->cookie);
        if($var == "") {
            $this->setErrorMessage('Usu�rio n�o est� logado');
            return false;
        }
        $var['usuario_login_tutorial'] = 'ativo';
        session::setVar($this->cookie, $var);
        
        $cod = $this->getCodUsuario();
        if($cod == 0) return;
        if(!parent::editar($cod, array('usuario_login_tutorial' => 'ativo'))) {
            die($this->getErrorMessage());
            return false;
        }
        $this->setSuccessMessage('Tutorial Ativado com sucesso');
        return true;
    }
    
    public function userIsConfirmed($addevent = true){
        $var = session::getVar($this->cookie);
        if($var == "") return false;
        if($var['confirmed'] == '0'){
            if($addevent){
                $this->LoadResource('html', 'html');
                $url = $this->html->getLink("usuario/login/why_confirm");
                EventTube::addEvent('page-top', "<span id='erro'>
                    Caro Usu�rio o seu email ainda n�o foi confirmado 
                    <a href='$url'>saiba mais</a>
                 </span>");
            }
            return false;
        }
        return true;
    }

    public function resend_confirmation(){
        $id = $this->getCodUsuario();
        $var = $this->getItem($id);
        if($var['confirmkey'] == ""){
            $v['confirmkey'] = genKey(16);
            if(!parent::editar($id, $v)){
                $this->setErrorMessage('
                    N�o foi poss�vel gerar uma nova chave de confirma��o para sua conta.
                ');
                return false;
            }
            $var['confirmkey'] = $v['confirmkey'];
        }
        $this->LoadModel('usuario/login/loginDialogs', 'udi');
        $bool = $this->udi->resend_confirmation($var);
        $this->setMessages($this->udi->getMessages());
        return $bool;
    }

    public function getCodUsuario($user = array()){
        if(empty($user)){
            $var = session::getVar($this->cookie);
            if($var != "") return(!isset($var['cod_usuario']))?0:$var['cod_usuario'];
            else return 0;
        }
        
        if(is_array($user)){
            if(isset($user['cod_usuario'])) return $user['cod_usuario'];
            else                            return 0;
        }
        
        $row = (is_numeric($user))?'cod_usuario':'email';
        $it = $this->getItem($user, $row, false, array('cod_usuario'));
        if(empty($it)) return 0;        
        return $it['cod_usuario'];
        
    }//c
    
    public function getUserNick($user = array(), $show_in_array = false){
        if(!is_array($user) || empty($user)){
            if(!is_numeric($user)){
                $var = session::getVar($this->cookie);
                if(empty($var)){
                    if($show_in_array) return array();
                    else return '';
                }
            }
            else $var = $this->getItem($user);
            
            $user = $var;
            unset($user['user_cargo']);
        }
        
        if(!array_key_exists('user_name', $user)){
            if(!$show_in_array) return $user['email'];
            return $user;
        }
        if(!$show_in_array){
            $cargo = (isset($user['user_cargo']) && $user['user_cargo'] != "") ?" (".$user['user_cargo'].")":"";
            return $user['user_name']. $cargo;
        }
        
        $user['cod_usuario']= self::CodUsuario();
        $user['user_cargo'] = (isset($user['user_cargo']) && $user['user_cargo'] != "") ?$user['user_cargo']:"";
        $user['img']        = (isset($user['img']))      ? "<img src=\'".$user['img']."\' class=\'profile_picture\' />":"";
        $user['user_name']  = (isset($user['user_name']))? $user['user_name']:$user['email'];
        return $user;
        
    }//c
    
    public function getUserData(){
        return session::getVar($this->cookie);
        
    }//c

    /*faz o login do usuario*/
    public function Login($login, $senha, $enc = true,$login_first = false){
        //procura no banco de dados um usu�rio e senha iguais ao digitado pelo usu�rio
        $login = str_replace(array("'", '"'), "", $login);
        $senha = str_replace(array("'", '"'), "", $senha);
        $w     = (!$enc)?"`senha` = '$senha'":"`senha` = PASSWORD('$senha')";
        $where = "`email` = '$login' AND $w";
        $value = $this->db->Read($this->tabela, NULL, $where);
        
        //se login n�o existe ou senha est� incorreta
        if(empty ($value)){
            $this->setErrorMessage("Usu�rio ou senha incorretos");
            return false;
        }
        //verifica se o usu�rio est� bloqueado
        $refer = (isset($_GET['refer']))?$_GET['refer']:session::getVar('refer');
        $user  = array_shift($value);
        if($refer == "") {$refer = URL;}
        $this->makeLogin($user, $refer);
        
        //redireciona, caso necess�rio
        $this->Redirect(true,$login_first);
        return true;
    }
    
    private function makeLogin($user, $refer = ''){
        if($user['status'] == 'bloqueado'){
            throw new AcessDeniedException("O seu acesso foi bloquado por um administrador do sistema!");
            return false;
        }
        if(empty($user)){return false;}
        
        //seta os dados a serem salvos na sess�o
        $var['cod_usuario']            = $user['cod_usuario'];
        $var['email']                  = $user['email'];
        $var['usuario_login_tutorial'] = $user['usuario_login_tutorial'];
        $var['user_name']              = $user['user_name'];
        $var['user_cargo']             = $user['user_cargo'];
        $var['cod_perfil']             = $user['cod_perfil'];
        $var['confirmed']              = @$user['confirmed'];
        
        session::destroyAll();
        session::setVar($this->cookie, $var);
        if(is_numeric($var['cod_usuario']) && $var['cod_usuario'] > 0){
            \classes\Classes\cookie::setVar($this->cookieuid, $var['cod_usuario']);
        }
        if($refer != ""){session::setVar('refer', $refer);}
        
        
        //seta os dados a serem editados
        $v['status']     = 'online';
        if($user['confirmkey'] != ""){
            $recsenha =  \classes\Classes\crypt::decrypt_camp($user['confirmkey']);
            if($recsenha != $user['confirmkey']) {$v['confirmkey'] = "FUNC_NULL";}
        }
        parent::editar($user['cod_usuario'], $v);
    }
    
    //insere um novo usuario
    public function inserir($array){
        $this->user_permission($array);
        $senha = $this->prepareInsertion($array);
        $refer = $this->getReferrer($array);
        if(!parent::inserir($array)) {return false;}
                
        $cod_usuario          = $this->getLastId();
        $array['referrer']    = $refer;
        $array['senha']       = $senha;
        $array['cod_usuario'] = $cod_usuario;
        $bool                 = $this->onSubscribe($cod_usuario, $array);
        $this->autoLogin($array);
        return $bool;

    }//c 
    
            //dados extras(do sistema)
            private function prepareInsertion(&$array){
                if(!isset($array['senha'])){$array['senha'] = genKey(12);}
                $senha               = $array['senha'];
                $array['cod_perfil'] = isset($array['cod_perfil'])?$array['cod_perfil']:'4';
                $array['senha']      = "FUNC_PASSWORD('{$array['senha']}')";
                if(isset($array['referrer'])){
                    $array['indicado'] = $array['referrer'];
                }
                if($array['cod_perfil'] != Webmaster) {$array['confirmkey'] = genKey(16);}
                return $senha;
            }
            
            private function getReferrer(&$array){
                if(isset($array['referrer']) && trim($array['referrer']) != ""){
                    $ref = $array['referrer'];
                }
                else{$ref = $this->LoadModel('usuario/referencia', 'ref')->getCookie();}
                if(trim($ref) != ""){$array['indicado'] = $ref;}
                return $ref;
            }

            private function onSubscribe($cod_usuario, $array){
                $folder = dirname(__FILE__)."/onSubscribe";
                $files  = $this->LoadResource('files/dir', 'dobj')->getArquivos($folder);
                $bool   = true;
                if(empty($files)){return $bool;}
                foreach($files as $file){
                    $class = str_replace('.php', '', $file);
                    $file  = "$folder/$file";
                    getTrueDir($file);
                    if(!file_exists($file)){continue;}
                    require_once $file;
                    if(!class_exists($class, false)){continue;}
                    $obj = new $class();
                    if(!method_exists($obj, 'execute')){continue;}
                    if(false === $obj->execute($cod_usuario, $array)){
                        $erro = $obj->getErrorMessage();
                        if($erro === ""){continue;}
                        $this->appendErrorMessage($erro);
                        $bool = false;
                    }
                }
                return $bool;
            }
            
            private function autoLogin($user){
                if($this->IsLoged()){return;}
                if(!defined ('USUARIO_LOGIN_AUTOLOGIN_CADASTRO') || USUARIO_LOGIN_AUTOLOGIN_CADASTRO !== true){return;}
                return $this->Login($user['email'], $user['senha'],true,true);
            }
    
    public function editarDados($id, $dados){
        $var = $this->selecionar(array('cod_usuario'), "`cod_usuario` = '$id' AND `senha` = PASSWORD('".$dados['senha']."')");
        if(empty($var)){
            $this->setErrorMessage("Caro usu�rio, sua senha est� incorreta!");
            return false;
        }
        return $this->editar($id, $dados);
    }//c
    
    public function getAutenticatedUser($cod_usuario, $senha){
        $user = $this->selecionar(array(), "cod_usuario = '$cod_usuario' AND senha = PASSWORD('$senha')");
        if(empty($user)) return $user;
        return array_shift($user);
    }
    
    private function user_permission(&$dados){
        
        //se n�o existe nenhum webmasters, seta a permiss�o de webmaster
        $count = $this->selecionar(array('cod_usuario'), "`permissao` = 'Webmaster'", '1');
        if(empty($count)){
            $dados['permissao'] = 'Webmaster';
            return;
        }
        if(!isset($dados['permissao'])) return;
        
        //se usu�rio n�o � admin a permiss�o s� pode ser de visitante
        if(!$this->UserIsAdmin()) {$dados['permissao'] = 'Visitante';}
        //if(!$this->UserIsAdmin()) $dados['permissao'] = 'Admin';
        
        //se usu�rio � admin mas n�o � webmaster s� pode criar um novo admin ou um novo visitante
        elseif(!$this->UserIsWebmaster() && ($dados['permissao'] == "Webmaster")){
            //$dados['permissao'] = "Visitante";
            $dados['permissao'] = "Admin";
        }
    }
    
    public function autentica($senha){
        if(is_array($senha)){$senha = $senha['senha_confirmacao'];}
        $senha       = $this->antinjection($senha);
        $cod_usuario = usuario_loginModel::CodUsuario();
        $user        = $this->selecionar(array("cod_usuario"), "cod_usuario='$cod_usuario' AND senha = PASSWORD('$senha')");
        return(!empty($user));
    }

    public function editar($id, $dados, $camp = ""){
        
        $this->getMessages(true);
        
        //se usu�rio n�o tem permiss�o para alterar outros usu�rios
        if(false === $this->UserCanAlter($id)){return false;}
        if(!isset($dados['senha_confirmacao'])){
            //se usu�rio que est� alterando os dados � o dono da conta
            $cod_user = self::CodUsuario();
            if($id === $cod_user){
                return $this->setErrorMessage("Para alterar seus dados a senha de confirma��o deve ser enviada!");
            }
        }
        
        $camp = ($camp == "")?$this->pkey:$camp;
        $where = (LINK ."/".CURRENT_ACTION != "usuario/login/edit")?"AND senha = PASSWORD('".$dados['senha_confirmacao']."')":'';
        $user = $this->selecionar(array(), "$camp = '$id' $where");
        if(empty($user)){
            return $this->setErrorMessage("Usu�rio ou senha incorretos");
        }
        
        if(isset($dados['senha_nova']) && $dados['senha_nova'] != $dados['confirmar_senha']){
            return $this->setErrorMessage("A senha nova deve ser id�ntica � confirma��o de senha");
        }
        
        $this->user_permission($dados);
        if(isset($dados['senha'])) {unset($dados['senha']);}
        if(array_key_exists('senha_nova', $dados)) $dados['senha'] = 'FUNC_PASSWORD("'.$dados['senha_nova'].'")';
        if($this->getCodUsuario() == $id && isset($dados['cod_perfil'])){unset($dados['cod_perfil']);}
        
        $old = $this->getItem($id);
        
        //seta as variaveis de controle
        $dados['update_permission'] = 's';
        if(isset($dados['email'])&& isset($user['email']) && $user['email'] != $dados['email']){$dados['confirmed'] = '0';}
        
        if(!parent::editar($id, $dados, $camp)) return false;
        $new_user = $this->getItem($id, "", true);
        
        $this->LoadModel('usuario/login/loginDialogs', 'udi')->editar($new_user, $old);
        return $this->setSuccessMessage('Dados alterados com sucesso!');
        //$this->setMessages($this->udi->getMessages()); 
        //return $bool;
    }//c
    
    public function apagar($valor, $chave = "") {
        $user = $this->getItem($valor, $chave);
        
        //impede que o usu�rio se exclua
        if($this->getCodUsuario($user) == $this->getCodUsuario()){
            $this->setErrorMessage("Voc� n�o pode excluir sua pr�pria conta!");
            return false;
        }
        
        //se usu�rio a ser excluido � webmaster
        elseif($user['cod_perfil'] == Webmaster){
            
            //se o usu�rio que est� excluindo um webmaster n�o for webmaster, ent�o bloqueia
            if($this->getCodPerfil() != Webmaster){
                $this->setErrorMessage("Voc� n�o tem permiss�o de excluir um Administrador do Sistema!");
                return false;
            }

            //se quem est� excluindo um webmaster � um webmaster
            else{
                
                //se s� existe um webmaster, bloqueia
                $total = $this->getCount("cod_perfil = '".Webmaster."'");
                if($total == 1){
                    $this->setErrorMessage("Para excluir uma conta de Webmaster � necess�rio 
                        que exista pelo menos outra conta com o mesmo privil�gio");
                    return false;
                }
            }
        }
        
        //apaga
        if(!parent::apagar($valor, $chave)) return false;
        $name = $user['user_name'] . " (".$user['user_cargo'].")";
        $this->setSuccessMessage("Usu�rio $name removido do sistema com sucesso!");
        return true;
    }

    public function Confirm($dados){

        $dados   = explode("-", $dados);
        $usuario = array_shift($dados);
        $chave   = array_shift($dados);
        $usuario = $this->antinjection($usuario, false);
        
        //procura o usuario no banco de dados
        $value = $this->db->Read($this->tabela, NULL, "`cod_usuario` = '$usuario'", 1); 
        if(empty($value)){
            echo $this->db->getSentenca();
            $this->setErrorMessage("O usu�rio que voc� procura n�o existe");
            return false;
        }
        
        //verifica se � necess�rio atualizar a session do usu�rio
        $user            = array_shift($value);
        $online          = $this->IsLoged();
        $codUserOnline   = $this->getCodUsuario();
        $codUserConfirm  = $this->getCodUsuario($user);
        $atualizaSession = ($online === true && $codUserOnline == $codUserConfirm && session::exists($this->cookie))?true:false;
        
        //recupera o nome do usu�rio que est� sendo recuperada a session
        $name            = $this->getUserNick($user);
        
        //se a chave de confirmacao esta vazia
        if($user['confirmkey'] == ""){
            if($atualizaSession){
                $co = session::getVar($this->cookie);
                $co['confirmed'] = '1';
                session::setVar($this->cookie, $co);
            }
            $this->setErrorMessage("O usu�rio $name foi confirmado no site anteriormente!");
            return false;
        }
        
        //se a chave de confirmacao esta errada
        if($user['confirmkey'] != $chave){
            $this->setErrorMessage("A chave de confirma��o do usu�rio $name est� incorreta!");
            return false;
        }
        
        //edita o usu�rio no banco de dados
        $Var['confirmkey'] = "FUNC_NULL";
        $Var['confirmed'] = "1";
        if(!parent::editar($user['cod_usuario'], $Var)){
            $this->setErrorMessage("N�o foi poss�vel confirmar o usu�rio $name");
            return false;
        }
        
        //atualiza a session se necess�rio
        if($atualizaSession){
            $co = session::getVar($this->cookie);
            $co['confirmed'] = '1';
            session::setVar($this->cookie, $co);
        }
        
        //atualiza as mensagens
        $this->setSuccessMessage("Usu�rio $name confirmado com sucesso!");
        session::setVar('controller_alerts', $this->getMessages());
        
        //se usu�rio n�o est� online e n�o existe outra sess�o de usu�rio online, faz o login do usu�rio
        if(!$online) $this->Login(@$user['email'], @$user['senha'], false);
        return true;

    }//c

    public function RecoverPassword($email){

        //procura o usuario no banco de dados
        $value = $this->db->Read($this->tabela, NULL, "`email` = '$email'");
        if(empty($value)){
            $this->setErrorMessage("Este email n�o est� registrado em nossa base de dados");
            return false;
        }
        $user = array_shift($value);
        
        /*
        Edita os dados no banco
        Se confirmkey estiver encriptada, ent�o ela cont�m a nova senha do usu�rio. 
        Do contr�rio gera uma nova chave de confirma��o
         */
        $confkey = ($user['confirmkey'] != "")?$user['confirmkey']:genKey(16);
        $Var['confirmkey'] = $confkey;
        if(!parent::editar($user['cod_usuario'], $Var)) return false;

        if($confkey == $user['confirmkey'] && strlen($confkey) > 16) $confkey = "";
        
        //envia um alerta por email ara o usu�rio
        $this->LoadModel('usuario/login/loginDialogs', 'udi');
        $bool = $this->udi->RecoverPassword($user, $confkey);
        $this->setMessages($this->udi->getMessages());
        return $bool;
    }

    //confirma a recupera��o de senha
    public function ConfirmRecoverPassword($dados){

        $dados   = explode("-", $dados);
        $usuario = array_shift($dados);
        $chave   = array_shift($dados);

        //verifica se existe algum usuario com esta chave de recupera��o
        $value = $this->db->Read($this->tabela,NULL, "`cod_usuario` = '$usuario' AND`confirmkey` = '$chave'");
        $user = array_shift($value);
        if(empty ($user)){
            $value = $this->db->Read($this->tabela, NULL, "`cod_usuario` = '$usuario'");
            
            //verifica se usu�rio existe
            $user = array_shift($value);
            if(empty ($user)) {
                $this->setErrorMessage("Usu�rio n�o existe");
                return false;
            }
            
            //verifica se a chave de confirma��o existe
            elseif(array_key_exists("confirmkey", $user) && $user['confirmkey'] != "") {
                
                //verifica se a chave de confirma��o cont�m a nova senha do usu�rio
                if($user['confirmkey'] ==  \classes\Classes\crypt::decrypt_camp($user['confirmkey'])){
                    $this->setErrorMessage("Chave de confirma��o inv�lida.");
                    return false;
                }
            }
            
            else{
                $this->setSuccessMessage('Usu�rio j� confirmado');
                return true;
            }
            
        }

        //carrega a senha gerada
        if(isset($user['confirmkey'])){
            $dec =  \classes\Classes\crypt::decrypt_camp($user['confirmkey']);
            $senha = ($dec != $user['confirmkey'])? $dec:genKey(12);
        }else $senha = genKey(12);
        $Var['senha']      = "FUNC_PASSWORD('".$senha."')";
        $Var['confirmkey'] =  \classes\Classes\crypt::encrypt_camp($senha);
        if(!$this->db->Update($this->tabela, $Var, "`cod_usuario` = '".$user['cod_usuario'] ."'")){
            $this->setErrorMessage("N�o foi poss�vel gerar sua nova senha");
            return false;
        }

        $this->LoadModel('usuario/login/loginDialogs', 'udi');
        $bool = $this->udi->ConfirmRecoverPassword($user, $senha);
        $this->setMessages($this->udi->getMessages());
        return $bool;
    }
    
    public function resend($login){
    	
    	//procura o usuario no banco de dados
        $value = $this->db->Read($this->tabela, NULL, "`cod_usuario` LIKE '$login' || `email` LIKE '$login'"); 
        if(empty($value)){
            $this->setErrorMessage("Usu�rio inexistente");
            return false;
        }
        $value = array_shift($value);
        
        if($value['confirmkey'] == NULL || $value['key'] == ""){
            $this->setSuccessMessage("Email j� confirmado");
            return true;
        }
        
        $Var['confirmkey'] = $value['key'];

        //Se nao conseguiu atualizar tabela
        if(!$this->db->Update($this->tabela, $Var, "`cod_usuario` = '".$value['cod_usuario'] ."'")){
            $this->setErrorMessage("N�o foi poss�vel atualizar o banco de dados");
            return false;
        }

        //prepara o email
        $this->LoadResource("html", 'html');
        $url     = $this->html->getLink("usuario/login/confirmar/".$value['cod_usuario']."/".$Var['confirmkey']);
        $msg     = "<p><a href='$url'>clique aqui</a> Para completar sua inscri��o</p>";
        $assunto = "Reenviar Confirma��o";
        $corpo   = $msg;
        
        $this->LoadResource("email", "email");
        $this->email->SendMail($assunto, $corpo, $value['email']);

        $this->setSuccessMessage("Um novo email de confirma��o foi enviado para voc�.");
        return true;
    }
    
    public function needWebmasterLogin($url = ''){
        
        //se usu�rio n�o est� logado ou n�o � admin
        if(!$this->IsLoged() || !$this->UserIsWebmaster()){
            $this->Logout();
            $this->needLogin($url);
        }
        
        //se usu�rio � admin
        else $this->Redirect();
        return true;
    }
    
    public function needAdminLogin($url = ""){
        
        //se usu�rio n�o est� logado ou n�o � admin
        if(!$this->IsLoged() || !$this->UserIsAdmin()){
            $this->Logout();
            $this->needLogin($url);
        }
        
        //se usu�rio � admin
        else $this->Redirect();
        return true;
    }
    
    public function needLogin($url = ""){
        if($url == "" && CURRENT_MODULE == "usuario" && CURRENT_CONTROLLER == "login"){$url = URL;}
        $url = ($url == "")?CURRENT_URL:$url;
        if(!$this->IsLoged()){
            $this->LoadResource('html', 'html');
            $url = base64_encode($this->html->getLink($url, false, true));
            if(!session::exists('refer'))session::setVar('refer', $url);
            Redirect('usuario/login', 0, "refer=$url");
        }
        else {$this->Redirect();}
        return true;
    }
    
    public function Redirect($first_login = false,$login_first = false){
        $class = 'usuario/login/helpers/loginRedirection';
        return $this->LoadClassFromPlugin($class, 'ulr')->TryRedirection($first_login,$login_first);
    }
    
    public function getUserId(){
        $var = session::getVar($this->cookie);
        if($var == "") return "";
        
        $cod_usuario = $var['cod_usuario'];
        $item = ($this->getItem($cod_usuario));
        if(!array_key_exists("cod_usuario", $item) || $cod_usuario != $item['cod_usuario']){
            return "";
        }
        return $cod_usuario;
    }
    
    public function getUserMail($cod_usuario){
        return $this->getField($cod_usuario, 'email');
    }
    
    public function Is($perfName){
        $cod_usuario = self::CodUsuario();
        $this->LoadModel('usuario/perfil', 'perf');
        $this->db->Join($this->tabela, $this->perf->getTable(), array('cod_perfil'), array('usuario_perfil_cod'), "LEFT");
        $this->getCount("$this->tabela.cod_usuario = '$cod_usuario' AND usuario_perfil_nome = '$perfName'");
        $total = $this->getCount("$this->tabela.cod_usuario = '$cod_usuario' AND usuario_perfil_nome = '$perfName'");
        return ($total > 0);
    }

    public static function IsWebmaster(){
        //usu�rios deslogados n�o s�o webmaster. Isto evita lan�amento de exce��o quando db n�o instalado
        if(!session::exists(self::$__cookie)) {return false;}
        $var = session::getVar(self::$__cookie);
        if(!isset($var['cod_perfil'])){return false;}
        return ($var['cod_perfil'] == Webmaster);
    }


    public function UserIsWebmaster($cod_usuario = ''){
        return ($this->getCodPerfil($cod_usuario) == Webmaster);
    }
    
    public function UserIsAdmin($codusuario = "", $perm = ""){
        $var = session::getVar($this->cookie);
        $cod_usuario = ($codusuario === "" && isset($var['cod_usuario']))?$var['cod_usuario']:$codusuario;
        if($cod_usuario == "") {return false;}
        $item = ($this->getItem($cod_usuario, '', false, array('cod_usuario', 'permissao')));
        if(empty($item)) return false;
        
        if(!array_key_exists("cod_usuario", $item) || $cod_usuario != $item['cod_usuario'])return false;
        if(!isset($item['__permissao'] )) {return false;}
        if($perm == "") {
            return ($item['__permissao'] == "Webmaster" || $item['__permissao'] == "Admin" || usuario_loginModel::CodPerfil() === Admin);
        }
        return ($item['__permissao'] == "$perm");
    }
    
    public function LoadPerfil($cod){
        if($cod == "") return array();
        $item = $this->getItem($cod);
        //echo $this->db->getSentenca();
        if(empty ($item)) return $item;
        unset($item['newslatter']);
        unset($item['senha']);
        unset($item['permissao']);
        unset($item['confirmkey']);
        return $item;
    }
    
    public function getCodPerfil($cod_usuario = ""){
        static $cache = array();
        if(!$this->IsLoged()){return "";}
        $cod_user = ($cod_usuario == "") ?$this->getCodUsuario():$cod_usuario;
        if(array_key_exists($cod_user, $cache)) {
            return (isset($_GET['_perfil']) && usuario_loginModel::CodPerfil() == Webmaster)?
                $_GET['_perfil']:$cache[$cod_user];
        }
        
        $user = $this->selecionar(array('cod_perfil'), "cod_usuario='$cod_user'");
        $us = (!empty($user))?array_shift($user):array('cod_perfil' => '');
        $cache[$cod_user] = $us['cod_perfil'];
        //die(usuario_loginModel::CodPerfil() . " - ".Webmaster);
        return (isset($_GET['_perfil']) && usuario_loginModel::CodPerfil() == Webmaster)?
                $_GET['_perfil']:$us['cod_perfil'];
    }
    
    public function getUsuariosPorPerfil($cod_perfil, $campos = array()){
        if(!is_array($cod_perfil)){$cod_perfil = array($cod_perfil);}
        $in = implode("','", $cod_perfil);
        return $this->selecionar($campos, "cod_perfil IN('$in')");
    }
    
    public function getTotalUsuariosPorPerfil($cod_perfil){
        return $this->getCount("cod_perfil = '$cod_perfil'");
    }
    
    public static function isLogged(){
        $var = session::getVar(self::$__cookie);
        return isset($var['cod_usuario']);
    }
    
    public static function CodUsuario(){
        $var = session::getVar(self::$__cookie);
        return isset($var['cod_usuario'])?$var['cod_usuario']:0;
    }
    
    public static function CodPerfil(){
        $var = session::getVar(self::$__cookie);
        if(!isset($var['cod_perfil'])){return 0;}
        return (isset($_GET['_perfil']) && $var['cod_perfil'] == Webmaster)?$_GET['_perfil']:$var['cod_perfil'];
    }
    
    public function changeUserPerfil($cod_usuario, $cod_perfil){
        if($this->getCodPerfil($cod_usuario) === Webmaster){return true;}
        if (false === parent::editar($cod_usuario, array("cod_perfil"=>$cod_perfil, 'update_permission' => 's'))){return false;}
        return true;
    }
    
    public function blockUser($cod_usuario){
        if($this->getCodPerfil($cod_usuario) == Webmaster){
            $this->setErrorMessage("N�o � poss�vel bloquear um usu�rio com permiss�o de Webmaster");
            return false;
        }
        $bool = parent::editar($cod_usuario, array('status' => 'bloqueado', 'update_permission' => 's'));
        if(!$bool) $this->setErrorMessage("N�o foi poss�vel bloquer o acesso a este usu�rio");
        else       $this->setSuccessMessage ('Usu�rio bloqueado com sucesso!');
        return $bool;
    }
    
    public function unblockUser($cod_usuario){
        $bool = parent::editar($cod_usuario, array('status' => 'offline', 'update_permission' => 's'));
        if(!$bool) $this->setErrorMessage("N�o foi poss�vel desbloquer o acesso deste usu�rio");
        else       $this->setSuccessMessage ('Usu�rio desbloqueado com sucesso!');
        return $bool;
    }
    
    public function isBloqued(){
        if($this->getCodPerfil() == Webmaster) return false ;
        $cod_usuario = $this->getCodUsuario();
        $total = $this->selecionar(array('cod_usuario'),"cod_usuario = '$cod_usuario' AND status = 'bloqueado'");
        return (!empty($total));
    }
    
    public function permissoes_alteradas($cod_perfil){
        return parent::editar($cod_perfil, array('update_permission' => 's'), 'cod_perfil');
    }
    
    public function has_permission_alterada($cod_usuario = ""){
        if($cod_usuario == "") $cod_usuario = $this->getCodUsuario ();
        if($cod_usuario == 0) return false;
        $total = $this->selecionar(array('cod_usuario'), "cod_usuario = '$cod_usuario' AND update_permission = 's'", 1);
        return (!empty($total));
    }
    
    public function isUpdatedPermissions(){
        $cod_usuario = $this->getCodUsuario ();
        if($cod_usuario == 0) return false;
        parent::editar($cod_usuario, array('update_permission' => 'n'));
    }
    
    public function UserCanAlter($cod_usuario){
        
        //se usu�rio est� alterando a pr�pria conta.
        $cod_autor = $this->getCodUsuario();
        if($cod_autor == $cod_usuario) {return true;}
        
        //se usu�rio � webmaster
        if($this->IsWebmaster() && !isset($_GET['_perfil'])) {return true;}
        
        //Somente um webmaster pode editar o pr�prio perfil
        $cod_perfil = $this->getCodPerfil($cod_usuario);
        if($cod_perfil == Webmaster){
            $this->setErrorMessage('Voc� n�o tem permiss�o para modificar um usu�rio com perfil de Webmaster!');
            return false;
        }
        
        //webmaster pode alterar os outros perfis
        $cod_perfil2  = $this->getCodPerfil($cod_autor);
        if($cod_perfil2 == Webmaster) return true;
        
        //somente um administrador pode editar um perfil de administrador
        if($cod_perfil == Admin && $cod_perfil2 != $cod_perfil) {
            $this->setErrorMessage('Voc� n�o tem permiss�o para modificar um usu�rio com perfil de Administrador!');
            return false;
        }
        
        //verifica se usu�rio tem permiss�o de alterar dados de outros usu�rios
        return $this->LoadModel('usuario/perfil', 'perf')->hasPermissionByName('usuario_GU');
    }
    
    public function paginate($page, $link = "", $cod_item = "", $campo = "", $qtd = 20, $campos = array(), $adwhere = "", $order = "") {
        if(CURRENT_CONTROLLER == 'login' && CURRENT_ACTION == 'todos'){
            //$this->LoadModel('usuario/perfil', 'perfil');
            //$tb = $this->perfil->getTable();
            //$wh      = "$tb.display_list = 's'";
            //$adwhere = ($adwhere == "")?$wh:"$wh AND ($adwhere)";
        }
        $order = ($order == "")?"status ASC, user_name ASC":$order;
        return parent::paginate($page, $link, $cod_item, $campo, $qtd, $campos, $adwhere, $order);
    }
    
    public function getCountPerfil(){
        $this->LoadModel('usuario/perfil', 'perfil');
        $tb = $this->perfil->getTable();
        $this->db->Join($this->tabela, $tb, array('cod_perfil'), array('usuario_perfil_cod'), "LEFT");
        return $this->selecionar(array("usuario_perfil_nome", "COUNT(cod_perfil) as total"), "1 GROUP BY cod_perfil", "", "", "total DESC");
    }
    
    public function getOrigem(){
        return $this->selecionar(array('tipo_cadastro', 'count(*) as total'), "1 GROUP BY tipo_cadastro", "", "", "total DESC");
    }
    
    public function getUltimosCadastros(){
        return $this->selecionar(array('DATE(user_criadoem) as data', 'count(*) as total'), "1 GROUP BY data", "", "", "data ASC");
    }
    
    public function validate() {
        if(!parent::validate()) return false;
     //   die("done");
        return true;
    }
    
    private function restoreCookieUid(){
        if(self::CodUsuario() !== 0){return;}
        $cod_usuario = $this->antinjection(\classes\Classes\cookie::getVar($this->cookieuid));
        if($cod_usuario === ""){return;}
        $res  = $this->selecionar(array(), "cod_usuario='$cod_usuario'");
        if(empty($res)){return;}
        $user = array_shift($res);
        $this->makeLogin($user);
    }
    
    public static function user_action_log($loguser = 'acesso', $msg = '', $loggroup = array()){
        if(!isset($_SERVER['REQUEST_URI'])){
            \classes\Utils\Log::save("Errors", "Vari�vel request uri inexistente");
            return;
        }
        $cod_perfil  = usuario_loginModel::CodPerfil();
        //if($cod_perfil == Webmaster && $msg == "") return;
        
        $cod_usuario = usuario_loginModel::CodUsuario();
        $action      = isset($_SERVER['REQUEST_URI'])    ?$_SERVER['REQUEST_URI']    :"";
        //$navegador   = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"";
        $refer       = isset($_SERVER['HTTP_REFERER'])   ?$_SERVER['HTTP_REFERER']   :"";
        $ip          = isset($_SERVER["REMOTE_ADDR"])    ?$_SERVER["REMOTE_ADDR"]    :"";
        if($loguser == "acesso" && (is_array($loggroup) || $loggroup == "")){
            $loggroup = array('plugins');
        }
        if(in_array($loguser, array('notificacao/notifycount/load'))){return;}
        $obj = new \classes\Classes\Object();
        $obj->LoadModel('usuario/acesso', 'acc')->saveLog($loguser,$cod_usuario,$cod_perfil,$action,$ip,$refer,$msg, $loggroup);
        /*if(!\classes\Utils\Log::exists($logname))
        \classes\Utils\Log::save($logname, ", C�digo do usu�rio, Perfil de usu�rio, Link, IP, Link Anterior, Mensagem;");
        else \classes\Utils\Log::save($logname, ",'$cod_usuario','$cod_perfil','$action','$ip','$refer', '$msg';");*/
    }
    
    public function getLastAccess($where){
        $res = $this->selecionar(array(),"$where");
        $count = count($res);
        return array('Descri��o'=>'Cadastro','Quantidade'=>$count);
    }
    
    public function getDailyAcesso(){
        return $this->getDailyAccess('user_uacesso');
    }
    
     public function getDailyCadastro(){
        return $this->getDailyAccess('user_criadoem');
    }
    
    public function getDailyReturn(){
        return $this->getDailyAccess('user_criadoem','date(user_uacesso) > date(user_criadoem)');
    }
    
    public function getDailyPay(){
        return $this->getDailyAccess('user_criadoem',"cod_perfil = '".Assinante_Analise."'");
    }
    
     private function getDailyAccess($data_name, $where = "", $group = ""){
        $where = ($where === "")?"1":$where;
        $gr    = ($group === "")?"":",$group";
        $arr = array(
            "DATE($data_name) as data", 
            "COUNT(DISTINCT(cod_usuario)) as cod_usuario", 
        );
        return $this->selecionar($arr,"$where GROUP BY DATE(data)$gr", "", "","");
    }
    
}