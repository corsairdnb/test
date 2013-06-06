<?php

class Common extends Mysql {

    public function create ($params) {
        $table=$params["table"];
        $cols=$params["cols"];
        $vals=$params["vals"];
        $related=$params["related"];
        $id=$params["id"];
        if ($related) {
            if (
                $this->sql_create_related($table,$cols,$vals,$related)
            ) return true; else return false;
        }
        else if (
            $this->sql_create($table,$cols,$vals,$id)
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

}
