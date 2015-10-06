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
                $obj = $this->api->LoadApiClass('emailMarketing/rdstationLead');
                if($obj == null){return true;}
            } catch (Exception $ex) {return;}
            $obj->addLead($array);
        }
        
        private function egoi($array){
            try{
                $obj = $this->api->LoadApiClass('emailMarketing/egoiLead');
                if($obj == null){return true;}
            } catch (Exception $ex) {return;}
            $e         = explode(' ', $array['user_name']);
            $firstname = array_shift($e);
            $lastname  = end($e);
            $arguments = array(
                'email'     => $array['email'],
                'first_name'=> $firstname,
                'last_name' => $lastname
            );
            $obj->addLead($arguments);
        }
    
}