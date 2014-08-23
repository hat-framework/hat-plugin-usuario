<?php

class painelComponent extends classes\Component\Component{
    public function draw($class = 'painel-usuario'){
        $this->LoadModel("usuario/login", 'umodel');
        if($this->umodel->IsLoged()){
            $logout  = $this->Html->getLink("usuario/login/logout");
            $compras = $this->Html->getLink("loja/usuario/");
            echo "<div class='$class'>
                    <a href='$compras'>Meu Painel</a>
                    <a href='$logout'>Logout</a>
                  </div>";
        }
        else{
            $login   = $this->Html->getLink("usuario/");
            echo "<div class='$class'>
                    <a href='$login'>Faça login</a>
                  </div>";
        }
    }
    
    public function painelAdmin($class = 'painel-usuario'){

        try{
            $this->LoadModel("usuario/login", 'umodel');

            if($this->umodel->IsLoged()){
                $item = $this->umodel->getItem($this->umodel->getCodUsuario());
                $nome = @$item['user_name'];

                $logout  = $this->Html->getLink("usuario/login/logout");
                echo "<div class='$class'>Bem Vindo, $nome
                        <a href='$logout' class='button orange transition'>Sair</a>
                      </div>";
            }
        }catch(\classes\Exceptions\DBException $e){
            $logout  = $this->Html->getLink("usuario/login/logout");
            echo "<div class='$class'>Bem Vindo
                    <a href='$logout' class='button orange transition'>Sair</a>
                  </div>";
        }
    }
}