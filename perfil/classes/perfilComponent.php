<?php
class perfilComponent extends classes\Component\Component{
    protected $listActions = array(
        'Padrão'=> 'padrao', 'Permissões' => "permissoes", 'Detalhes' => "show");
    public    $list_in_table = true;
}