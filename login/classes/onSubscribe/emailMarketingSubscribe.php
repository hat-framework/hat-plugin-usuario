<?php

class emailMarketingSubscribe extends classes\Classes\Object{
    
    public function execute($cod_usuario, $array){
        $this->LoadResource('api', 'api');
        $this->rdstation($array);
        $this->egoi($array);
        return true;
    }
    
        private function rdstation($array){
            try{
                if(!class_exists("resource\api\emailMarketing\rdstationLead")){return;}
            } catch (Exception $ex) {
                return;
            }
            $rds = new resource\api\emailMarketing\rdstationLead();
            $rds->addLead($array);
        }
        
        private function egoi($array){
            try{
                if(!class_exists("resource\api\emailMarketing\egoiLead")){return;}
            } catch (Exception $ex) {
                return;
            }
            $egoi      = new resource\api\emailMarketing\egoiLead();
            $e         = explode(' ', $array['user_name']);
            $firstname = array_shift($e);
            $lastname  = end($e);
            $arguments = array(
                'email'     => $array['email'],
                'first_name'=> $firstname,
                'last_name' => $lastname
            );
            $egoi->addLead($arguments);
        }
    
}