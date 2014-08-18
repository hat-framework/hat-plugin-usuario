<?php 
class usuario_enderecoModel extends \classes\Model\Model{
    public $tabela      = "usuario_endereco";
    public $pkey        = 'cod';
    protected $feature  = "USUARIO_ENDERECO";
    
    public function getUserAddress($cod_usuario = ""){
        $cod_usuario = usuario_loginModel::CodUsuario();
        $arr = $this->selecionar(array(), "login='$cod_usuario'");
        if(empty($arr)){return array();}
        return array_shift($arr);
    }
    
    public function inserir($dados) {
        if(false === $this->LoadModel('usuario/login', 'uobj')->autentica($post['senha_confirmacao'])){
            return $this->setErrorMessage('A senha que você digitou está incorreta!');
        }
        $dados['login'] = usuario_loginModel::CodUsuario();
        return parent::inserir($dados);
    }
    
    public function editar($id, $post, $camp = "") {
        if(false === $this->LoadModel('usuario/login', 'uobj')->autentica($post['senha_confirmacao'])){
            return $this->setErrorMessage('A senha que você digitou está incorreta!');
        }
        return parent::editar($id, $post, $camp);
    }
    
    public function findCep($cep){
        $arr = $this->selecionar(array('rua','bairro','cidade','estado'), "cep='$cep'", 1);
        return (empty($arr)?array():  array_shift($arr));
    }
    
}
