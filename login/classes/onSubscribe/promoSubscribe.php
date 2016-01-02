<?php
class promoSubscribe extends classes\Classes\Object{
    
    public function execute($cod_usuario, $array){
        $codpromo = isset($array['promocod'])?$array['promocod']:"";
        $this->LoadModel('usuario/promocod', 'promo');
        if($codpromo === ""){
            $codpromo = $this->promo->getCookie();
            if($codpromo === ""){return;}
        }
        
        try{
            if(false === $this->LoadModel('usuario/promocod/promouser', 'pruser')->attachPromocod($codpromo, $cod_usuario)){return true;}
        } catch (Exception $ex) {
            sendEmailToWebmasters('Erro ao associar promoção', "Ocorreu algum erro ao associar a promoção $codpromo para o usuário $cod_usuario");
            return true;
        }
        
    }
}