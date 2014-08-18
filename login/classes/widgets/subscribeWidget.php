<?php

use classes\Classes\Object;
class subscribeWidget extends classes\Classes\Object{
    
    public function widget(){
        $this->LoadComponent('usuario/login/subscribe', 'tl')->screen('');
    }
    
}