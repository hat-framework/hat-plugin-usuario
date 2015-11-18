<?php

class referrerSubscribe extends classes\Classes\Object{
    
    public function execute($cod_usuario, $array){
        $refer = isset($array['referrer'])?$array['referrer']:"";
        $this->LoadModel('usuario/referencia', 'ref');
        if($refer === ""){
            $refer = $this->ref->getCookie();
            if($refer === ""){return;}
        }
        
        try{
            if(false === $this->ref->associate($refer, $cod_usuario)){return true;}
        } catch (Exception $ex) { return true; }
        
        return $this->sendMail($refer, $array);
    }
    
            private function sendMail($refer, $array){
                $refuser = $this->LoadModel('usuario/login','uobj')->getSimpleItem($refer);
                $link    = $this->LoadResource('html','html')->getLink('config/group/form/pessoal/pessoal_referrer');
                $corpo   = 
                    "<h2>Olá {$refuser['user_name']}</h2>
                    <p>Novo afiliado associado ao seu email no site ".SITE_NOME."</p>
                    <p><b>dados do afiliado:</b></p>
                    <p>Nome: ".$array['user_name']." </p>
                    <p>Email: ".$array['email']." </p><hr/>
                    Para acessar uma lista contendo todos os seus afiliados clique no link abaixo <br/>
                    <a href='$link'>$link</a>
                ";
                $assunto  = SITE_NOME . " [Novo Afiliado] {$array['user_name']}";
                $mail     = $this->LoadResource('email', 'mail');
                if(false == $mail->sendMail($assunto, $corpo, array($refuser['email']))){
                    \classes\Utils\Log::save("system/mail/error", 
                        "<div class='email_trouble' style='border:1px solid red;'>"
                        ."<h2>$assunto</h2><div class='msg'><p>$corpo</p></div></div><hr/>");
                    return false;
                }
                return true;
            }
    
}