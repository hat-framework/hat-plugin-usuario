<?php 
 use classes\Controller\CController;
class promocodController extends CController{
    public $model_name = "usuario/promocod";
    
    public function index($display = true, $link = "") {
        parent::index($display, $link == ""?"admin/auto/areacliente/grid":$link);
    }
    
    public function promouser(){
        $this->exec(__FUNCTION__, true);
    }
    
            private function exec($function, $arr = false){
                $action           = array_shift($this->vars);
                if(trim($action) === ""){$action = 'index';}
                $this->model_name        = "$this->model_name/$function";
                $this->LoadModel($this->model_name, 'model');
                if(!method_exists($this, $action)){throw new \classes\Exceptions\PageNotFoundException();}
                if(in_array($action, array('show','edit','apagar'))){
                    $cod                     = $this->getCode($arr);
                    $this->item              = $this->model->getItem($this->cod);
                    $this->redirect_link     = array('usertag' => ($cod == "")?"$this->model_name/index":"usuario/pomocod/show/$cod");
                    $this->redirect_droplink = "$this->model_name/index";
                    $this->registerVar('cod' , $this->cod);
                    $this->registerVar('item', $this->item);
                }else{
                    $page       = array_shift($this->vars);
                    $this->item = $this->model->paginate($page);
                }
                $this->registerVar('model_name', $this->model_name);
                $this->registerVar('component' , $this->model_name);
                $this->$action();
            }
            
                    private function getCode($arr){
                        $cod       = "";
                        $this->cod = array_shift($this->vars);
                        if($arr === true){
                            $this->cod = array($this->cod);
                            $cod       = array_shift($this->vars);
                            $this->cod[] = $cod;
                        }
                        return $cod;
                    }
                    
    public function aderir(){
        $coduser = usuario_loginModel::CodUsuario();
        $promocod  = $this->getVarsParam(0, "Você deve informar o código de promoção");
        $this->model->createCookie($promocod);
        if($coduser != 0){
            if(false === $this->LoadModel('usuario/promocod/promouser', 'puser')->attachPromocod($promocod, $coduser)){
                \classes\Utils\Log::save('usuario/promocod/erro', $this->puser->getErrorMessage());
            }
        }
        $url = $this->getRedirectUrl($promocod,true, true);
        Redirect($url);      
    }
    
            private function getRedirectUrl($codref){
                $args   = $this->prepareArgs($codref);
                $this->LoadResource('html', 'html');
                if(defined('USUARIO_PROMO_VIEW') && USUARIO_PROMO_VIEW !== ""){
                    return $this->html->getLink(USUARIO_PROMO_VIEW."&$args", true, true);
                }
                return $this->html->getLink("", true, true)."?$args";
            }
            
            
                    private function prepareArgs($promocod){
                        $get = $_GET;
                        if(isset($get['url'])){unset($get['url']);}
                        if(!isset($get['utm_source'])){
                            $get['utm_source']   = "promo";
                            $get['utm_medium']   = "recovery_site";
                            $get['utm_campaign'] = $promocod;
                        }
                        
                        $args = array();
                        foreach($get as $key => $val){
                            $args[] = "$key=$val";
                        }
                        return implode("&",$args);
                    }
}