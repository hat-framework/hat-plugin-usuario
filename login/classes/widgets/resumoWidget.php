<?php

class resumoWidget extends \classes\Component\widget{    
    public function widget(){
        $this->LoadModel('usuario/login', 'uobj');
        $id = ($this->id == "")?"widget_".  str_replace("/", "_", $this->modelname):$this->id;
        $this->gui->opendiv($id, $this->class);
            $this->gui->subtitle("Resumo dos usuários");
            $this->total();
            $this->cadastroGraf();
            $this->perfilGraf();
            $this->origemGraf();            
        $this->gui->closediv();
    }
    
    private function total(){
        $total  = $this->uobj->getCount();
        echo "Total de Usuários: $total";
    }
    
    private function cadastroGraf(){
        $acesso = $this->uobj->getUltimosCadastros();
        echo $this->LoadResource('charts', 'ch')
                    ->init('LineChart')
                    ->load($acesso)
                    ->draw('user_cadastro',  array('title' => 'Cadastros por data'));
    }
    
    private function perfilGraf(){
        $perfil = $this->uobj->getCountPerfil();
        echo $this->LoadResource('charts', 'ch')
                ->init('BarChart')
                ->load($perfil)
                ->draw('user_count', array('title' => 'Número de usuários por perfil'));
    }
    
    private function origemGraf(){
        $origem = $this->uobj->getOrigem();
        echo $this->LoadResource('charts', 'ch')
                    ->init('BarChart')
                    ->load($origem)
                    ->draw('user_origem',  array('title' => 'Origem do cadastro dos usuários'));
    }
}