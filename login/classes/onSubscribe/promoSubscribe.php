<?php
class referrerSubscribe extends classes\Classes\Object{
    
    public function execute($cod_usuario, $array){
        $codpromo = isset($array['promocod'])?$array['promocod']:"";
        $this->LoadModel('usuario/promocod', 'promo');
        if($refer === ""){
            $refer = $this->promo->getCookie();
            if($refer === ""){return;}
        }
        
        try{
            if(false === $this->LoadModel('usuario/promocod/promouser', 'pruser')->attachPromocod($codpromo, $cod_usuario)){return true;}
        } catch (Exception $ex) {
            sendEmailToWebmasters('Erro ao associar promoção', "Ocorreu algum erro ao associar a promoção $refer para o usuário $cod_usuario");
            return true;
        }
        
    }
}