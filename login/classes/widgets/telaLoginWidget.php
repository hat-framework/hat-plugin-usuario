<?php

use classes\Classes\Object;
class telaLoginWidget extends classes\Classes\Object{
    
    public function widget(){
        $this->LoadComponent('usuario/login/telaLogin', 'tl')->screen('span12');
    }
    
}