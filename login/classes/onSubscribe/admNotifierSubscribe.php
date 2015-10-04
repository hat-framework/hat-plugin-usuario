<?php

class admNotifierSubscribe extends classes\Classes\Object{
    
    public function execute($cod_usuario, $user){
        $this->cod_usuario = $cod_usuario;
        $this->LoadResource('html','html');
        $perfs = $this->LoadModel('plugins/acesso', 'pacc')->getPerfisOfPermission('Plugins_ANA');
        if(!is_array($perfs)){$perfs = array();}
        array_unshift($perfs, Webmaster);
        array_unshift($perfs, Admin);

        $in     = implode("','", $perfs);
        $mails  = array();
        $emails = $this->LoadModel('usuario/login', 'uobj')->selecionar(array('email'), "cod_perfil IN ('$in')");
        foreach($emails as $mail){
            $mails[] = $mail['email'];
        }
        if(empty($mails)){return;}

        $nome = $this->uobj->getUserNick($cod_usuario);
        $msg  = $this->uobj->LoadPerfil($cod_usuario);
        if(empty($msg)){return true;}
        return $this->sendADMEmails(SITE_NOME . " [Novo Cadastro] $nome", $msg, $mails);
    }

            private function sendADMEmails($assunto, $userData, $emails){
                $msg             = $this->prepareMessage($userData);
                if($msg === ""){$msg = "Falha ao enviar dados de cadastro do usuário! Nenhum dado encontrado!";}

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

                    private function prepareMessage($arr){
                        $dados = $this->uobj->getDados();
                        $c     = $this->getHeader($dados,$arr);
                        foreach ($arr as $key => $row) {
                            $c .= $this->getStr($key, $row, $dados, $arr);
                        }
                        return $c;
                    }

                            private function getHeader($dados,&$arr){
                                $pkey  = $this->uobj->getPkey();
                                $url   = (isset($dados[$pkey]))?$this->html->getLink("usuario/login/seelog/$this->cod_usuario"):"";
                                $c     = "<h1><a href='$url' target='_BLANK'>{$arr['user_name']}";
                                $c    .= ($arr['user_cargo'] != "")?" ({$arr['user_cargo']})": "";
                                $c    .= "</a></h1>";
                                unset($arr['user_name']);unset($arr['user_cargo']);unset($arr[$pkey]);
                                return $c;
                            }

                            private function getStr($key, $row, $dados, $arr){
                                if(!isset($dados[$key])){
                                    if(strstr($key, '__')){return "";}
                                    $dados[$key]['name'] = $key;
                                }
                                $title = isset($dados[$key]['name'])?$dados[$key]['name']:$key;
                                if(isset($dados[$key]['fkey'])){
                                    if($dados[$key]['fkey']['cardinalidade'] === '1n'){
                                        $url = (isset($dados["__$key"]))?$this->html->getLink($dados[$key]['fkey']['model'] ."/show/{$arr["__$key"]}"):"";
                                        return ($url === "")?"":"<br> <b>$title:</b> <a href='$url' target='_BLANK'>{$row[$arr["__$key"]]}</a>";
                                    }
                                    return "";
                                }
                                return "<br> <b>$title:</b> $row";
                            }
    
}