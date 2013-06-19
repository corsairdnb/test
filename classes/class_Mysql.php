<?php

class Mysql
{
    private $link;
    /*public $q;
    protected $res;
    protected $resource;*/

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

    public function sql_query_get ($q) {
        $this->sql_select_db ();
        $resource=(mysql_num_rows(mysql_query($q))>0)?mysql_query($q):false;
        if ($resource) while ($res[]=mysql_fetch_assoc($resource));
        else return false;
        return ($res) ? $res : false;
    }

    public function sql_query_get_num ($q) {
        $this->sql_select_db ();
        return mysql_num_rows(mysql_query($q));
    }

    public function sql_query_create ($q) {
        return ($this->sql_query($q))? : false;
    }

    protected function sql_create ($table,$cols,$vals,$id,$test_id) {
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
        if ($table=="group_test") {
            $cols2 = $cols.",`id`";
            $vals2 = $vals.", '".$id."'";
            $str=$this->sql_make_upd_string( explode(",",$cols), explode(",",$vals) );
            $q="INSERT INTO ".MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_$table ($cols2) VALUES ($vals2) ON DUPLICATE KEY UPDATE $str";
            $this->sql_query($q);

            $q2 = "INSERT INTO `ts_data_user_test` (`id`,`group_id`,`test_id`)
                    SELECT `ts_rel_user`.id AS id, `ts_rel_user`.group_id AS group_id, `ts_data_group_test`.test_id
                    FROM `ts_rel_user`, `ts_data_group_test`
                    WHERE `ts_rel_user`.group_id = $id
                    AND `ts_data_group_test`.id = $id";
            $this->sql_query($q2);

            $q3 = "SELECT id FROM `ts_data_user_test` WHERE group_id=$id AND test_id=$test_id";
            $resource=(mysql_num_rows($this->sql_query($q3))>0)?$this->sql_query($q3):false;
            if ($resource) while ($res[]=mysql_fetch_assoc($resource));
            foreach ($res as $k=>$val) {
                $key=uniqid(rand(), true);
                $key=substr($key,0,8);
                if ($val['id']) {
                    $q4 = "UPDATE `ts_data_user_test` SET `key`='$key' WHERE `id`=".$val['id'];
                    $this->sql_query($q4);
                    $q5 = "INSERT `ts_data_user_test_info` SET `key`='$key'";
                    $this->sql_query($q5);
                    //echo $q4;
                }
            }
            return true;
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
        //echo $q;
        if ($result = $this->sql_query($q)) {
            $query1 = true;
        }
        $val1=mysql_result($this->sql_query("select last_insert_id();"),0);
        $val2=$related[key($related)];
        $q2="INSERT INTO `".MYSQL_PREFIX."_".MYSQL_PREFIX_REL."_$table` (`id`,$rel2) VALUES ($val1,$val2)";
        //echo $q2;
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
        $table_1 = MYSQL_PREFIX."_".MYSQL_PREFIX_DATA."_".$table;
        if ($table=="group_test") $q="SELECT * FROM $table_1 WHERE `test_id`='$id'";
            else $q="SELECT * FROM $table_1 WHERE `id`='$id'";
        //echo $q;
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
            ),
            "ts_data_user"=>array(
                "type"=>"user",
                "tables"=>array(
                    "depend_by"=>array(
                        "tables"=>array(
                            0=>"ts_data_user_test"
                        ),
                        "column"=>"id"
                    ),
                    "delete"=>array(
                        0=>"ts_rel_user"
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

    public function sql_get_keys () {
        $this->sql_select_db ();
        $q = "SELECT `ts_data_user_test_info`.`time_end`, `ts_data_user_test`.`key`, `ts_data_user_test`.`test_id`, `ts_data_user_test`.`id`, `ts_data_user`.name, `ts_data_test`.`name` AS test_name FROM `ts_data_user_test_info`, `ts_data_user_test`, `ts_data_user`, `ts_data_test`
        WHERE (`ts_data_user_test_info`.`time_end`>NOW() OR `ts_data_user_test_info`.`time_end`=0)
        AND `ts_data_user_test`.`key` = `ts_data_user_test_info`.`key`
        AND `ts_data_user`.`id` = `ts_data_user_test`.`id`
        AND `ts_data_test`.`id` = `ts_data_user_test`.`test_id`
        AND `ts_data_user_test`.`complete` = '0'
        ";
        //echo $q;
        $resource=(mysql_num_rows($this->sql_query($q))>0)?$this->sql_query($q):false;
        if ($resource) while ($res[]=mysql_fetch_assoc($resource));
        else return false;
        return (count($res)) ? $res : false;
    }

    public function sql_test_isActive ($key, $test) {
        $this->sql_select_db ();
        $res = array();
        $session = $_SESSION["sid"];
        $q="SELECT `ts_data_user_test`.*, `ts_data_test`.duration FROM `ts_data_user_test`
        LEFT JOIN `ts_data_test` ON `ts_data_test`.id='$test'
        WHERE `ts_data_user_test`.`key`='$key' AND `ts_data_user_test`.`test_id`='$test'
        AND `ts_data_user_test`.`complete` = '0'
        ";
        $resource=(mysql_num_rows($this->sql_query($q))>0)?$this->sql_query($q):false;
        if ($resource) while ($res[]=mysql_fetch_assoc($resource));
        if (count($res)) {
            $duration = $res[0]["duration"];
            $q="UPDATE `ts_data_user_test` SET `session`='$session' WHERE `key`='$key'";
            if ($res[0]["session"]!="") {
                return ($this->sql_query($q))? : false;
            }
            else $this->sql_query($q);
            $q="UPDATE `ts_data_user_test_info` SET `time_start`=NULL, `time_end`=DATE_ADD(`time_start`, INTERVAL ".$duration." MINUTE) WHERE `key`='$key'";
            return ($this->sql_query($q))? : false;
        }
        return false;
    }

    public function sql_get_reports () {
        $this->sql_select_db ();
        $result = array();
        $keys = array();

        $q = "  SELECT `ts_data_user_answer`.*, `ts_data_test`.name AS test_name, `ts_data_test`.num_questions, `ts_data_user`.name AS user_name
                FROM `ts_data_user_answer`, `ts_data_test`, `ts_data_user`
                WHERE  `ts_data_user_answer`.test_id = `ts_data_test`.id
                AND `ts_data_user_answer`.user_id = `ts_data_user`.id
                ";
        $ar = $this->sql_query_get($q);

        if ($ar) {
            foreach ($ar as $key=>$val) {
                if ($val) {
                    $keys[]=$ar[$key]["key"];
                }
            }
            $keys = array_unique($keys);
            foreach ($keys as $k=>$v) {
                $result[$v]=array();
            }
            foreach ($ar as $key=>$val) {
                if ($val) {
                    foreach ($result as $k=>$v) {
                        if ($ar[$key]["key"] == $k) {
                            $result[$k][] = $ar[$key];
                        }
                    }
                }
            }

            foreach ($result as $a=>$b) {
                foreach ($b as $k=>$v) {
                    if ($v) {
                        $question = $v["question_id"];
                        $answer = $v["answer_id"];
                        $q2 = "SELECT * FROM `ts_data_question_answer` WHERE `id`='$question'";
                        $x = $this->sql_query_get($q2);
                        $answers = explode(".",$x[0]["answer"]);
                        $answers = array_filter( $answers, function($el) {return !empty($el);} );
                        foreach ($answers as $k2=>$v2) {
                            if ($answer == $v2 && $answer == $x[0]["true"]) {
                                $result[$a][$k]["true"]=true;
                            }
                        }
                    }
                }
            }

        }

        return ($ar) ? $result : false;
    }

}

?>