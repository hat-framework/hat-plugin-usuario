<?php

class usuario_promocodModel extends \classes\Model\Model {

    public $tabela = "usuario_promocod";
    public $pkey = 'cod';
    private $cookiename = 'upmcod';
    public  $cookietime = '2592000';//1 mês
    public function avaiblePromo($cod_promo){
        $promo = $this->getItem($cod_promo);
        if(empty($promo)){return $this->setErrorMessage('Código promocional não existe!');}
        if($promo['dt_inicio'] !== "" && classes\Classes\timeResource::diffDate($promo['dt_inicio'] , '', "D") > 0){
            return $this->setErrorMessage("A promoção inicia no dia: " . classes\Classes\timeResource::getFormatedDate($promo['dt_termino']));
        }
        
        if($promo['status'] == 'concluido'){
            return $this->setErrorMessage("A promoção foi encerrada no dia ". classes\Classes\timeResource::getFormatedDate($promo['dt_termino']));
        }
        
        $total = $this->LoadModel('usuario/promocod/promouser', 'puser')->getPromoTotal($cod_promo);
        if($promo['max_cadastros'] < $total ){
            $this->terminate($cod_promo);
            return $this->setErrorMessage("Promoção encerrada pois o número máximo de usuários para esta promoção foi atingido!");
        }
        
        if(classes\Classes\timeResource::diffDate($promo['dt_termino'], '', "D") < 0){
            $this->terminate($cod_promo);
            return $this->setErrorMessage("A promoção foi encerrada no dia ". classes\Classes\timeResource::getFormatedDate($promo['dt_termino']));
        }
        
        return true;
    }
    
    public function terminaPromocao($cod_promo){
        $total = $this->LoadModel('usuario/promocod/promouser', 'puser')->getPromoTotal($cod_promo);
        $promo = $this->getItem($cod_promo);
        if($promo['max_cadastros'] !== "" && $promo['max_cadastros'] > 0 && $promo['max_cadastros'] < $total ){return $this->terminate($cod_promo);}
        if(classes\Classes\timeResource::diffDate($promo['dt_termino'], '', "D") < 0){return $this->terminate($cod_promo);}
        return false;
    }
    
            private function terminate($cod_promo){
                return $this->editar($cod_promo, array('status'=>'concluido'));
            }
            
    public function createCookie($promocod){
        classes\Classes\cookie::create($this->cookiename, $this->cookietime);
        classes\Classes\cookie::setVar($this->cookiename, $promocod);
        classes\Classes\session::setVar($this->cookiename, $promocod);
    }
    
    public function getCookie(){
        $data = classes\Classes\cookie::getVar($this->cookiename);
        return (trim($data) !== "")?$data:classes\Classes\session::getVar($this->cookiename);
    }
    
}
