<?php

class User extends Common {

    public $data;

    public function User () {
        $sid = $_SESSION["sid"];
        $this->data = $this->sql_query_get("
            SELECT *, `ts_data_group`.name AS group_name, `ts_data_user`.name AS user_name, `ts_data_user`.id AS user_id, `ts_data_test`.name AS test_name, `ts_rel_test`.id AS tid, `ts_rel_test`.subject_id, `ts_data_subject`.name AS subject_name
            FROM (
            SELECT * FROM `ts_data_user_test`
            WHERE `ts_data_user_test`.session = '".$sid."' AND `ts_data_user_test`.complete=0
            ) AS q
            , `ts_data_group`, `ts_data_user`, `ts_data_test`, `ts_data_user_test_info`, `ts_rel_test`, `ts_data_subject`
            WHERE `ts_data_user`.id = q.id
            AND `ts_data_test`.id = q.test_id
            AND `ts_data_user_test_info`.key = q.key
            AND `ts_data_group`.id = q.group_id
            AND `ts_rel_test`.id = q.test_id
            AND `ts_rel_test`.subject_id = `ts_data_subject`.id
        ");
        $this->data = $this->data[0];

    }

    public function getQuestionList() {
        $q = "SELECT * FROM `ts_data_test_question` WHERE `id`='".$this->data['test_id']."' LIMIT 1";
        $ar = $this->sql_query_get($q);
        $questions = explode(".", $ar[0]['question']);
        $questions = array_filter( $questions, function($el) {return !empty($el);} );
        shuffle($questions);
        return json_encode($questions);
    }

    public function getQuestion() {
        $q = "SELECT * FROM `ts_data_test_question` WHERE `id` = '".$this->data['test_id']."'";
        $ar = $this->sql_query_get($q);
        $questions = explode(".",$ar[0]['question']);
        $questions = array_filter( $questions, function($el) {return !empty($el);} );
        shuffle($questions);
        for ($i=0; $i<count($questions); $i++) {
            $q = $this->sql_query_get("
                SELECT * FROM `ts_data_user_answer`
                WHERE `test_id` = '".$this->data['test_id']."'
                AND `question_id` = '".$questions[$i]."'
                AND `test_id` = '".$this->data['test_id']."'
                AND `user_id` = '".$this->data['user_id']."'
            ");
            if (!$q) break;
        }
        return $questions[$i];
    }

    public function completeTest() {
        $q = "UPDATE `ts_data_user_test` SET `complete`='1'
        WHERE `test_id` = '".$this->data['test_id']."' AND `id` = '".$this->data['user_id']."'";
        $this->sql_query_create($q);

        $q2 = "SELECT `id` FROM `ts_data_user_test` WHERE `test_id` = '".$this->data['test_id']."' AND `complete`='0'";
        if (!$this->sql_query_get($q2)) {
            $this->deleteTest();
        }

        // write answers
        foreach ($_POST as $key=>$val) {
            $ar[$key]=$val;
        }
        $test_id = $this->data['test_id'];
        $user_id = $this->data['user_id'];
        $k = $this->data['key'];
        foreach ($ar as $key=>$val) {
            $q = "INSERT INTO `ts_data_user_answer`
            (`test_id`,`user_id`,`question_id`,`answer_id`,`key`)
            VALUES ('$test_id','$user_id','$key','$val','$k')
            ";
            $this->sql_query_create($q);
        }
    }

    public function deleteTest() {
        $q = "DELETE FROM `ts_data_user_test` WHERE `test_id` = '".$this->data['test_id']."'";
        $this->sql_query_create($q);

        $q = "DELETE FROM `ts_data_group_test`
        WHERE `test_id` = '".$this->data['test_id']."' AND `id` = '".$this->data['group_id']."'";
        $this->sql_query_create($q);
    }

    public function getTestResult() {
        $test_id = $this->data['test_id'];
        $user_id = $this->data['user_id'];
        $key = $this->data['key'];
        $result = array();

        $q = "  SELECT `ts_data_user_answer`.*
                FROM `ts_data_user_answer`
                WHERE  `ts_data_user_answer`.test_id = '$test_id'
                AND `ts_data_user_answer`.user_id = '$user_id'
                AND `ts_data_user_answer`.key = '$key'
                ";
        $ar = $this->sql_query_get($q);

        $counter = 0;
        foreach ($ar as $k=>$v) {
            if ($v) {
                $question = $v["question_id"];
                $answer = $v["answer_id"];
                $q2 = "SELECT * FROM `ts_data_question_answer` WHERE `id`='$question'";
                $x = $this->sql_query_get($q2);
                $answers = explode(".",$x[0]["answer"]);
                $answers = array_filter( $answers, function($el) {return !empty($el);} );
                foreach ($answers as $k2=>$v2) {
                    if ($answer == $v2 && $answer == $x[0]["true"]) {
                        $counter++;
                    }
                }
            }
        }
        $result["true"]=$counter;

        $q = "  SELECT *
                FROM `ts_data_question_answer`
                WHERE `ts_data_user_answer`.answer_id = `ts_data_question_answer`.true
                AND `ts_data_user_answer`.test_id = '$test_id'
                AND `ts_data_user_answer`.user_id = '$user_id'
                AND `ts_data_user_answer`.key = '$key'
                ";

        $q = "  SELECT `id`
                FROM `ts_data_user_answer`
                WHERE `ts_data_user_answer`.test_id = '$test_id'
                AND `ts_data_user_answer`.user_id = '$user_id'
                AND `ts_data_user_answer`.key = '$key'
                ";
        $result["answers"] = $this->sql_query_get_num($q);

        $result["total"] = $this->data['num_questions'];
        return $result;
    }

}

?>