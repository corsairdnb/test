<?php

class User extends Common {

    public $data;

    public function User () {
        $sid = session_id();
        $this->data = $this->sql_query_get("
            SELECT *, `ts_data_group`.name AS group_name, `ts_data_user`.name AS user_name, `ts_data_user`.id AS user_id, `ts_data_test`.name AS test_name, `ts_rel_test`.id AS tid, `ts_rel_test`.subject_id, `ts_data_subject`.name AS subject_name
            FROM (
            SELECT * FROM `ts_data_user_test`
            WHERE `ts_data_user_test`.session = '".$sid."'
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
        $ar = $this->sql_query_get("
            SELECT * FROM `ts_data_test_question`
        ");
        $questions = explode(".", $ar[0]['question']);
        $questions = array_filter( $questions, function($el) {return !empty($el);} );
        shuffle($questions);
        return json_encode($questions);
    }

    public function getQuestion() {
        $ar = $this->sql_query_get("
            SELECT * FROM `ts_data_test_question` WHERE `id` = '".$this->data['test_id']."'
        ");
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

}

?>