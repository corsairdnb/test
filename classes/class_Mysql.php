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
        if ($where['id']) {
            // select questions for subject
            $q="SELECT $table_1.* FROM $table_1 LEFT JOIN $table_2 ON $table_1.id=$table_2.id WHERE $table_2.".$where['type']."_id=".$where['id']."";
        }
        elseif (!$related) {
            // select all
            $q="SELECT $cols FROM $table_1";
            $q.=($this->sql_table_exists($table_2)) ? " LEFT JOIN $table_2 ON $table_1.id=$table_2.id" : "";
        }
        else {
            // select related table
            $q="SELECT * FROM $table_1 ORDER BY `id`";
        }
        $resource=(mysql_num_rows($this->sql_query($q))>0)?$this->sql_query($q):false;
        if ($resource) while ($res[]=mysql_fetch_assoc($resource));
        return ($res)?$res:false;
    }

    protected function sql_get_data_values ($table, $id) {
        $this->sql_select_db ();
        $table = MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_".$table;
        $q="SELECT * FROM $table WHERE `id`='$id'";
        $resource=(mysql_num_rows($this->sql_query($q))>0)?$this->sql_query($q):false;
        if ($resource) while ($res[]=mysql_fetch_assoc($resource));
        else return false;
        return ($res) ? $res : false;
    }

    protected function sql_table_exists ($table) {
        $this->sql_select_db ();
        $q="Show tables from ".MYSQL_DB." like '$table'";
        return (mysql_num_rows($this->sql_query($q))>0)?true:false;
    }

    protected function sql_remove ($table,$id) {
        $this->sql_select_db ();
        $dependencies=array(
            "ts_data_answer"=>array(
                "type"=>"answer",
                "tables"=>array(
                    "depend"=>array(
                        "table"=>"ts_data_question_answer",
                        "column"=>"answer"
                    ),
                    "delete"=>array(
                        0=>"ts_rel_answer"
                    )
                )
            ),
            "ts_data_question"=>array(
                "type"=>"question",
                "tables"=>array(
                    "depend"=>array(
                        "table"=>"ts_data_test_question",
                        "column"=>"question"
                    ),
                    "delete"=>array(
                        0=>"ts_rel_question"
                    )
                )
            ),
            "ts_data_subject"=>array(
                "type"=>"subject",
                "tables"=>array(
                    "depend_by"=>array(
                        "tables"=>array(
                            0=>"ts_rel_answer",
                            1=>"ts_rel_question",
                            2=>"ts_rel_test"
                        ),
                        "column"=>"subject_id"
                    )
                )
            ),
            "ts_data_group"=>array(
                "type"=>"group",
                "tables"=>array(
                    "depend_by"=>array(
                        "tables"=>array(
                            0=>"ts_rel_user"
                        ),
                        "column"=>"group_id"
                    )
                )
            ),
            "ts_data_test"=>array(
                "type"=>"test",
                "tables"=>array(
                    "delete"=>array(
                        0=>"ts_data_test_question",
                        1=>"ts_rel_test"
                    )
                )
            )
        );
        $table_1 = MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_".$table;
        $q = "DELETE FROM $table_1 ";
        $allow = true;
        $plural = false;
        foreach ($dependencies as $key=>$dep) {
            $type = $dep["type"];
            if ($type == $table) {
                $tables = $dep["tables"];
                foreach ($tables as $key2=>$t) {
                    if ($key2=="depend") {
                        $q2 = "SELECT ".$t["column"]." FROM ".$t["table"];
                        $resource=(mysql_num_rows($this->sql_query($q2))>0)?$this->sql_query($q2):false;
                        if ($resource) {
                            while ($res[]=mysql_fetch_assoc($resource));
                            $result = explode(".",$res[0][$t["column"]]);
                            foreach ($result as $key3=>$val) {
                                if ($val == $id) {
                                    $allow = false;
                                }
                            }
                        }
                    }
                    elseif ($key2=="delete" && $allow && $t!="") {
                        $table_list = $table_1.",";
                        $and_list = $table_1.".id=".$id." AND ";
                        foreach ($t as $delete=>$del) {
                            $q4 = "SELECT * FROM $del WHERE id=$id";
                            if (mysql_num_rows($this->sql_query($q4))>0) {
                                $plural = true;
                                $table_list .= $del.",";
                                $and_list .= $del.".id=".$id." AND ";
                            }
                        }
                        $table_list = substr($table_list, 0, -1);
                        $and_list = substr($and_list, 0, -4);
                        $q = "DELETE $table_list FROM $table_list WHERE $and_list";
                    }
                    elseif ($key2=="depend_by") {
                        foreach ($t["tables"] as $key4=>$t2) {
                            $q3 = "SELECT ".$t["column"]." FROM ".$t2." WHERE ".$t["column"]."=".$id;
                            if (mysql_num_rows($this->sql_query($q3))>0) {
                                $allow = false;
                                break;
                            }
                        }
                    }
                }
                break;
            }
        }
        if (!$plural) $q .= "WHERE `id`=".$id;
        if ($allow) {
            return ($this->sql_query($q)>0) ? true : false;
        }
        else return false;
    }

}

?>