<?php

class acessoMigrate extends classes\Classes\Object{
    
    public function __construct() {
        $this->LoadModel('usuario/acesso', 'acc');
    }
    
    public function migrateUtm(){
        $limit = 1000;
        $where = $this->getWhere();
        $count = $this->acc->getCount($where);
        $pages = ceil($count/$limit);
        $out   = array();
        $keys  = array('cod', 'group8','group9','group10','group11','group12','group13','group14','group15');
        
        $i     = 0;
        while($i < $pages){
            $arr = $this->acc->selecionar($keys, $where, $limit, $i*$limit,'cod ASC');
            $i++;
            foreach($arr as $a){
                if(false == $this->findUtm($a)){continue;}
                $out[] = $a;
            }
            if(count($out) < $limit){continue;}
            $this->doImportData($out, $limit);
        }
        return $this->doImportData($out, 0);
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
    
    
            private function doImportData($out, $limit){
                if((count($out) < $limit && $limit > 0) || empty($out)){return true;}
                if(false !== $this->acc->importDataFromArray($out)){return true;}
                if(!usuario_loginModel::IsWebmaster()){return true;}
                $this->acc->db->printSentenca();
                die($this->acc->db->getErrorMessage());
            }
            
    public function migrateGroups(){
        $arr = $this->acc->selecionar(array());
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
        return $this->acc->importDataFromArray($out);
    }
    
}