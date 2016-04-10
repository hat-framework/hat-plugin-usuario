<?php

class notifyUserSubscribe extends classes\Classes\Object{
       
    public function execute($cod_usuario, $user){
        $this->LoadModel('usuario/login', 'uobj');
        $this->LoadResource('html','html');
        $msg      = "";
        $assunto  = "Ativação da conta";
        $isLogged = $this->uobj->IsLoged();
        $nomeuser = $this->uobj->getUserNick($user);
        $corpo    = $this->messageBody($user, $isLogged, $nomeuser, $msg);
        
        //se não conseguiu enviar o alerta
        if(!$this->alertar($assunto, $corpo, $user['email'])){
            $msg = (!$isLogged)?
                "Olá $nomeuser, seu cadastro foi realizado com sucesso! Seja bem vindo ao site ".SITE_NOME."!":
                "Usuário $nomeuser cadastrado com sucesso! Porém ocorreu alguma falha ao notificá-lo por email.";
        }
        
        return $this->setSuccessMessage($msg);
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
                $corpo .= "<p>Para confirmar que foi você mesmo quem se cadastrou seu email no site <b><a href='$link'>clique aqui</a></b></p>
                   <hr/><p>Caso não consiga visualizar o link acima: $link</p>
                ";
                return $corpo;
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