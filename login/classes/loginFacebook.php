<?php

use classes\Classes\Object;
class loginFacebook extends classes\Classes\Object{
    
    /**
     * variável APP Id do aplicativo do facebook
     */
    private $appId       = '243156629188469';
    
    /**
     * Variável app secret do aplicativo do facebook
     */
    private $appSecret   = '1e7f0f59a4e7a376bfc50e0d56f914bb';
    
    /**
     * Url para a autenticação no facebook
     */
    private $fb_url      = "https://graph.facebook.com/oauth/access_token?client_id=%appid%&redirect_uri=%red_url%&client_secret=%secret%&code=%code%";
    
    /**
     * Url informada no campo "Site URL"
     */
    private $redirectUri = ""; 
    public function __construct() {
        $this->LoadResource('html', 'html');
        $this->redirectUri = $this->html->getLink('usuario/login/facebook', false, true);
    }
    
    private $md_user = '';
    public function setUserModel(\usuario_loginModel $md_user){
        $this->md_user = $md_user;
    }
    
    public function valida($code){
        $token_url = $this->getTokenUrl($code);
        $response  = $this->getData($token_url); if($response  === false) return false;
        $user      = $this->getUser($response);  if($user      === false) return false;
        if(!$this->validateUser($user)) return false;
        if($this->isRegistered($user)) return true;
        return $this->register($user);
    }
    
    private function validateUser($user){
        if(!isset($user->name)  || trim($user->name)  == ""){$this->setErrorMessage("Nome de usuário não retornado pelo facebook "); return false;}
        if(!isset($user->email) || trim($user->email) == ""){$this->setErrorMessage("Email não retornado pelo facebook"); return false;}
        return true;
    }
    
    private function getTokenUrl($code){        
        return str_replace(
                array("%appid%","%red_url%","%secret%","%code%"), 
                array($this->appId, $this->redirectUri, $this->appSecret, $code), 
                $this->fb_url
        );
    }
    
    private function getData($token_url){
        $response = @file_get_contents($token_url);   
        if(trim($response) !== "" && $response !== false) return $response;
        $msg = \usuario_loginModel::IsWebmaster()?" - token url = $token_url":"";
        $this->setErrorMessage("Erro de conexão com Facebook $msg ($response)");
        return false;
    }
    
    private function getUser($response){
        $params = null;
        parse_str($response, $params);
        if(!isset($params['access_token']) || !$params['access_token']){
            $this->setErrorMessage("Erro ao conectar com Facebook");
            return false;
        }
        $graph_url = "https://graph.facebook.com/me?access_token=".$params['access_token'];
        $var = json_decode(file_get_contents($graph_url));
        if(!$var) {$this->setErrorMessage("Dados enviados pelo facebook estão vazios");}
        return $var;

    }
    
    private function isRegistered($user){
        $this->LoadResource('database', 'db');
        $email = $this->md_user->antinjection($user->email);
        if($this->md_user->getCount("email='$email'") == 0){ return false; }
        $arr = $this->db->Read($this->md_user->getTable(), array('senha'), "email='$email'", 1); 
        if(empty($arr)){
            $this->setErrorMessage('Erro ao autenticar usuário');
            return false;
        }
        $senha = $arr[0]['senha'];
        $bool  = $this->md_user->Login($email, $senha, false);
        if(!$bool){
            $bool = $this->md_user->Login($email, $senha, true);
            if(!$bool){
                $this->setErrorMessage($this->md_user->getErrorMessage());
                return false;
            }
        }
        return true;
    }
    
    private function register($user){
        if(!isset($verified)) $verified = 0;
        $arr = array(
            'user_name'    => $user->name,
            'email'        => $user->email,
            'confirmed'    => ($user->verified === 1)?'s':'n',
            'tipo_cadastro'=> 'fb',
        );
        if(!$this->md_user->inserir($arr)){
            $this->setMessages($this->md_user->getMessages());
            return false;
        }
        return $this->isRegistered($user);
    }
    
    public function getFBLink($text = "", $class = ""){
        $link = "https://www.facebook.com/dialog/oauth?client_id={$this->appId}&redirect_uri=$this->redirectUri&scope=email";
        $text = ($text == "")?"Entrar com Facebook":$text;
        return $this->html->MakeLink($link, $text, "$class fb_login_icon");
    }

}