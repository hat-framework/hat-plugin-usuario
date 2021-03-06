<?php 

class usuario_referenciaModel extends \classes\Model\Model{
    private $cookiename = 'urf';
    public  $tabela     = "usuario_referencia";
    public  $pkey       = array('cod_referencia','cod_usuario');
    public  $cookietime = '2592000';//1 mês
    public function associate($cod_referencia, $cod_usuario){
        if($cod_referencia == $cod_usuario){
            throw new \classes\Exceptions\InvalidArgumentException("O usuário não pode referenciar a ele mesmo!");
        }
        $item = $this->selecionar(array(), "cod_usuario='$cod_usuario'");
        if(!empty($item)){
            if($item[0]['cod_referencia'] == $cod_referencia){return true;}
            return $this->setErrorMessage("Este usuário já foi convidado no sistema por outro usuário");
        }
        
        $bool = $this->inserir(array('cod_referencia' => $cod_referencia,'cod_usuario'    => $cod_usuario));
        if($bool !== false){
            classes\Classes\cookie::destroy($this->cookiename);
            classes\Classes\session::destroy($this->cookiename);
        }
        return ($bool !== false);
    }
    
    public function createCookie($cod_referrer){
        classes\Classes\cookie::create($this->cookiename, $this->cookietime);
        classes\Classes\cookie::setVar($this->cookiename, $cod_referrer);
        classes\Classes\session::setVar($this->cookiename, $cod_referrer);
    }
    
    public function getCookie(){
        $data = classes\Classes\cookie::getVar($this->cookiename);
        return (trim($data) !== "")?$data:classes\Classes\session::getVar($this->cookiename);
    }
    
    public function getReferrers ($cod){
        $this->join('usuario/login', 'cod_referencia','cod_usuario', "LEFT");
        return $this->selecionar(
                array('user_name as cod_referencia',"$this->tabela.cod_usuario as __cod_usuario", 'dtindicacao', "cod_referencia as __cod_referencia"), 
                 "$this->tabela.cod_usuario='$cod'"
        );
    }
    
    private $limit = 10;
    public function getMyInvitations($cod, $page = 0){
        //init vars
        $pg     = (!is_numeric($page) || $page <= 0)? 0:$page;
        $offset = ($pg * $this->limit);
        $where  = "$this->tabela.cod_referencia='$cod'";
        
        //getCount
        $this->join('usuario/login', 'cod_usuario','cod_usuario', "LEFT");
        $this->temp_count = $this->getCount($where);
        
        //selecionar
        $this->join('usuario/login', 'cod_usuario','cod_usuario', "LEFT");
        return $this->selecionar(
            array("user_name as cod_usuario", 'email',"$this->tabela.cod_usuario as __cod_usuario", 'dtindicacao', "cod_referencia as __cod_referencia"),
            $where, $this->limit, $offset, "dtindicacao DESC"
        );
    }
    
    public function getMyInvitationsPages($cod, $page){
        $this->LoadResource('html', 'html');
        if(!isset($this->temp_count)){
            $this->getMyInvitations($cod);
        }
        
        if(!is_numeric($this->limit) || $this->limit <= 0){$this->limit = 1;}
        $pages = ceil($this->temp_count/$this->limit);
        if($pages > $this->limit){$pages = $this->limit;}
        if($pages <= 1){return;}
        
        $p     = 0;
        $out   = "<div><span>Página ".($page+1)."</span><br/><ul class='pagination'>";
        while($p < $pages){
            $link = ($p == $page)?"#":$this->html->getLink("config/group/form/pessoal/pessoal_referrer/&widget=referencias&widget_page=".$p);
            $active = ($p == $page)?'active':"";
            $out .= "<li class='$active'><a href='$link'>".($p+1)."</a></li>";
            $p++;
        }
        $out   .= '</ul></div>';
        \classes\Classes\EventTube::addEvent("paginate_usuario_referencia", $out);
    }
}