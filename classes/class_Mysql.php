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

    protected function sql_create ($table,$cols,$vals,$id) {
        $this->sql_select_db ();
        if ($id) {
            $cols2 = $cols.",`id`";
            $vals2 = $vals.", '".$id."'";
            $str=$this->sql_make_upd_string( explode(",",$cols), explode(",",$vals) );
            $q="INSERT INTO ".MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_$table ($cols2) VALUES ($vals2) ";
            $q.= "ON DUPLICATE KEY UPDATE $str";
        } else {
            $q="INSERT INTO ".MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_$table ($cols) VALUES ($vals)";
        }
        return ($this->sql_query($q))? : false;
    }

    protected function sql_make_upd_string ($ar1,$ar2) {
        $str="";
        foreach ($ar1 as $key1=>$val1) {
            $str.=$val1."=".$ar2[$key1].",";
        }
        $str = substr($str, 0, -1);
        return $str;
    }

    protected function sql_create_related ($table,$cols,$vals,$related) {
        $this->sql_select_db ();
        $rel2 = key($related);
        $q="INSERT INTO ".MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_$table ($cols) VALUES ($vals)";
        if ($result = $this->sql_query($q)) {
            $query1 = true;
        }
        $val1=mysql_result($this->sql_query("select last_insert_id();"),0);
        $val2=$related[key($related)];
        $q2="INSERT INTO ".MYSQL_PREFIX."_".MYSQL_PREFIX_REL."_$table (`id`,$rel2) VALUES ($val1,$val2)";
        if ($this->sql_query($q2)) {
            $query2 = true;
        }
        return ($query1 && $query2)? : false;
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

    protected function sql_get_data ($table,$cols,$where,$related) {
        $this->sql_select_db ();
        $table_1 = MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_".$table;
        $table_2 = MYSQL_PREFIX."_".MYSQL_PREFIX_REL."_".$table;
        //var_dump($where);
        if ($where['id']) {
            // select questions for subject
            $q="SELECT $table_1.* FROM $table_1 LEFT JOIN $table_2 ON $table_1.id=$table_2.id WHERE $table_2.".$where['type']."_id=".$where['id']."";
        }
        elseif (!$related) {
            // select all
            $q="SELECT $cols FROM $table_1";
            //(!empty($where))?$q.=" WHERE $where":"";
            //$q.=" ORDER BY `id`";
            $q.=($this->sql_table_exists($table_2)) ? " LEFT JOIN $table_2 ON $table_1.id=$table_2.id" : "";
            //echo $q;
        }
        else {
            // select related table
            $q="SELECT * FROM $table_1 ORDER BY `id`";
            //echo $q;
        }
        $resource=(mysql_num_rows($this->sql_query($q))>0)?$this->sql_query($q):false;
        if ($resource) while ($res[]=mysql_fetch_assoc($resource));
        return ($res)?$res:false;
    }

    protected function sql_table_exists ($table) {
        $this->sql_select_db ();
        $q="Show tables from ".MYSQL_DB." like '$table'";
        //echo $q;
        return (mysql_num_rows($this->sql_query($q))>0)?true:false;
    }

    protected function sql_remove ($table,$where) {
        $this->sql_select_db ();
        $q="DELETE FROM `".MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_$table`";
        (!empty($where))?$q.=" WHERE $where":"";
        return ($this->sql_query($q)>0)?true:false;
    }

}

?>