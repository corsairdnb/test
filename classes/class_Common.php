<?php

class Common extends Mysql {

    public function create ($params) {
        $table=$params["table"];
        $cols=$params["cols"];
        $vals=$params["vals"];
        $related=$params["related"];
        $id=$params["id"];
        $test_id=$params["test_id"];
        if ($related) {
            if (
                $this->sql_create_related($table,$cols,$vals,$related)
            ) return true; else return false;
        }
        else if (
            $this->sql_create($table,$cols,$vals,$id,$test_id)
        ) return true; else return false;
    }

    public function update ($params) {
        $table=$params["table"];
        $cols=$params["cols"];
        $vals=$params["vals"];
        $id=$params["update"];
        if (
            $this->sql_update($table,$cols,$vals,$id)
        ) return true; else return false;
    }

    public function get ($params) {
        $table=$params["table"];
        $cols="*";
        $where=$params["where"];
        $related=$params["rel"];
        if (!$params["datafrom"]) {
            if ($result=$this->sql_get_data($table,$cols,$where,$related)) {
                rsort($result);
                reset($result);
                return $result;
            } else return false;
        }
        else {
            if ($data=$this->sql_get_data($table,$cols,$where,$related)) {
                rsort($data);
                reset($data);
            }
            $data_values=$this->sql_get_data_values($params["datafrom"]["table"],$params["datafrom"]["id"]);
            if ($data) {
                return array("data"=>$data, "data_values"=>$data_values);
            } else return false;
        }
    }

    public function remove ($params) {
        $table=$params["table"];
        $id=$params["id"];
        return ($this->sql_remove($table,$id))?true:false;
    }

    public function getKeys () {
        if ($result=$this->sql_get_keys()) {
            return $result;
        } else return false;
    }

    // add to $ar_1 items from $ar_2 if $by is found in $ar_2
    // $prefix needed for $ar_1 keys
    // $keys is list of needed items
    protected function splitByKeys ($ar_1, $ar_2, $by, $prefix, $keys) {
        $result = array();
        foreach ($ar_1 as $key_1=>$val_1) {
            if (is_array($val_1) && count($val_1)>0) {
                $result[$key_1] = $val_1;
                foreach ($ar_2 as $key_2=>$val_2) {
                    if (is_array($val_2) && count($val_2)>0) {
                        if ($val_1[$by] == $val_2['id']) {
                            foreach ($keys as $key=>$item) {
                                $result[$key_1][$prefix."_".$item]=$val_2[$item];
                            }
                        }
                    }
                }
            }

        }
        return $result;
    }

}
