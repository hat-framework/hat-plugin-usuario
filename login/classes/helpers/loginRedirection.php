<?php

use classes\Classes\session;
class loginRedirection extends classes\Classes\Object{
    
    private $welcomePage        = 'index/index/aviso';
    private $login_default_page = "usuario/login/logado";
    public function __construct() {
        $this->LoadModel('usuario/perfil', 'perf');
        $this->LoadModel("plugins/plug", "plug");
    }
    
    public function TryRedirection($first_login = false,$login_first = false){
        $refer = $this->getRefer();
        $this->firstLogin($login_first);
        $page  = $this->getRedirectionPage($refer, $first_login);
        $this->doRedirection($page);
        return false;
    }
    
            private function getRefer(){
                $refer = "";
                if(isset($_GET['refer'])) {$refer = $_GET['refer'];}
                if(session::exists('refer')){
                    if($refer == "") {$refer = session::getVar('refer');}
                    session::destroy('refer');
                }
                if($refer === "" || $refer == URL){return "";}
                
                //decodifica as urls codificadas
                $ref = base64_decode($refer);
                $r   = $refer;
                if( base64_encode($ref) == $refer){$r = $ref;}
                return $r;
            }
            
            private function firstLogin($login_first){
                if($login_first == false){return;}

                //se não tem página de boas vindas
                if(!$this->perf->hasPermission($this->welcomePage)){return;}

                //redireciona o usuário para a página de boas vindas
                $this->doRedirection($this->welcomePage);
            }
            
            private function getRedirectionPage($refer, $first_login){
                if($refer != "" || !$first_login){return $refer;}

                //recupera o plugin padrão
                $default = $this->plug->getDefault();
                if($default === 'usuario'){return $this->login_default_page;}
                
                //retorna a página padrão
                try{
                    $this->plug->IsAvaible($default); 
                    $default_page = "$default/index/index";
                    return (!$this->perf->hasPermission($default_page))?$this->login_default_page:"$default/index/index";
                }
                catch (\classes\Exceptions\PageNotFoundException $pne){return $this->login_default_page;}
            }

            private function doRedirection($page){
                if($page == ""){return;}
                $arr['status']  = "1";
                $arr['success'] = "Login efetuado com sucesso! Autenticando... ";
                SRedirect($page, 0, $arr);
            }
}