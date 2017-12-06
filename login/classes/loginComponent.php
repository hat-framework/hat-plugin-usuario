<?php

use classes\Classes\EventTube;

class loginComponent extends classes\Component\Component {

  public function Subscribe($options = '') {
    $this->callUserComponent("subscribe", $options);
  }

  public function tela_login($options = '') {
    $this->callUserComponent("telaLogin", $options);
  }

  public function superior_login($options = '') {
    $this->callUserComponent("superiorLogin", $options);
  }

  public function recuperar($options = '') {
    $this->callUserComponent("recuperar", $options);
  }

  public function LoggedMenu($options = '') {
    return $this->callUserComponent("usermenu", $options, 'getLoggedMenu');
  }

  public function menu() {
    $this->LoadModel('usuario/login', 'uobj');
    $arr = array(
      'Página Inicial' => array('Página Inicial' => MODULE_DEFAULT),
      /* 'Administrar Usuários' => array(
        //'Administrar Usuários'  => 'usuario/login/todos',
        'Usuários'              => array('Usuários'          => 'usuario/login/todos'),
        'Perfis de Usuário'     => array('Perfis de Usuário' => 'usuario/perfil/index'),
        ), */
      'Minha Conta' => array(
        'Minha Conta' => 'usuario/login',
        'Criar nova conta' => array('Criar nova conta' => 'usuario/login/inserir'),
        'Esqueci Minha senha' => array('Esqueci Minha senha' => 'usuario/login/recuperar'),
        'Alterar Dados' => array('Alterar Dados' => 'usuario/login/email'),
        'Alterar Senha' => array('Alterar Senha' => 'usuario/login/senha'),
        //'Alterar Dados'         => array('Alterar Dados'        => 'usuario/login/dados'),
        'Sair' => array('Sair' => 'usuario/login/logout')
      )
    );
    if ($this->uobj->IsLoged()) {
      unset($arr['Minha Conta']['Criar nova conta']);
      unset($arr['Minha Conta']['Esqueci Minha senha']);
    } else {
      unset($arr['Minha Conta']['Email e Senha']);
      unset($arr['Minha Conta']['Sair']);
    }
    if (MODULE_DEFAULT == "usuario")
      unset($arr['Página Inicial']);

    $this->LoadJsPlugin('menu/treeview', 'mt');
    //$this->LoadJsPlugin('menu/menu', 'mt');
    $this->mt->imprime();
    $var = $this->mt->draw($arr);
    $var = "<h3>Minha Conta</h3>$var";
    EventTube::addEvent('menu-lateral', $var);
  }

  public function setLoadMenu($region = "menu-superior") {

    $menu_array = $this->LoggedMenu();
    if (!\usuario_loginModel::IsWebmaster()) {
      unset($menu_array['Área Administrativa']);
    }
    //gera o menu superior
    $this->LoadJsPlugin('menu/dropdown', 'mn');
    $this->mn->imprime();
    $var = $this->mn->draw($menu_array, "navbar-right", 'user-menu');

    EventTube::addEvent($region, $var);
  }

  protected $listActions = array('Veja Mais' => "show", 'Bloquear' => "block", 'Desbloquear' => 'unblock');

  private function tags(&$item) {
    if (isset($item['status']) && trim($item['status']) !== '') {
      $this->gui->label($item['status'], "label_{$item['__status']}", $item['__status']);
      unset($item['status']);
      unset($item['__status']);
    }
    if (isset($item['confirmed']) && trim($item['confirmed']) !== '') {
      $this->gui->label($item['confirmed'], "label_{$item['__confirmed']}", $item['__confirmed']);
      unset($item['confirmed']);
      unset($item['__confirmed']);
    }
  }

  public function show($model, $item) {
    $this->drawTitle($item);
    $this->itemSection($model, $item);
    $this->tagsSection($item['cod_usuario']);
    $this->gui->separator();
    $this->configSection($item['cod_usuario']);
  }

  public function drawTitle(&$item) {
    echo "<style>#item_header{background:#fff;text-align:center;}";
    echo "#gadget_header{height:44px;display: block;text-align:center; background:#E5E5E5;}</style>";
    $this->gui->opendiv('item_header', 'col-xs-12');
    if (isset($item['user_name']) && trim($item['user_name']) !== '') {
      $this->gui->title($item['user_name']);
    }
    if (isset($item['user_cargo']) && trim($item['user_cargo']) !== '') {
      $this->gui->infotitle($item['user_cargo']);
    }
    if (isset($item['cod_perfil']) && isset($item['__cod_perfil']) && isset($item['cod_perfil'][$item['__cod_perfil']])) {
      $this->gui->infotitle($item['cod_perfil'][$item['__cod_perfil']]);
    }
    $this->tags($item);

    $this->gui->closediv();
    $this->gadgets($item);
  }

  private function itemSection($model, $item) {
    $dados = $this->LoadModel($model, 'uobj')->getDados();
    $data = array();
    foreach ($item as $name => $it) {
      if (!isset($dados[$name])) {
        continue;
      }
      if (isset($dados[$name]['private']) && $dados[$name]['private'] === true) {
        continue;
      }
      $title = (isset($dados[$name]['name'])) ? $dados[$name]['name'] : $name;
      if (is_array($it)) {
        if (!isset($item["__$name"]) || !isset($dados[$name]['fkey'])) {
          continue;
        }
        $it = $this->Html->getActionLinkIfHasPermission(
          $dados[$name]['fkey']['model'] . "/show/{$item["__$name"]}", $item[$name][$item["__$name"]], "", "", "", "", true
        );
      }
      $data[] = array($title, $it);
    }
    $this->tableData("Dados Pessoais", $data, 'col-xs-12 col-sm-12 col-md-7', 'fa fa-user');
  }

  private function tagsSection($cod_usuario) {
    $tags = $this->LoadModel('usuario/tag/usertag', 'utag')->getUserTags($cod_usuario);
    ob_start();
    $this->AddTagLink($cod_usuario, $tags);
    foreach ($tags as $tag) {
      $this->tagLinks($tag, $cod_usuario);
    }
    $content = ob_get_contents();
    ob_end_clean();
    $this->tableData("Tags", $content, 'col-xs-12 col-sm-12 col-md-5', 'fa fa-tags');
  }

  private function AddTagLink($cod_usuario, $tags) {
    $lk = $this->Html->getActionLinkIfHasPermission("usuario/tag/usertag/formulario", "");
    if (trim($lk) == "") {
      return;
    }
    $out = $this->getArray($tags);
    $this->LoadResource('formulario', 'form')->NewForm($out, array('cod_usuario' => $cod_usuario), array(), true, 'usuario/tag/usertag/formulario');
  }

  private function getArray($tags) {
    $filter = $this->getFilter($tags);
    $dados = $this->LoadModel('usuario/tag/usertag', 'utag')->getDados();
    $out['cod_tag'] = $dados['cod_tag'];
    $out['cod_usuario'] = $dados['cod_usuario'];
    $out['cod_tag']['filther'] = "cod_tag NOT IN('$filter')";
    $out['cod_usuario']['especial'] = 'hidden';
    $out['button'] = array('button' => 'Adicionar Tag');
    return $out;
  }

  private function getFilter($tags) {
    $in = array();
    foreach ($tags as $tag) {
      $in[] = $tag['cod_tag'];
    }
    return implode("','", $in);
  }

  private function tagLinks($tag, $cod_usuario) {
    if (trim($tag['tag']) === "") {
      return;
    }

    $link = $this->Html->getActionLinkIfHasPermission("usuario/tag/show/{$tag["cod_tag"]}", $tag['tag'], "", "", "", "style='color:#FFF;'", true);
    if (trim($link) === "") {
      return;
    }
    echo "<div class='col-xs-12 col-sm-6'><button class='btn btn-primary col-xs-12' type='button' style='margin:5px; color:#FFF;'> ";

    $link2 = $this->Html->getActionLinkIfHasPermission(
      "usuario/tag/usertag/apagar/{$cod_usuario}/{$tag["cod_tag"]}", "<i class='fa fa-remove'></i>", "", "", "", "", true
    );
    if (trim($link2) !== "") {
      echo "<span class='badge' style='float:right; top:0;'>$link2</span>";
    }

    echo "$link</button></div>";
  }

  private function configSection($cod_usuario) {
    $cod = 'pessoal';
    $forms = $this->LoadModel('config/form', 'frm')->selecionar(array('cod', 'title', 'description', 'icon', 'form_data'), "`group`='$cod'");
    if (empty($forms)) {
      return array();
    }
    $this->LoadMansonry();
    $forms2 = $this->prepareLinks($forms, $cod_usuario);
    $out = $this->prepareData($cod_usuario, $forms2);
    $this->printSide($forms2, $out, $cod_usuario);
  }

  private function LoadMansonry() {
    $this->LoadResource('html', 'html');
//                        $this->LoadResource('html', 'html')->LoadBowerComponent("masonry/dist/masonry.pkgd.min.js");
//                        $this->html->LoadJqueryFunction("$('#grid').masonry({
//                                itemSelector: '.grid_item',
//                                fitWidth: true
//                              });");
    echo "<style>#grid{width:95%;}</style>";
  }

  private function prepareLinks($forms, $cod_usuario) {
    $forms2 = array();
    echo "<div class='col-xs-12'>";
    echo "<h2>Acesso Rápido</h2>";
    foreach ($forms as $data) {
      $link = $this->html->getLink("usuario/login/show/$cod_usuario");
      echo "<a href='{$link}panel_{$data['cod']}' class='btn btn-default'>{$data['title']}</a>";
      $forms2[$data['cod']] = $data;
    }
    echo "</div>";
    return $forms2;
  }

  private function prepareData($cod_usuario, $forms2) {
    $this->LoadModel('config/response', 'resp')->Join('config/form', array('form'), array('cod'), "LEFT");
    $data = $this->resp->selecionar(
      array('form_response', 'form'), "login='$cod_usuario' AND (form LIKE '%pessoal_%' OR `group`='pessoal')"
    );
    $out = array();
    foreach ($data as $dt) {
      if (!isset($out[$dt['form']])) {
        $out[$dt['form']] = array();
      }
      if (!array_key_exists($dt['form'], $forms2)) {
        continue;
      }
      $out[$dt['form']][] = $this->formatLine($forms2, $dt);
    }
    return $out;
  }

  private function formatLine($forms2, $dt) {
    $temp = array();
    $current = $forms2[$dt['form']]['form_data'];
    foreach ($dt['form_response'] as $name => $val) {
      $this->getEnumVal($current, $name, $val);
      $this->fkeyCase($current, $name, $val);
      $name = isset($current[$name]['name']) ? $current[$name]['name'] : $name;
      $temp[$name] = $val;
    }
    return $temp;
  }

  private function getEnumVal($current, $name, &$val) {
    if (!is_array($current) ||
      !isset($current[$name]) ||
      !isset($current[$name]['type'])
    ) {
      return;
    }
    if ($current[$name]['type'] !== 'enum') {
      return;
    }
    if (isset($current[$name]['options'][$val])) {
      $val = $current[$name]['options'][$val];
      return;
    }
    $lower = strtolower($val);
    if (isset($current[$name]['options'][$lower])) {
      $val = $current[$name]['options'][$lower];
    }
  }

  private function fkeyCase($current, $name, &$val) {
    if (!isset($current[$name]['fkey'])) {
      return;
    }
    $key = $current[$name]['fkey']['keys'][0];
    $k2 = $current[$name]['fkey']['keys'][1];
    $res = $this->LoadModel($current[$name]['fkey']['model'], 'tmp')->selecionar(
      $current[$name]['fkey']['keys'], "$key='$val'"
    );
    if (!is_array($res)) {
      return;
    }
    $res2 = array_shift($res);
    $val = $this->Html->getActionLinkIfHasPermission($current[$name]['fkey']['model'] . "/show/{$res2[$key]}", $res2[$k2]);
  }

  private function printSide($forms2, $out, $cod_user) {
    $this->gui->opendiv('grid', "foo col-xs-12", array());
    $style = "";
    foreach ($forms2 as $current) {
      if (!isset($current['cod']) || !isset($out[$current['cod']])) {
        continue;
      }
      $content = $this->getContentArray($out[$current['cod']]);
      $link = $this->html->getActionLinkIfHasPermission("config/group/form/pessoal/{$current['cod']}&_user=$cod_user&_click=login", "<i class='fa fa-pencil'></i>");
      $this->tableData($current['title'] . "<span class='pull-right'>$link<span>", $content, "grid_item $style", $current['icon'], false, "panel_{$current['cod']}");
    }
    $this->gui->closediv();
  }

  private function getContentArray($values) {
    $content = array();
    foreach ($values as $val) {
      foreach ($val as $key => $v) {
        if (!array_key_exists($key, $content)) {
          $content[$key] = array($key);
        }
        $content[$key][] = $v;
      }
    }
    return $content;
  }

  private function tableData($title, $data, $class, $icon = "", $multitable = false, $id = "") {
    $content = $this->getContent($data, $multitable);
    if (trim($content) === '') {
      return false;
    }
    $idd = GetPlainName($id);
    $this->gui->opendiv($idd, $class)
      ->openPanel('')
      ->panelHeader($title, $icon)
      ->panelBody($content)
      ->closePanel()
      ->closediv();
    return true;
  }

  private function getContent($data, $multitable) {
    if (!is_array($data)) {
      return $data;
    }
    $this->LoadResource('html/table', 'tb');

    if (!$multitable) {
      return $this->tb->printable(false)->draw($data, array());
    }
    $content = "";
    foreach ($data as $dt) {
      $content .= $this->tb->printable(false)
        ->draw($dt, array());
    }
    return $content;
  }

  private function gadgets($item) {
    if (!isset($item['cod_usuario'])) {
      return;
    }
    $gadgets = $this->LoadModel('usuario/gadget', 'uga')->selecionar();
    $cod_usuario = $item['cod_usuario'];
    $this->gui->opendiv('gadget_header', 'col-xs-12');
    if ($cod_usuario == \usuario_loginModel::CodUsuario()) {
      $this->makeGadgetLink("config/group/form/acesso/acesso_email", 'Alterar Dados');
    }
    if (!empty($gadgets)) {
      foreach ($gadgets as $ga) {
        $link = "usuario/gadget/exec/{$ga['cod']}/$cod_usuario";
        $this->makeGadgetLink($link, $ga['titulo']);
      }
    }
    $this->makeGadgetLink("usuario/login/show/$cod_usuario", 'Sobre');
    $this->gui->closediv();
  }

  private function makeGadgetLink($link, $title) {
    $url = $this->Html->getLink($link);
    echo (strstr(CURRENT_URL, $link)) ?
      $title : "<a class='active' style='margin-right:12px; line-height:44px;' href='$url' active>$title</a>";
  }

  public function getActionLinks($model, $pkey, $item) {

    if (!isset($item['status']) || !isset($this->listActions["Desbloquear"])) {
      return parent::getActionLinks($model, $pkey, $item);
    }
    $laction = $this->listActions;
    if ($item['status'] == 'bloqueado') {
      $this->listActions['Desbloquear'] = "unblock";
      unset($this->listActions['Bloquear']);
    } else {
      $this->listActions['Bloquear'] = "block";
      unset($this->listActions['Desbloquear']);
    }
    $var = parent::getActionLinks($model, $pkey, $item);
    $this->listActions = $laction;
    return $var;
  }

  private function callUserComponent($component, $options = "", $method = 'screen') {
    $this->LoadComponent("usuario/login/$component", 'comp');
    return $this->comp->$method($options);
  }

  public function format_user_criadoem($data) {
    return (defined('USUARIO_FRIENDLY_DATE') && USUARIO_FRIENDLY_DATE) ?
      \classes\Classes\timeResource::Date2StrBr($data) :
      \classes\Classes\timeResource::getFormatedDate($data);
  }

  public function format_user_uacesso($data) {
    return (defined('USUARIO_FRIENDLY_DATE') && USUARIO_FRIENDLY_DATE) ?
      \classes\Classes\timeResource::Date2StrBr($data) :
      \classes\Classes\timeResource::getFormatedDate($data);
  }

}
