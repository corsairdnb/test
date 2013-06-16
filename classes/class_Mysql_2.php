<?php

class Mysql
{
    private $link;
    public function Mysql () {}
    /* Соединение с сервером БД */
    private function sql_connect() {
        global $_MYSQL_CONNETCION_ESTABLISHED;
        $this->link=mysql_connect(MYSQL_SERVER,MYSQL_LOGIN,MYSQL_PASS)
            or die("Could not connect to database");
        mysql_query(MYSQL_INIT_CONNECT);
        mysql_query("SET group_concat_max_len=1048576");
        $_MYSQL_CONNETCION_ESTABLISHED=$this->link;
        return $_MYSQL_CONNETCION_ESTABLISHED;
    }
    /* Выбор БД */
    private function sql_select_db () {
        $connection=$this->sql_connect();
        if ($connection) {
            mysql_select_db(MYSQL_DB,$connection) or die ("database error");
        }
        else {
            die ("not connected to database ".MYSQL_DB);
        }
    }
    /* Запрос к БД */
    public function sql_query($q) {
        return ($res=mysql_query($q))?$res:false;
    }
    /* Создание записи */
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
    /* Обновление записи */
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
    /* Выбор записей */
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
    /* Проверка существования таблицы */
    protected function sql_table_exists ($table) {
        $this->sql_select_db ();
        $q="Show tables from ".MYSQL_DB." like '$table'";
        return (mysql_num_rows($this->sql_query($q))>0)?true:false;
    }
    /* Удаление записей */
    protected function sql_remove ($table,$id) {
        $this->sql_select_db ();
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