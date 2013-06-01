<?php

class Mysql
{
    private $link;
    public $q;
    protected $res;
    protected $resource;

    public function Mysql () {

    }

    private function sql_connect() {
        global $_MYSQL_CONNETCION_ESTABLISHED;
        $this->link=mysql_connect(MYSQL_SERVER,MYSQL_LOGIN,MYSQL_PASS)
            or die("Could not connect to database");
        mysql_query(MYSQL_INIT_CONNECT);
        mysql_query("SET group_concat_max_len=1048576");
        $_MYSQL_CONNETCION_ESTABLISHED=$this->link;
        return $_MYSQL_CONNETCION_ESTABLISHED;
    }

    private function sql_select_db () {
        $connection=$this->sql_connect();
        if ($connection) {
            mysql_select_db(MYSQL_DB,$connection) or die ("database error");
        }
        else {
            die ("not connected to database ".MYSQL_DB);
        }
    }

    public function sql_query($q) {
        return ($res=mysql_query($q))?$res:false;
    }

    protected function sql_create ($table,$cols,$vals) {
        $this->sql_select_db ();
        $q="INSERT INTO ".MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_$table ($cols) VALUES ($vals)";
        echo $q;
        return ($this->sql_query($q))? : false;
    }

    protected function sql_update ($table,$cols,$vals,$id) {
        $this->sql_select_db ();
        $str="";
        $i=0;
        $cols=explode(",",$cols);
        $vals=explode(",",$vals);
        foreach ($cols as $col=>$val) {
            $str.=$val."=".$vals[$i].",";
            $i++;
        }
        $str[strlen($str)-1]=" ";
        $q="UPDATE `".MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_$table` SET $str WHERE `id`='$id'";
        return ($this->sql_query($q))? : false;
    }

    protected function sql_get_data ($table,$cols,$where) {
        $this->sql_select_db ();
        $q="SELECT $cols FROM `".MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_$table`";
        (!empty($where))?$q.=" WHERE $where":"";
        $q.=" ORDER BY `id`";
        $resource=(mysql_num_rows($this->sql_query($q))>0)?$this->sql_query($q):false;
        if ($resource) while ($res[]=mysql_fetch_assoc($resource));
        //unset($res[count($res)-1]);
        return ($res)?$res:false;
    }

    protected function sql_remove ($table,$where) {
        $this->sql_select_db ();
        $q="DELETE FROM `".MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_$table`";
        (!empty($where))?$q.=" WHERE $where":"";
        return ($this->sql_query($q)>0)?true:false;
    }

}

?>