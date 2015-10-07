<?php

class admNotifierSubscribe extends classes\Classes\Object{
    
    public function execute($cod_usuario, $user){
        $this->cod_usuario = $cod_usuario;
        $this->LoadResource('html','html');
        
        $perfs = $this->loadPlugins_ANA();
        $mails = $this->getWebmasterEmails($perfs);
        if(empty($mails)){return true;}
        
        if(false === $this->initializeVars($user)){return true;}
        return $this->sendADMEmails(SITE_NOME . " [Novo Cadastro] $this->nome", $this->userData, $mails);
    }

            private function loadPlugins_ANA(){
                $perfs = $this->LoadModel('plugins/acesso', 'pacc')->getPerfisOfPermission('Plugins_ANA');
                if(!is_array($perfs)){$perfs = array();}
                array_unshift($perfs, Webmaster);
                array_unshift($perfs, Admin);
                return $perfs;
            }
    
            private function getWebmasterEmails($perfs){
                $mails  = array();
                $in     = implode("','", $perfs);
                $emails = $this->LoadModel('usuario/login', 'uobj')->selecionar(array('email'), "cod_perfil IN ('$in')");
                foreach($emails as $mail){
                    $mails[] = $mail['email'];
                }
                return $mails;
            }
            
            private function initializeVars($user){
                $this->refer    = isset($user['referrer'])?$user['referrer']:"";
                $this->nome     = $this->uobj->getUserNick($this->cod_usuario);
                $this->userData = $this->uobj->LoadPerfil($this->cod_usuario);
                return(!empty($this->userData));
            }
    
            private function sendADMEmails($assunto, $userData, $emails){
                $msg = $this->prepareMessage($userData);
                $this->LoadResource('email', 'mail');
                if(false == $this->mail->sendMail($assunto, $msg, $emails)){
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
                        $this->refine($c);
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
                            
                            private function refine(&$c){
                                if($c !== ""){
                                    $this->afiliate($c);
                                    $c .= "<hr/>Horário: ". \classes\Classes\timeResource::getDbDate()."<br/>"
                                         . "url: (http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']})";
                                    return;
                                }
                                $this->afiliate($c);
                                $c .= "Falha ao enviar dados de cadastro do usuário! Nenhum dado encontrado!";
                                
                            }
                            
                                    private function afiliate(&$c){
                                        if($this->refer === ""){return;}
                                        $link = $this->html->getLink("usuario/login/show/$this->refer");
                                        $c .= "Este usuário veio através de um afiliado! <a href='$link'>Ver Afiliado</a>";
                                    }
    
}