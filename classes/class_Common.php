<?php

class Common extends Mysql implements Data {

    public function create ($params) {
        $table=$params["table"];
        $cols=$params["cols"];
        $vals=$params["vals"];
        if (
            $this->sql_create($table,$cols,$vals)
        ) return true; else return false;
    }

    public function update ($params) {
        $table=$params["table"];
        $cols=$params["cols"];
        $vals=$params["vals"];
        $id=$params["where"];
        if (
            $this->sql_update($table,$cols,$vals,$id)
        ) return true; else return false;
    }

    public function get ($params) {
        $table=$params["table"];
        $cols="*";
        $where=$params["where"];
        if ($result=$this->sql_get_data($table,$cols,$where)) {
            rsort($result);
            reset($result);
            return $result;
        } else return false;
    }

    public function remove ($params) {
        $table=$params["table"];
        $where=$params["where"];
        return ($this->sql_remove($table,$where))?true:false;
    }

}
