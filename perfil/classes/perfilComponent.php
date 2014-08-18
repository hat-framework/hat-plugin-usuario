<?php
class perfilComponent extends classes\Component\Component{
    protected $listActions = array(
        'Padrão'=> 'padrao', 'Permissões' => "permissoes", 'Detalhes' => "show");
    public    $list_in_table = true;
    public function show($model, $item) {
//        echo "<hr/>";
//        if(isset($item['usuario_perfil_nome']))      {echo "<h2>". $item['usuario_perfil_nome']."</h2>"; unset($item['usuario_perfil_nome']);}
//        if(isset($item['usuario_perfil_descricao'])) {echo "<h4>". $item['usuario_perfil_descricao']."</h4>"; unset($item['usuario_perfil_descricao']);}
//        if(isset($item['usuario_perfil_default']))   {echo ($item['usuario_perfil_default'] == 0)?"":"Este é o perfil padrão do sistema"; unset($item['usuario_perfil_default']);}
//        echo "<hr/>";
        parent::show($model, $item);
    }
}
?>