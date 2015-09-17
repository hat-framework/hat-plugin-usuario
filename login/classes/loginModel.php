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

    //verifica se o usuario está logado
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
            $this->setErrorMessage('Usuário não está logado');
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
            $this->setErrorMessage('Usuário não está logado');
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
                    Caro Usuário o seu email ainda não foi confirmado 
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
                    Não foi possível gerar uma nova chave de confirmação para sua conta.
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
        //procura no banco de dados um usuário e senha iguais ao digitado pelo usuário
        $login = str_replace(array("'", '"'), "", $login);
        $senha = str_replace(array("'", '"'), "", $senha);
        $w     = (!$enc)?"`senha` = '$senha'":"`senha` = PASSWORD('$senha')";
        $where = "`email` = '$login' AND $w";
        $value = $this->db->Read($this->tabela, NULL, $where);
        
        //se login não existe ou senha está incorreta
        if(empty ($value)){
            $this->setErrorMessage("Usuário ou senha incorretos");
            return false;
        }
        //verifica se o usuário está bloqueado
        $refer = (isset($_GET['refer']))?$_GET['refer']:session::getVar('refer');
        $user  = array_shift($value);
        if($refer == "") {$refer = URL;}
        $this->makeLogin($user, $refer);
        
        //redireciona, caso necessário
        $this->Redirect(true,$login_first);
        return true;
    }
    
    private function makeLogin($user, $refer = ''){
        if($user['status'] == 'bloqueado'){
            throw new AcessDeniedException("O seu acesso foi bloquado por um administrador do sistema!");
            return false;
        }
        if(empty($user)){return false;}
        
        //seta os dados a serem salvos na sessão
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
        $refer = isset($array['referrer'])?$array['referrer']:"";
        if(!parent::inserir($array)) {return false;}
        $this->setReferrer($refer);
        $this->rdstation($array);
        return $this->sendSubscribeMessage($array, $senha);

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
    
            private function setReferrer($refer){
                $this->LoadModel('usuario/referencia', 'ref');
                if($refer === ""){
                    $refer = $this->ref->getCookie();
                    if($refer === ""){return;}
                }
                $id = $this->getLastId();
                return $this->ref->associate($refer, $id);
            }
    
            private function rdstation($array){
                $this->LoadResource('api', 'api');
                $this->rds = new resource\api\rdstation\rdstationLead();
                $this->rds->addLead($array);
            }
            
            //responsavel pelas mensagens para o usuario
            private function sendSubscribeMessage($array, $senha){
                $this->LoadModel('usuario/login/loginDialogs', 'udi');
                $user = $this->getItem($array['email'], 'email');
                $user['senha'] = $senha;
                $bool = $this->udi->inserir($user);
                $this->setMessages($this->udi->getMessages());
                return $bool;
            }
    
    public function editarDados($id, $dados){
        $var = $this->selecionar(array('cod_usuario'), "`cod_usuario` = '$id' AND `senha` = PASSWORD('".$dados['senha']."')");
        if(empty($var)){
            $this->setErrorMessage("Caro usuário, sua senha está incorreta!");
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
        
        //se não existe nenhum webmasters, seta a permissão de webmaster
        $count = $this->selecionar(array('cod_usuario'), "`permissao` = 'Webmaster'", '1');
        if(empty($count)){
            $dados['permissao'] = 'Webmaster';
            return;
        }
        if(!isset($dados['permissao'])) return;
        
        //se usuário não é admin a permissão só pode ser de visitante
        if(!$this->UserIsAdmin()) {$dados['permissao'] = 'Visitante';}
        //if(!$this->UserIsAdmin()) $dados['permissao'] = 'Admin';
        
        //se usuário é admin mas não é webmaster só pode criar um novo admin ou um novo visitante
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
        
        //se usuário não tem permissão para alterar outros usuários
        if(false === $this->UserCanAlter($id)){return false;}
        if(!isset($dados['senha_confirmacao'])){
            //se usuário que está alterando os dados é o dono da conta
            $cod_user = self::CodUsuario();
            if($id === $cod_user){
                return $this->setErrorMessage("Para alterar seus dados a senha de confirmação deve ser enviada!");
            }
        }
        
        $camp = ($camp == "")?$this->pkey:$camp;
        $where = (LINK ."/".CURRENT_ACTION != "usuario/login/edit")?"AND senha = PASSWORD('".$dados['senha_confirmacao']."')":'';
        $user = $this->selecionar(array(), "$camp = '$id' $where");
        if(empty($user)){
            return $this->setErrorMessage("Usuário ou senha incorretos");
        }
        
        if(isset($dados['senha_nova']) && $dados['senha_nova'] != $dados['confirmar_senha']){
            return $this->setErrorMessage("A senha nova deve ser idêntica à confirmação de senha");
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
        
        //impede que o usuário se exclua
        if($this->getCodUsuario($user) == $this->getCodUsuario()){
            $this->setErrorMessage("Você não pode excluir sua própria conta!");
            return false;
        }
        
        //se usuário a ser excluido é webmaster
        elseif($user['cod_perfil'] == Webmaster){
            
            //se o usuário que está excluindo um webmaster não for webmaster, então bloqueia
            if($this->getCodPerfil() != Webmaster){
                $this->setErrorMessage("Você não tem permissão de excluir um Administrador do Sistema!");
                return false;
            }

            //se quem está excluindo um webmaster é um webmaster
            else{
                
                //se só existe um webmaster, bloqueia
                $total = $this->getCount("cod_perfil = '".Webmaster."'");
                if($total == 1){
                    $this->setErrorMessage("Para excluir uma conta de Webmaster é necessário 
                        que exista pelo menos outra conta com o mesmo privilégio");
                    return false;
                }
            }
        }
        
        //apaga
        if(!parent::apagar($valor, $chave)) return false;
        $name = $user['user_name'] . " (".$user['user_cargo'].")";
        $this->setSuccessMessage("Usuário $name removido do sistema com sucesso!");
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
            $this->setErrorMessage("O usuário que você procura não existe");
            return false;
        }
        
        //verifica se é necessário atualizar a session do usuário
        $user            = array_shift($value);
        $online          = $this->IsLoged();
        $codUserOnline   = $this->getCodUsuario();
        $codUserConfirm  = $this->getCodUsuario($user);
        $atualizaSession = ($online === true && $codUserOnline == $codUserConfirm && session::exists($this->cookie))?true:false;
        
        //recupera o nome do usuário que está sendo recuperada a session
        $name            = $this->getUserNick($user);
        
        //se a chave de confirmacao esta vazia
        if($user['confirmkey'] == ""){
            if($atualizaSession){
                $co = session::getVar($this->cookie);
                $co['confirmed'] = '1';
                session::setVar($this->cookie, $co);
            }
            $this->setErrorMessage("O usuário $name foi confirmado no site anteriormente!");
            return false;
        }
        
        //se a chave de confirmacao esta errada
        if($user['confirmkey'] != $chave){
            $this->setErrorMessage("A chave de confirmação do usuário $name está incorreta!");
            return false;
        }
        
        //edita o usuário no banco de dados
        $Var['confirmkey'] = "FUNC_NULL";
        $Var['confirmed'] = "1";
        if(!parent::editar($user['cod_usuario'], $Var)){
            $this->setErrorMessage("Não foi possível confirmar o usuário $name");
            return false;
        }
        
        //atualiza a session se necessário
        if($atualizaSession){
            $co = session::getVar($this->cookie);
            $co['confirmed'] = '1';
            session::setVar($this->cookie, $co);
        }
        
        //atualiza as mensagens
        $this->setSuccessMessage("Usuário $name confirmado com sucesso!");
        session::setVar('controller_alerts', $this->getMessages());
        
        //se usuário não está online e não existe outra sessão de usuário online, faz o login do usuário
        if(!$online) $this->Login(@$user['email'], @$user['senha'], false);
        return true;

    }//c

    public function RecoverPassword($email){

        //procura o usuario no banco de dados
        $value = $this->db->Read($this->tabela, NULL, "`email` = '$email'");
        if(empty($value)){
            $this->setErrorMessage("Este email não está registrado em nossa base de dados");
            return false;
        }
        $user = array_shift($value);
        
        /*
        Edita os dados no banco
        Se confirmkey estiver encriptada, então ela contém a nova senha do usuário. 
        Do contrário gera uma nova chave de confirmação
         */
        $confkey = ($user['confirmkey'] != "")?$user['confirmkey']:genKey(16);
        $Var['confirmkey'] = $confkey;
        if(!parent::editar($user['cod_usuario'], $Var)) return false;

        if($confkey == $user['confirmkey'] && strlen($confkey) > 16) $confkey = "";
        
        //envia um alerta por email ara o usuário
        $this->LoadModel('usuario/login/loginDialogs', 'udi');
        $bool = $this->udi->RecoverPassword($user, $confkey);
        $this->setMessages($this->udi->getMessages());
        return $bool;
    }

    //confirma a recuperação de senha
    public function ConfirmRecoverPassword($dados){

        $dados   = explode("-", $dados);
        $usuario = array_shift($dados);
        $chave   = array_shift($dados);

        //verifica se existe algum usuario com esta chave de recuperação
        $value = $this->db->Read($this->tabela,NULL, "`cod_usuario` = '$usuario' AND`confirmkey` = '$chave'");
        $user = array_shift($value);
        if(empty ($user)){
            $value = $this->db->Read($this->tabela, NULL, "`cod_usuario` = '$usuario'");
            
            //verifica se usuário existe
            $user = array_shift($value);
            if(empty ($user)) {
                $this->setErrorMessage("Usuário não existe");
                return false;
            }
            
            //verifica se a chave de confirmação existe
            elseif(array_key_exists("confirmkey", $user) && $user['confirmkey'] != "") {
                
                //verifica se a chave de confirmação contém a nova senha do usuário
                if($user['confirmkey'] ==  \classes\Classes\crypt::decrypt_camp($user['confirmkey'])){
                    $this->setErrorMessage("Chave de confirmação inválida.");
                    return false;
                }
            }
            
            else{
                $this->setSuccessMessage('Usuário já confirmado');
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
            $this->setErrorMessage("Não foi possível gerar sua nova senha");
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
            $this->setErrorMessage("Usuário inexistente");
            return false;
        }
        $value = array_shift($value);
        
        if($value['confirmkey'] == NULL || $value['key'] == ""){
            $this->setSuccessMessage("Email já confirmado");
            return true;
        }
        
        $Var['confirmkey'] = $value['key'];

        //Se nao conseguiu atualizar tabela
        if(!$this->db->Update($this->tabela, $Var, "`cod_usuario` = '".$value['cod_usuario'] ."'")){
            $this->setErrorMessage("Não foi possível atualizar o banco de dados");
            return false;
        }

        //prepara o email
        $this->LoadResource("html", 'html');
        $url     = $this->html->getLink("usuario/login/confirmar/".$value['cod_usuario']."/".$Var['confirmkey']);
        $msg     = "<p><a href='$url'>clique aqui</a> Para completar sua inscrição</p>";
        $assunto = "Reenviar Confirmação";
        $corpo   = $msg;
        
        $this->LoadResource("email", "email");
        $this->email->SendMail($assunto, $corpo, $value['email']);

        $this->setSuccessMessage("Um novo email de confirmação foi enviado para você.");
        return true;
    }
    
    public function needWebmasterLogin($url = ''){
        
        //se usuário não está logado ou não é admin
        if(!$this->IsLoged() || !$this->UserIsWebmaster()){
            $this->Logout();
            $this->needLogin($url);
        }
        
        //se usuário é admin
        else $this->Redirect();
        return true;
    }
    
    public function needAdminLogin($url = ""){
        
        //se usuário não está logado ou não é admin
        if(!$this->IsLoged() || !$this->UserIsAdmin()){
            $this->Logout();
            $this->needLogin($url);
        }
        
        //se usuário é admin
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
        //usuários deslogados não são webmaster. Isto evita lançamento de exceção quando db não instalado
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
            $this->setErrorMessage("Não é possível bloquear um usuário com permissão de Webmaster");
            return false;
        }
        $bool = parent::editar($cod_usuario, array('status' => 'bloqueado', 'update_permission' => 's'));
        if(!$bool) $this->setErrorMessage("Não foi possível bloquer o acesso a este usuário");
        else       $this->setSuccessMessage ('Usuário bloqueado com sucesso!');
        return $bool;
    }
    
    public function unblockUser($cod_usuario){
        $bool = parent::editar($cod_usuario, array('status' => 'offline', 'update_permission' => 's'));
        if(!$bool) $this->setErrorMessage("Não foi possível desbloquer o acesso deste usuário");
        else       $this->setSuccessMessage ('Usuário desbloqueado com sucesso!');
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
        
        //se usuário está alterando a própria conta.
        $cod_autor = $this->getCodUsuario();
        if($cod_autor == $cod_usuario) {return true;}
        
        //se usuário é webmaster
        if($this->IsWebmaster() && !isset($_GET['_perfil'])) {return true;}
        
        //Somente um webmaster pode editar o próprio perfil
        $cod_perfil = $this->getCodPerfil($cod_usuario);
        if($cod_perfil == Webmaster){
            $this->setErrorMessage('Você não tem permissão para modificar um usuário com perfil de Webmaster!');
            return false;
        }
        
        //webmaster pode alterar os outros perfis
        $cod_perfil2  = $this->getCodPerfil($cod_autor);
        if($cod_perfil2 == Webmaster) return true;
        
        //somente um administrador pode editar um perfil de administrador
        if($cod_perfil == Admin && $cod_perfil2 != $cod_perfil) {
            $this->setErrorMessage('Você não tem permissão para modificar um usuário com perfil de Administrador!');
            return false;
        }
        
        //verifica se usuário tem permissão de alterar dados de outros usuários
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
            \classes\Utils\Log::save("Errors", "Variável request uri inexistente");
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
        \classes\Utils\Log::save($logname, ", Código do usuário, Perfil de usuário, Link, IP, Link Anterior, Mensagem;");
        else \classes\Utils\Log::save($logname, ",'$cod_usuario','$cod_perfil','$action','$ip','$refer', '$msg';");*/
    }
    
    public function getLastAccess($where){
        $res = $this->selecionar(array(),"$where");
        $count = count($res);
        return array('Descrição'=>'Cadastro','Quantidade'=>$count);
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