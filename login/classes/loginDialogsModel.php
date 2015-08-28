<?php

use classes\Classes\Object;
class usuario_loginDialogsModel extends classes\Classes\Object {

    public function __construct() {
        $this->LoadResource("html", 'html');
    }
    
    public function setModelName($name){
        $this->model_name = $name;
    }
    
    public function inserir($user){

        //prepara o email
        $this->LoadModel('usuario/login', 'uobj');
        $isLogged = $this->uobj->IsLoged();
        $msg      = "";
        $nomeuser = $this->uobj->getUserNick($user);
        $assunto  = "Ativação da conta";
        $corpo    = $this->messageBody($user, $isLogged, $nomeuser, $msg);
        
        //se não conseguiu enviar o alerta
        if(!$this->alertar($assunto, $corpo, $user['email'])){
            $msg = (!$isLogged)?
                "Olá $nomeuser, seu cadastro foi realizado com sucesso! Seja bem vindo ao site ".SITE_NOME."!":
                "Usuário $nomeuser cadastrado com sucesso! Porém ocorreu alguma falha ao notificá-lo por email.";
        }
        
        $this->notifyAdminCadastro($user);
        
        $this->autoLogin($user, $isLogged);
        $this->setSuccessMessage($msg);
        return true;
    }
            private function messageBody($user, $isLogged, $nomeuser, &$msg){
                $msg = "Um email de confirmação foi enviado para o email: '".$user['email']."'
                    Acesse o email e clique no link enviado para ter acesso ao site";
                $corpo    = "<h2>Olá $nomeuser</h2><p>Seja bem vindo ao site ".SITE_NOME."</p>";
                $link     = $this->html->getLink("usuario/login/confirmar/{$user['cod_usuario']}-{$user['confirmkey']}");
                
                if($isLogged){
                    $usertry  = $this->uobj->getItem($this->uobj->getCodUsuario());
                    $nome     = $this->uobj->getUserNick($usertry);
                    $mail     = $usertry['email']; 
                    $corpo   .= "<p>Você foi inscrito no site pelo usuário </b> $nome ($mail)</p><hr/>";
                    $msg      = "Usuário cadastrado com sucesso";
                }
                $corpo .= "<p>Confira seus dados de acesso e <b><a href='$link'>clique aqui</a></b> para concluir o cadastro no site</p>
                   <p><b>dados de acesso:</b></p>
                   <p>Login: ".$user['email']." </p>
                   <p>Senha: ".$user['senha']." </p>
                   <hr/><p>Caso não consiga visualizar o link acima: $link</p>
                ";
                return $corpo;
            }
            
            public function notifyAdminCadastro($user){
                $perfs = $this->LoadModel('plugins/permissao', 'plug')->getPerfisOfPermission('Plugins_ANA');
                if(!is_array($perfs)){$perfs = array();}
                array_unshift($perfs, Webmaster);
                array_unshift($perfs, Admin);
                
                $in     = implode("','", $perfs);
                $mails  = array();
                $emails = $this->LoadModel('usuario/login', 'user')->selecionar(array('email'), "cod_perfil IN ('$in')");
                foreach($emails as $mail){
                    $mails[] = $mail['email'];
                }
                
                $nome = $this->uobj->getUserNick($user['cod_usuario']);
                $msg  = $this->uobj->LoadPerfil($user['cod_usuario']);
                return $this->sendADMEmails(SITE_NOME . " [Novo Cadastro] $nome", $msg, $emails);
            }
            
                    private function sendADMEmails($assunto, $msg, $emails){
                        $obj             = new \classes\Classes\Object();
                        $mail            = $obj->LoadResource('email', 'mail');
                        $msg            .= "<hr/>Horário: ". \classes\Classes\timeResource::getDbDate()."<br/>url: (http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']})";
                        if(empty($emails)){
                            \classes\Utils\Log::save("system/mail/error", "Nenhum webmaster encontrado no método getWebmastersMail");
                            return false;
                        }
                        if(false == $mail->sendMail($assunto, $msg, $emails)){
                            \classes\Utils\Log::save("system/mail/error", 
                                "<div class='email_trouble' style='border:1px solid red;'>"
                                ."<h2>$assunto</h2><div class='msg'><p>$msg</p></div></div><hr/>");
                            return false;
                        }
                        return true;
                    }
    
            private function autoLogin($user, $isLogged){
                if($isLogged){return;}
                if(!defined ('USUARIO_LOGIN_AUTOLOGIN_CADASTRO') || USUARIO_LOGIN_AUTOLOGIN_CADASTRO !== true){return;}
                $bool = $this->uobj->Login($user['email'], $user['senha'],true,true);
                return $bool;
            }
    
    public function resend_confirmation($user){
        
        $ur     = $user['cod_usuario']."-".$user['confirmkey'];
        $link   = $this->html->getLink("usuario/login/confirmar/$ur");
        $corpo  = "
            <h2>Pedido de Confirmação</h2>
            <p>Caro usuário seu email ainda não foi confirmado em nosso site, por favor 
                <a href='$link'>clique aqui</a> para confirmar seu email</p>
            <hr/>
            <p>Caso não consiga visualizar o link acima: $link</p>
        ";

        //se não conseguiu enviar o alerta
        if(!$this->alertar("Pedido de Confirmação", $corpo, $user['email'])){
            $this->setErrorMessage("Não foi possível enviar a confirmação para o seu email");
            return false;
        }
        
        $this->setSuccessMessage("O email de confirmação foi enviado com sucesso!");
        return true;
    }
    
    public function editar($user, $old_user){
 
        //prepara o email
        $link    = $this->html->getLink("usuario/login/recuperar/");
        $assunto = "Alterações nos dados ".SITE_NOME;
        
        $corpo = "";
        $this->LoadModel('usuario/login', 'uobj');
        $usuariotry  = $this->uobj->getItem($this->uobj->getCodUsuario());
        $nome        = $this->uobj->getUserNick($usuariotry);
        $mail        = $usuariotry['email'];
        if($usuariotry['email'] != $user['email'])
            $corpo  .= "<p> Alterações feitas em sua conta pelo usuário: $nome, email: $mail </p><hr/>";
        
        if($user['email'] != $old_user['email'])
            $corpo  .= "<p> O seu email de acesso foi alterado, o novo email é: ".$user['email'].". </p><hr/>";
        
        $corpo = "
          <p>Olá ".$user['user_name']."</p> 
          <p>
          Seus dados foram alterados recentemente no site ".SITE_NOME." em ".\classes\Classes\timeResource::getFormatedDate().". <br/>
              Como medida de segurança, essa notificação foi enviada ao seu endereço de email. 
              Se foi você quem fez estas mudanças desconsidere este email.
          </p>
                $corpo
          <hr/>
          <p>
            Se esta mudança não foi autorizada por você, pode ter ocorrido alguma tentativa de fraude em sua conta.
            Acesse <a href='$link'>este link</a> para obter novamente o controle da sua conta.
            <br/>Caso seu cliente de email não permita a visualização do link acima, acesse: $link.
          </p>
         ";
        
        
        //se ocorreu alguma falha ao enviar o email
        $msg = "Informações da conta alteradas com Sucesso!";
        $this->alertar($assunto, $corpo, $old_user['email']);
        $this->setSuccessMessage($msg);
        return true;
    }

    public function RecoverPassword($value, $confirmkey){

        //recupera o corpo e o assunto do email
        $this->LoadResource("html", 'Html');
        $link    = $this->Html->getLink("usuario/login/confirmrec/".$value['cod_usuario']."-$confirmkey");
        $assunto = "Recuperação de senha";
        $corpo = "
            <p>Foi feita um pedido de recuperação de senha no site ".SITE_NOME."</p>
            <p>Caso não tenha sido solicitado por você desconsidere este email</p>
            <p>Para concluir a recuperação de senha <a href='$link'>clique aqui</a></p>
            <hr/><p>Caso não consiga visualizar o link acima: $link </p>";

        //se nao conseguiu enviar email
        if(!$this->alertar($assunto, $corpo, $value['email'])){
            $this->setSuccessMessage("Para concluir a recuperação de senha <a href='$link'>clique aqui</a>");
        }
        else $this->setSuccessMessage("Um email de confirmação será enviado para o seu email em alguns instantes. Acesse o seu email e clique no link indicado para recuperar sua senha");
        return true;
    }

    public function ConfirmRecoverPassword($value, $senha){
        //recupera o corpo e o assunto do email
        $this->LoadResource("html", 'Html');
        $link1   = $this->Html->getLink("usuario/login/senha");
        $link2   = $this->Html->getLink("usuario/login/");
        $assunto = "Nova senha do site ". SITE_NOME;
        $corpo   = "
            <p>Caro usuário, <br/> Uma solicitação de recuperação de senha foi enviada para o nosso site. </p>
            <p>Sua nova senha é: $senha</p><hr/>
            <p>Para alterar sua senha <a href='$link1'>clique aqui</a></p> 
            <p>Para acessar sua conta <a href='$link2'>clique aqui</a></p>
            <hr/>
            Caso não consiga visualizar os links acima:
            <p>Recuperar senha $link1 </p>
            <p>Acessar conta $link2 </p>
        ";

        //se nao conseguiu enviar email
        if(!$this->alertar($assunto, $corpo, $value['email'])){
            $this->setAlertMessage("Ocorreu um erro no servidor ao enviar uma notificação para o seu email");
            $this->setSuccessMessage("Sua nova senha é: $senha");
        }else $this->setSuccessMessage("Um email será enviado para você em alguns instantes com sua nova senha");

        return true;
    }
    
    private function alertar($assunto, $corpo, $destinatarios, $nome_remetente = ""){
        if(!is_array($destinatarios) && $destinatarios === ""){return true;}
        $this->LoadResource('email', 'mail');
        if(false === $this->mail->sendMail($assunto, $corpo, $destinatarios, "", $nome_remetente)){
            $this->setMessages($this->mail->getMessages());
            return false;
        }
        return true;
    }
    
}