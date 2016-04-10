<?php 

class usuario_acessoModel extends \classes\Model\Model{
    public  $tabela = "usuario_acesso";
    public  $pkey   = 'cod';
    public function saveLog($logname,$cod_usuario,$cod_perfil,$action_str,$ip,$refer,$msg,$loggroup){
        $key    = $this->getCookieKey($cod_usuario);
        $gr     = array_shift($loggroup);
        $action = substr($action_str, 1, strlen($action_str));
        if($action == 'notificacao/notifycount/load'){return true;}
        $log = array(
            'logname'     => $logname,
            'cod_usuario' => $cod_usuario,
            'cod_perfil'  => $cod_perfil,
            'group0'      => $gr,
            'action'      => $action,
            'ip'          => $ip,
            'refer'       => $refer,
            'msg'         => $msg,
        );
        if($key !== ""){$log['key'] = $key;}
        
        $this->findGroups($log, $action, $loggroup);
        $this->findUtm($log);
        if(false === $this->db->Insert($this->tabela, $log)){
            $logname = "usuario/usuario/u$cod_usuario/$logname";
            \classes\Utils\Log::save('usuario/acesso/erro', "<hr/>Erro ao salvar log de usuário!");
            \classes\Utils\Log::save('usuario/acesso/erro', $this->db->getMessages());
            \classes\Utils\Log::save('usuario/acesso/erro', "$logname, '$cod_usuario','$cod_perfil','$action','$ip','$refer', '$msg';<hr/>");
            \classes\Utils\Log::save($logname, ",'$cod_usuario','$cod_perfil','$action','$ip','$refer', '$msg';");
        }
        return true;
    }
    
            public function getCookieKey($cod_usuario){
                $cookiename = "usuario_acesso_uid";
                $key        = \classes\Classes\cookie::getVar($cookiename);
                if($cod_usuario == 0){
                    if($key == ""){$key = genKey(24);}
                    \classes\Classes\cookie::setVar($cookiename, $key);
                }else{
                    if($key != ""){
                        $this->cookieTrackUser($cod_usuario, $key);
                        //$this->db->printSentenca();
                        //die('fooo');
                        $key = "";
                    }
                    \classes\Classes\cookie::destroy($cookiename);
                }
                //echoBr($key);
                return $key;
            }

                    private function cookieTrackUser($cod_usuario, $key){
                        $post = array('cod_usuario' => $cod_usuario, 'key' => "FUNC_NULL");
                        return parent::editar($key, $post, 'key');
                    }
    
            private function findGroups(&$log, $action, $loggroup){
                $this->captureGet($action, $loggroup);
                $groups = explode("/", $action);
                $i = 0;
                foreach($groups as $gr){
                    if(trim($gr) === ""){continue;}
                    $i++;
                    $log["group$i"] = $gr;
                    if($i == 7){break;}
                }
                $i = 7;
                foreach($loggroup as $gr){
                    if(trim($gr) === ""){continue;}
                    $i++;
                    $log["group$i"] = $gr;
                    if($i == 15)break;
                }
            }

                    private function captureGet(&$action, &$loggroup){
                        if(strstr($action, 'index.php') === false){return;}
                        $act = $action;
                        $e = explode("url=", $action);
                        $a = isset($e[1])?explode("&", $e[1]):array($action);
                        $action = urldecode($a[0]);

                        $e2 = explode("&", $act);
                        foreach($e2 as $nm){
                            if(strstr($nm, 'index.php') || strstr($nm, $action)){continue;}
                            $loggroup[] = $nm;
                        }
                    }
                    
            private function findUtm(&$array){
                if(!is_array($array)){return"";}
                $founded = false;
                $keys    = array('utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'utm_expid', 'utm_referrer');
                foreach($keys as $k){$array[$k] = "";}
                foreach($array as $key => $val){
                    if(strstr($val, "utm_") === false){continue;}
                    $e = explode('=', $val);
                    foreach($keys as $k){
                        if(true === $this->filterUtm($k, $key, $val, $e, $founded, $array)){break;}
                    }
                }
                return $founded;
            }
            
                    private function filterUtm($find, $key, $val, $e, &$founded, &$array){
                        if(strstr($val, $find) !== false){
                            if(!isset($e[1])){die("adsfasd");}
                            $array[$find] = $e[1];
                            $founded      = true;
                            $array[$key]  = "";
                            return true;
                        }
                        if(!isset($array[$find])){$array[$find] = "";}
                        return false;
                    }
    
    public function getChartData($qtd = 10, $cod_usuario = '') {
        $where = ($cod_usuario == "")?"1":"cod_usuario='$cod_usuario'";
        return $this->selecionar(array('cod','COUNT(*) as total', 'action'), "$where GROUP BY action", $qtd, 0, 'total DESC');
    }
    
    public function getChartDataUnique($qtd = 10, $cod_usuario = '') {
        //$where = "logname LIKE '%'";
        $where = ($cod_usuario == "")?"1":"cod_usuario='$cod_usuario'";
        $arr = array('cod','COUNT(DISTINCT cod_usuario) as total', 'action');
        return $this->selecionar($arr, "$where GROUP BY action", $qtd, 0, 'COUNT(*) DESC');
    }
    
    public function getChartErrorData($qtd = 10, $cod_usuario = '') {
        $where = "logname LIKE '%exception%'";
        if($cod_usuario != ""){
            $where.= " AND cod_usuario='$cod_usuario'";
        }
        $arr = array('cod', 'COUNT(*) as total', 'action');
        return $this->selecionar($arr, "$where GROUP BY action", $qtd, 0, 'total DESC');
    }
    
    public function getChartGroupData($grupos, $qtd = 10, $cod_usuario = ''){
        if(empty($grupos)) return array();
        foreach($grupos as $grupo){
            $where[] = "loggroup LIKE '%$grupo%'";
        }
        
        if($cod_usuario != ""){$where[] = "cod_usuario='$cod_usuario'";}
        $arr = array('cod','COUNT(*) as total', 'action');
        $where = implode(" AND ", $where);
        return $this->selecionar($arr, "$where GROUP BY group", $qtd, 0, 'total DESC');
    }
    
    public function getUserAccess(){
        return $this->getDailyAccess();
    }
    
    public function getLoginAccess(){
        return $this->getDailyAccess('',"cod_perfil != '0'");
    }
    
    
    public function getPerfilAccess(){
        return $this->getDailyAccess('cod_perfil', "cod_usuario != '0' AND cod_perfil != '0'");
    }
    
     public function getPluginAccess($plugin = '',$subplugin = '',$page = ''){
        $pl = '';
        $pl.= ($plugin == '')?'':"AND group1 = '$plugin'";
        $pl.= ($subplugin == '')?'':"AND group2 = '$subplugin'";
        $pl.= ($page == '')?'':"AND group3 = '$page'";
        return $this->getDailyAccess('', "cod_usuario != '0' AND cod_perfil != '0' $pl");
    }
    
    public function getLastAccess($where){
        $res = $this->selecionar(array(),"$where");
        $count = count($res);
        return array('Descrição'=>'Acesso','Quantidade'=>$count);
    }
    
    public function getLastActionAccess($where){
        $res = $this->selecionar(array('*',"count(*) as count"),"$where");
        foreach($res as $for){
           $action = $for['group1'].'/'.$for['group2'].'/'.$for['group3'];
           $action = "<a href='".URL.$action."'>$action</a>";
           $out[]  = array('cod'=>$for['cod'], 'Descrição'=>$action,'Quantidade'=>$for['count']);
        }
        usort($out, function($a, $b){return $a["Quantidade"] <= $b["Quantidade"];});
        return $out;
    }
    
    
    private function getDailyAccess($group = "", $where = ""){
        $where = ($where === "")?"":" AND ($where)";
        $gr    = ($group === "")?"":", $group";
        $arr = array(
            "DATE(data) as data", 
            "COUNT(DISTINCT(ip)) as ip", 
            "COUNT(DISTINCT(cod_usuario)) as cod_usuario", 
        );
        if($group !== ""){
            $arr[] = $group;
        }
        return $this->selecionar(
                $arr,
                "data != '0000-00-00 00:00:00' AND data != '0000-00-00' $where GROUP BY DATE(data)$gr", "", "", 
                "data DESC"
         );
    }
    
    public function migrateGroups(){
        $arr = $this->selecionar(array());
        $out = array();
        foreach($arr as $a){
            $temp     = $a;
            $i        = 0;
            $groups   = explode('/',$a['action']);
            foreach($groups as $group){
                if(trim($group) == ""){continue;}
                $i++;
                if($i == 10){break;}
                $temp["group$i"] = $group;
            }
            $out[] = $temp;
        }
        return $this->importDataFromArray($out);
    }
    
    public function migrateUtm(){
        $limit = 1000;
        $where = $this->getWhere();
        $count = $this->getCount($where);
        $pages = ceil($count/$limit);
        $out   = array();
        $keys  = array('cod', 'group8','group9','group10','group11','group12','group13','group14','group15');
        
        $i     = 0;
        while($i < $pages){
            $arr = $this->selecionar($keys, $where, $limit, $i*$limit,'cod ASC');
            foreach($arr as $a){
                if(false == $this->findUtm($a)){continue;}
                $out[] = $a;
            }
            if(count($out) < $limit){continue;}
            if(false == $this->doImportData($out, $limit)){
                $this->db->printSentenca();
                die($this->db->getErrorMessage());
            }
        }
        if(empty($out)){return;}
        return $this->importDataFromArray($out);
    }
    
            private function doImportData($out, $limit){
                if(count($out) < $limit){return true;}
                return $this->importDataFromArray($out);
            }
    
            private function getWhere(){
                $wh = array();
                $i  = 8;
                while($i <= 15){
                    $wh[] = "group$i LIKE '%utm_%'";
                    $i++;
                }
                $where = implode($wh, " OR ");
                return "group8 != '' AND ($where)";
            }
    
    public function dropitem($action) {
        $cod_usuario = usuario_loginModel::CodUsuario();
        return $this->db->Delete($this->tabela, "action='$action' AND cod_usuario='$cod_usuario'");
    }
    
    public function globaldrop($action){
        return $this->db->Delete($this->tabela, "action='$action'");
    }
}