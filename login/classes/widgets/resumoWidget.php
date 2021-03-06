<?php

class resumoWidget extends \classes\Component\widget{    
    public function widget(){
        $this->LoadModel('usuario/login', 'uobj');
        $id = ($this->id == "")?"widget_".  str_replace("/", "_", $this->modelname):$this->id;
        $this->gui->opendiv($id, "$this->class panel $this->panel");
            $this->gui->panelSubtitle("Resumo dos usuários");
            $this->gui->opendiv('', 'panel-body');
            $this->total();
            $this->cadastroGraf();
            $this->perfilGraf();
            $this->origemGraf();  
            $this->gui->closediv();
        $this->gui->closediv();
    }
    
    private function total(){
        $this->gui->opendiv('total_users');
        $total  = $this->uobj->getCount();
        echo "Total de Usuários: $total";  
        $this->gui->closediv();
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